<?php

namespace App\Http\Controllers;

use App\Models\IndikatorStandar;
use App\Models\PenerapanStandar;
use App\Models\TemuanAmi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemuanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Menampilkan seluruh indikator dan penerapan pada periode
    | yang ditugaskan kepada Auditor.
    |
    | Auditor hanya melihat data penerapan milik Auditee.
    | Auditor tidak dapat mengubah penerapan tersebut.
    |
    */
public function index()
{
    $idAuditor = $this->getLoginUserId();

    /*
    |--------------------------------------------------------------------------
    | PERIODE PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    $idPeriodeDitugaskan = DB::table('tim_ami')
        ->where('id_user', $idAuditor)
        ->pluck('id_periode_ami')
        ->filter()
        ->unique()
        ->values();

    /*
    |--------------------------------------------------------------------------
    | STANDAR MUTU DALAM PERIODE PENUGASAN
    |--------------------------------------------------------------------------
    */

    $idStandarMutuDitugaskan = DB::table('standarmutu_periodeami')
        ->whereIn(
            'id_periode_ami',
            $idPeriodeDitugaskan
        )
        ->pluck('id_standar_mutu')
        ->filter()
        ->unique()
        ->values();

    /*
    |--------------------------------------------------------------------------
    | INDIKATOR PADA STANDAR MUTU YANG DITUGASKAN
    |--------------------------------------------------------------------------
    */

    $data = IndikatorStandar::with([
        'isiStandar',
        'isiStandar.standarMutu',

        'isiStandar.parent',
        'isiStandar.parent.standarMutu',

        'isiStandar.parent.parent',
        'isiStandar.parent.parent.standarMutu',

        'isiStandar.parent.parent.parent',
        'isiStandar.parent.parent.parent.standarMutu',

        'penerapan' => function ($query) use ($idPeriodeDitugaskan) {
            $query
                ->with([
                    'user',

                    'standarmutuPeriode',
                    'standarmutuPeriode.standarMutu',
                    'standarmutuPeriode.periodeAmi',
                    'standarmutuPeriode.periodeAmi.unitKerja',

                    'temuan',
                    'temuan.tanggapan',
                ])
                ->whereHas(
                    'standarmutuPeriode',
                    function ($subQuery) use ($idPeriodeDitugaskan) {
                        $subQuery->whereIn(
                            'id_periode_ami',
                            $idPeriodeDitugaskan
                        );
                    }
                )
                ->orderByDesc('id');
        },
    ])
        ->whereHas(
            'isiStandar',
            function ($query) use ($idStandarMutuDitugaskan) {
                $query->whereIn(
                    'id_standar_mutu',
                    $idStandarMutuDitugaskan
                );
            }
        )
        ->orderBy('id_isi_standar_mutu')
        ->orderBy('id')
        ->get()
        ->unique('id')
        ->values();

    // Bagian perhitungan statistik tetap lanjut di bawah sini.

    /*
    |--------------------------------------------------------------------------
    | RINGKASAN
    |--------------------------------------------------------------------------
    */

    $semuaPenerapan = $data
        ->flatMap(function ($indikator) {
            return $indikator->penerapan ?? collect();
        });

    $totalPenerapan = $semuaPenerapan->count();

    $penerapanLengkap = $semuaPenerapan
        ->filter(function ($penerapan) {
            return $this->penerapanLengkap($penerapan);
        })
        ->count();

    $penerapanBelumLengkap =
        $totalPenerapan - $penerapanLengkap;

    $totalTemuan = $semuaPenerapan
        ->sum(function ($penerapan) {
            return $penerapan->temuan?->count() ?? 0;
        });

    $temuanOpen = $semuaPenerapan
        ->sum(function ($penerapan) {
            return $penerapan->temuan
                ?->filter(function ($temuan) {
                    return strtolower(
                        trim((string) $temuan->status_temuan)
                    ) === 'open';
                })
                ->count() ?? 0;
        });

    $temuanClosed = $semuaPenerapan
        ->sum(function ($penerapan) {
            return $penerapan->temuan
                ?->filter(function ($temuan) {
                    return strtolower(
                        trim((string) $temuan->status_temuan)
                    ) === 'closed';
                })
                ->count() ?? 0;
        });

    return view(
        'auditor.temuan.index',
        compact(
            'data',
            'totalPenerapan',
            'penerapanLengkap',
            'penerapanBelumLengkap',
            'totalTemuan',
            'temuanOpen',
            'temuanClosed'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    |
    | Auditor hanya dapat membuka halaman create apabila:
    |
    | - deskripsi_hasil sudah diisi;
    | - link_bukti sudah tersedia.
    |
    */

    public function create(
        PenerapanStandar $penerapan
    ) {
        $penerapan->load([
            'user',

            'indikator',
            'indikator.isiStandar',
            'indikator.isiStandar.parent',
            'indikator.isiStandar.parent.parent',
            'indikator.isiStandar.parent.parent.parent',

            'standarmutuPeriode',
            'standarmutuPeriode.standarMutu',
            'standarmutuPeriode.periodeAmi',
            'standarmutuPeriode.periodeAmi.unitKerja',

            'temuan',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PENUGASAN AUDITOR
        |--------------------------------------------------------------------------
        */

        $this->pastikanAuditorDitugaskan(
            $penerapan
        );

        /*
        |--------------------------------------------------------------------------
        | CEK PENERAPAN SUDAH LENGKAP
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $this->penerapanLengkap($penerapan),
            403,
            'Temuan belum dapat dibuat karena Auditee belum melengkapi hasil dan bukti penerapan.'
        );

        return view(
            'auditor.temuan.create',
            compact('penerapan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'id_penerapan_standar' => [
                    'required',
                    'integer',
                    'exists:penerapan_standar,id',
                ],

                'temuan' => [
                    'required',
                    'string',
                    'max:5000',
                ],

                'status_temuan' => [
                    'required',
                    'in:open,closed',
                ],
            ],
            [
                'id_penerapan_standar.required' =>
                    'Data penerapan standar tidak ditemukan.',

                'id_penerapan_standar.exists' =>
                    'Data penerapan standar tidak valid.',

                'temuan.required' =>
                    'Isi temuan audit wajib diisi.',

                'status_temuan.required' =>
                    'Status temuan wajib dipilih.',

                'status_temuan.in' =>
                    'Status temuan tidak valid.',
            ]
        );

        $penerapan = PenerapanStandar::with([
            'standarmutuPeriode',
            'standarmutuPeriode.periodeAmi',
        ])->findOrFail(
            $validated['id_penerapan_standar']
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PENUGASAN
        |--------------------------------------------------------------------------
        */

        $this->pastikanAuditorDitugaskan(
            $penerapan
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI PENERAPAN
        |--------------------------------------------------------------------------
        */

        if (!$this->penerapanLengkap($penerapan)) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Temuan belum dapat ditambahkan karena hasil atau bukti penerapan belum lengkap.'
                );
        }

        TemuanAmi::create([
            'id_penerapan_standar' =>
                $penerapan->id,

            'temuan' =>
                $validated['temuan'],

            'status_temuan' =>
                $validated['status_temuan'],
        ]);

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
                'success',
                'Temuan Audit berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(TemuanAmi $temuan)
    {
        $temuan->load([
            'penerapanStandar',

            'penerapanStandar.user',

            'penerapanStandar.indikator',
            'penerapanStandar.indikator.isiStandar',
            'penerapanStandar.indikator.isiStandar.parent',
            'penerapanStandar.indikator.isiStandar.parent.parent',
            'penerapanStandar.indikator.isiStandar.parent.parent.parent',

            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.standarMutu',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
            'penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',

            'tanggapan',
            'tanggapan.user',
        ]);

        $this->pastikanAuditorDitugaskan(
            $temuan->penerapanStandar
        );

        return view(
            'auditor.temuan.show',
            compact('temuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(TemuanAmi $temuan)
    {
        $temuan->load([
            'penerapanStandar',

            'penerapanStandar.user',

            'penerapanStandar.indikator',
            'penerapanStandar.indikator.isiStandar',
            'penerapanStandar.indikator.isiStandar.parent',
            'penerapanStandar.indikator.isiStandar.parent.parent',
            'penerapanStandar.indikator.isiStandar.parent.parent.parent',

            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.standarMutu',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
            'penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',
        ]);

        $this->pastikanAuditorDitugaskan(
            $temuan->penerapanStandar
        );

        return view(
            'auditor.temuan.edit',
            compact('temuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        TemuanAmi $temuan
    ) {
        $validated = $request->validate(
            [
                'temuan' => [
                    'required',
                    'string',
                    'max:5000',
                ],

                'status_temuan' => [
                    'required',
                    'in:open,closed',
                ],
            ],
            [
                'temuan.required' =>
                    'Isi temuan audit wajib diisi.',

                'status_temuan.required' =>
                    'Status temuan wajib dipilih.',

                'status_temuan.in' =>
                    'Status temuan tidak valid.',
            ]
        );

        $temuan->load([
            'penerapanStandar',
            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
        ]);

        $this->pastikanAuditorDitugaskan(
            $temuan->penerapanStandar
        );

        $temuan->update([
            'temuan' =>
                $validated['temuan'],

            'status_temuan' =>
                $validated['status_temuan'],
        ]);

        return redirect()
            ->route(
                'auditor.temuan.show',
                $temuan->id
            )
            ->with(
                'success',
                'Temuan Audit berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(TemuanAmi $temuan)
    {
        $temuan->load([
            'penerapanStandar',
            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
        ]);

        $this->pastikanAuditorDitugaskan(
            $temuan->penerapanStandar
        );

        /*
        |--------------------------------------------------------------------------
        | HAPUS TANGGAPAN TERKAIT
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use ($temuan) {
                $temuan->tanggapan()->delete();
                $temuan->delete();
            }
        );

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
                'success',
                'Temuan Audit berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CEK PENERAPAN LENGKAP
    |--------------------------------------------------------------------------
    */

    private function penerapanLengkap(
        PenerapanStandar $penerapan
    ): bool {
        $deskripsiHasil = trim(
            (string) $penerapan->deskripsi_hasil
        );

        $linkBukti = trim(
            (string) $penerapan->link_bukti
        );

        return $deskripsiHasil !== ''
            && $linkBukti !== '';
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function pastikanAuditorDitugaskan(
        PenerapanStandar $penerapan
    ): void {
        $idAuditor = $this->getLoginUserId();

        $idPeriode = $penerapan
            ->standarmutuPeriode
            ?->id_periode_ami;

        abort_unless(
            $idPeriode,
            404,
            'Periode AMI dari penerapan tidak ditemukan.'
        );

        $ditugaskan = DB::table('tim_ami')
            ->where(
                'id_user',
                $idAuditor
            )
            ->where(
                'id_periode_ami',
                $idPeriode
            )
            ->exists();

        abort_unless(
            $ditugaskan,
            403,
            'Anda tidak ditugaskan pada periode AMI ini.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ID USER LOGIN
    |--------------------------------------------------------------------------
    */

    private function getLoginUserId(): int
    {
        $user = session('user');

        abort_unless(
            $user,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $idUser = is_array($user)
            ? ($user['id'] ?? null)
            : ($user->id ?? null);

        abort_unless(
            $idUser,
            401,
            'ID pengguna tidak ditemukan pada sesi.'
        );

        return (int) $idUser;
    }
}