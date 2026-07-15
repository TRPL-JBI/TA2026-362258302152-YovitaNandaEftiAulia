<?php

namespace App\Http\Controllers;

use App\Models\IndikatorStandar;
use App\Models\PenerapanStandar;
use App\Models\StandarMutuPeriodeAmi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuditeePenerapanController extends Controller
{
    public function create(Request $request, int $standar)
    {
        $user = $this->currentUser();

        $standarPeriode = StandarMutuPeriodeAmi::with([
            'standarMutu',
            'periodeAmi',
        ])
            ->where('status', 'aktif')
            ->whereHas('periodeAmi', function ($query) use ($user) {
                $query->where('id_unit_kerja', $user['id_unit_kerja']);
            })
            ->findOrFail($standar);

        $request->validate([
            'indikator' => ['required', 'integer', 'exists:indikator_standar,id'],
        ]);

        $indikator = IndikatorStandar::with('isiStandar')
            ->findOrFail($request->integer('indikator'));

        abort_unless(
            (int) $indikator->isiStandar->id_standar_mutu ===
            (int) $standarPeriode->id_standar_mutu,
            404
        );

        $idUser = $user['id'];

        $penerapan = PenerapanStandar::query()
            ->where('id_standarmutu_periodeami', $standarPeriode->id)
            ->where('id_indikator', $indikator->id)
            ->where('id_user', $idUser)
            ->first();

        if ($penerapan) {
            return redirect()->route('auditee.penerapan.edit', $penerapan->id);
        }

        return view('auditee.penerapan.create', [
            'standar' => $standarPeriode,
            'indikator' => $indikator,
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->currentUser();
        $idUser = $user['id'];

        $validated = $request->validate([
            'id_standarmutu_periodeami' => [
                'required',
                'integer',
                'exists:standarmutu_periodeami,id',
            ],
            'id_indikator' => [
                'required',
                'integer',
                'exists:indikator_standar,id',
                Rule::unique('penerapan_standar', 'id_indikator')
                    ->where(fn ($query) => $query
                        ->where(
                            'id_standarmutu_periodeami',
                            $request->integer('id_standarmutu_periodeami')
                        )
                        ->where('id_user', $idUser)),
            ],
            'deskripsi_hasil' => ['required', 'string'],
            'link_bukti' => ['nullable', 'url', 'max:2048'],
        ], [
            'id_indikator.unique' => 'Penerapan untuk indikator ini sudah pernah diisi.',
        ]);

        $standarPeriode = StandarMutuPeriodeAmi::query()
            ->where('status', 'aktif')
            ->whereHas('periodeAmi', function ($query) use ($user) {
                $query->where('id_unit_kerja', $user['id_unit_kerja']);
            })
            ->findOrFail($validated['id_standarmutu_periodeami']);

        $indikator = IndikatorStandar::with('isiStandar')
            ->findOrFail($validated['id_indikator']);

        abort_unless(
            (int) $indikator->isiStandar->id_standar_mutu ===
            (int) $standarPeriode->id_standar_mutu,
            422,
            'Indikator tidak sesuai dengan standar mutu yang dipilih.'
        );

        PenerapanStandar::create([
            'id_standarmutu_periodeami' => $standarPeriode->id,
            'id_indikator' => $indikator->id,
            'deskripsi_hasil' => $validated['deskripsi_hasil'],
            'link_bukti' => $validated['link_bukti'] ?? null,
            'id_user' => $idUser,
        ]);

        return redirect()
            ->route('auditee.standar.index', $standarPeriode->id_standar_mutu)
            ->with('success', 'Penerapan standar berhasil disimpan.');
    }

    public function edit(int $id)
    {
        $data = $this->findOwnedPenerapan($id);

        $data->load([
            'standarmutuPeriode.standarMutu',
            'standarmutuPeriode.periodeAmi',
            'indikator.isiStandar',
        ]);

        return view('auditee.penerapan.edit', compact('data'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'deskripsi_hasil' => ['required', 'string'],
            'link_bukti' => ['nullable', 'url', 'max:2048'],
        ]);

        $data = $this->findOwnedPenerapan($id);
        $data->load('standarmutuPeriode');
        $data->update($validated);

        return redirect()
            ->route(
                'auditee.standar.index',
                $data->standarmutuPeriode->id_standar_mutu
            )
            ->with('success', 'Penerapan standar berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $data = $this->findOwnedPenerapan($id);
        $data->load('standarmutuPeriode');

        $standarMutuId = $data->standarmutuPeriode->id_standar_mutu;
        $data->delete();

        return redirect()
            ->route('auditee.standar.index', $standarMutuId)
            ->with('success', 'Penerapan standar berhasil dihapus.');
    }

    private function findOwnedPenerapan(int $id): PenerapanStandar
    {
        return PenerapanStandar::query()
            ->where('id_user', $this->currentUser()['id'])
            ->findOrFail($id);
    }

    private function currentUser(): array
    {
        $user = session('user');

        $idUser = is_array($user)
            ? ($user['id'] ?? null)
            : ($user->id ?? null);

        $idUnitKerja = is_array($user)
            ? ($user['id_unit_kerja'] ?? null)
            : ($user->id_unit_kerja ?? null);

        abort_unless(
            $idUser && $idUnitKerja,
            403,
            'Data pengguna atau unit kerja tidak ditemukan.'
        );

        return [
            'id' => (int) $idUser,
            'id_unit_kerja' => (int) $idUnitKerja,
        ];
    }
}
