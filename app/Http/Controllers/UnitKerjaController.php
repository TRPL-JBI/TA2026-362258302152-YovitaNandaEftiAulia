<?php

namespace App\Http\Controllers;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UnitKerjaController extends Controller
{
    /**
     * Menampilkan daftar Unit Kerja.
     */
    public function index(): View
    {
        $data = UnitKerja::query()
            ->with('kepalaUnit')
            ->orderBy('id', 'desc')
            ->get();

        return view(
            'unit.index',
            compact('data')
        );
    }

    /**
     * Menampilkan form penugasan Unit Kerja.
     */
    public function create(): View
    {
        $users = User::query()
            ->where('role', 'auditee')
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $unitKerja = UnitKerja::query()
            ->with('kepalaUnit')
            ->orderBy('nama')
            ->get();

        return view(
            'unit.create',
            compact(
                'users',
                'unitKerja'
            )
        );
    }

    /**
     * Menyimpan penugasan Unit Kerja kepada user.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'id_user' => [
                    'required',
                    'integer',

                    Rule::exists('users', 'id')
                        ->where(function ($query) {
                            $query
                                ->where('role', 'auditee')
                                ->where('status', 'aktif');
                        }),
                ],

                'unit_kerja_ids' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'unit_kerja_ids.*' => [
                    'required',
                    'integer',
                    'distinct',
                    'exists:unit_kerja,id',
                ],
            ],
            [
                'id_user.required' =>
                    'Nama User wajib dipilih.',

                'id_user.integer' =>
                    'Nama User tidak valid.',

                'id_user.exists' =>
                    'User Auditee tidak ditemukan atau tidak aktif.',

                'unit_kerja_ids.required' =>
                    'Pilih minimal satu Unit Kerja.',

                'unit_kerja_ids.array' =>
                    'Pilihan Unit Kerja tidak valid.',

                'unit_kerja_ids.min' =>
                    'Pilih minimal satu Unit Kerja.',

                'unit_kerja_ids.*.integer' =>
                    'Pilihan Unit Kerja tidak valid.',

                'unit_kerja_ids.*.distinct' =>
                    'Unit Kerja tidak boleh dipilih dua kali.',

                'unit_kerja_ids.*.exists' =>
                    'Salah satu Unit Kerja tidak ditemukan.',
            ]
        );

        $idUser = (int) $validated['id_user'];

        $unitKerjaIds = collect(
            $validated['unit_kerja_ids']
        )
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use (
            $idUser,
            $unitKerjaIds
        ) {
            /*
             * Lepaskan Unit Kerja lama milik user yang
             * tidak dipilih lagi.
             */
            UnitKerja::query()
                ->where('id_user', $idUser)
                ->whereNotIn('id', $unitKerjaIds)
                ->update([
                    'id_user' => null,
                ]);

            /*
             * Simpan seluruh Unit Kerja yang dipilih.
             */
            UnitKerja::query()
                ->whereIn('id', $unitKerjaIds)
                ->update([
                    'id_user' => $idUser,
                ]);

            /*
             * Kolom lama users.id_unit_kerja tetap diisi
             * menggunakan Unit Kerja pertama.
             */
            User::query()
                ->where('id', $idUser)
                ->update([
                    'id_unit_kerja' =>
                        $unitKerjaIds[0] ?? null,
                ]);
        });

        $user = User::find($idUser);

        return redirect()
            ->route('unit-kerja.index')
            ->with(
                'success',
                'Penugasan Unit Kerja untuk ' .
                ($user?->nama ?? 'user') .
                ' berhasil disimpan.'
            );
    }

    /**
     * Menampilkan detail Unit Kerja.
     */
    public function show(
        int|string $id
    ): View {
        $data = UnitKerja::query()
            ->with('kepalaUnit')
            ->findOrFail($id);

        return view(
            'unit.show',
            compact('data')
        );
    }

    /**
     * Menampilkan form edit penugasan user.
     *
     * Parameter ID yang diterima adalah ID Unit Kerja.
     * Dari Unit Kerja tersebut sistem mengambil user
     * yang saat ini menjadi Kepala Unit.
     */
    public function edit(
        int|string $id
    ): View {
        $data = UnitKerja::query()
            ->with('kepalaUnit')
            ->findOrFail($id);

        $users = User::query()
            ->where('role', 'auditee')
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $unitKerja = UnitKerja::query()
            ->with('kepalaUnit')
            ->orderBy('nama')
            ->get();

        $idUserLama = $data->id_user;

        $unitTerpilih = [];

        if ($idUserLama) {
            $unitTerpilih = UnitKerja::query()
                ->where('id_user', $idUserLama)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();
        } else {
            $unitTerpilih = [
                (int) $data->id,
            ];
        }

        return view(
            'unit.edit',
            compact(
                'data',
                'users',
                'unitKerja',
                'idUserLama',
                'unitTerpilih'
            )
        );
    }

    /**
     * Memperbarui penugasan Unit Kerja.
     */
    public function update(
        Request $request,
        int|string $id
    ): RedirectResponse {
        $unitAwal = UnitKerja::findOrFail($id);

        $validated = $request->validate(
            [
                'id_user' => [
                    'required',
                    'integer',

                    Rule::exists('users', 'id')
                        ->where(function ($query) {
                            $query
                                ->where('role', 'auditee')
                                ->where('status', 'aktif');
                        }),
                ],

                'id_user_lama' => [
                    'nullable',
                    'integer',
                    'exists:users,id',
                ],

                'unit_kerja_ids' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'unit_kerja_ids.*' => [
                    'required',
                    'integer',
                    'distinct',
                    'exists:unit_kerja,id',
                ],
            ],
            [
                'id_user.required' =>
                    'Nama User wajib dipilih.',

                'id_user.integer' =>
                    'Nama User tidak valid.',

                'id_user.exists' =>
                    'User Auditee tidak ditemukan atau tidak aktif.',

                'unit_kerja_ids.required' =>
                    'Pilih minimal satu Unit Kerja.',

                'unit_kerja_ids.array' =>
                    'Pilihan Unit Kerja tidak valid.',

                'unit_kerja_ids.min' =>
                    'Pilih minimal satu Unit Kerja.',

                'unit_kerja_ids.*.integer' =>
                    'Pilihan Unit Kerja tidak valid.',

                'unit_kerja_ids.*.distinct' =>
                    'Unit Kerja tidak boleh dipilih dua kali.',

                'unit_kerja_ids.*.exists' =>
                    'Salah satu Unit Kerja tidak ditemukan.',
            ]
        );

        $idUserBaru = (int) $validated['id_user'];

        $idUserLama = isset($validated['id_user_lama'])
            ? (int) $validated['id_user_lama']
            : (int) ($unitAwal->id_user ?? 0);

        $unitKerjaIds = collect(
            $validated['unit_kerja_ids']
        )
            ->map(fn ($unitId) => (int) $unitId)
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use (
            $idUserBaru,
            $idUserLama,
            $unitKerjaIds
        ) {
            /*
             * Jika user lama tersedia, lepaskan semua unit
             * milik user lama terlebih dahulu.
             */
            if ($idUserLama > 0) {
                UnitKerja::query()
                    ->where('id_user', $idUserLama)
                    ->update([
                        'id_user' => null,
                    ]);

                User::query()
                    ->where('id', $idUserLama)
                    ->update([
                        'id_unit_kerja' => null,
                    ]);
            }

            /*
             * Jika user baru sebelumnya mempunyai unit lain,
             * lepaskan unit yang tidak dipilih lagi.
             */
            UnitKerja::query()
                ->where('id_user', $idUserBaru)
                ->whereNotIn('id', $unitKerjaIds)
                ->update([
                    'id_user' => null,
                ]);

            /*
             * Pasangkan seluruh unit terpilih kepada user baru.
             */
            UnitKerja::query()
                ->whereIn('id', $unitKerjaIds)
                ->update([
                    'id_user' => $idUserBaru,
                ]);

            User::query()
                ->where('id', $idUserBaru)
                ->update([
                    'id_unit_kerja' =>
                        $unitKerjaIds[0] ?? null,
                ]);
        });

        $user = User::find($idUserBaru);

        return redirect()
            ->route('unit-kerja.index')
            ->with(
                'success',
                'Penugasan Unit Kerja untuk ' .
                ($user?->nama ?? 'user') .
                ' berhasil diperbarui.'
            );
    }

    /**
     * Menampilkan konfirmasi hapus Unit Kerja.
     */
    public function delete(
        int|string $id
    ): View {
        $data = UnitKerja::findOrFail($id);

        $sedangDigunakan = DB::table('periode_ami')
            ->where('id_unit_kerja', $data->id)
            ->exists();

        return view(
            'unit.delete',
            compact(
                'data',
                'sedangDigunakan'
            )
        );
    }

    /**
     * Menghapus data Unit Kerja.
     */
    public function destroy(
        int|string $id
    ): RedirectResponse {
        $data = UnitKerja::findOrFail($id);

        $dipakaiPadaPeriode = DB::table('periode_ami')
            ->where('id_unit_kerja', $data->id)
            ->exists();

        if ($dipakaiPadaPeriode) {
            return redirect()
                ->route('unit-kerja.index')
                ->with(
                    'error',
                    'Unit Kerja tidak dapat dihapus karena sudah digunakan pada Periode AMI.'
                );
        }

        try {
            $data->delete();

            return redirect()
                ->route('unit-kerja.index')
                ->with(
                    'success',
                    'Unit Kerja berhasil dihapus.'
                );
        } catch (QueryException $exception) {
            if (
                (string) $exception->getCode()
                === '23000'
            ) {
                return redirect()
                    ->route('unit-kerja.index')
                    ->with(
                        'error',
                        'Unit Kerja tidak dapat dihapus karena masih digunakan oleh data lain.'
                    );
            }

            report($exception);

            return redirect()
                ->route('unit-kerja.index')
                ->with(
                    'error',
                    'Terjadi kesalahan saat menghapus Unit Kerja.'
                );
        }
    }
}