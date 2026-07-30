<?php

namespace App\Http\Controllers;

use App\Models\IndikatorStandar;
use App\Models\PenerapanStandar;
use App\Models\Rekomendasi;
use App\Models\SkalaSkor;
use App\Models\SkorPenerapanStandar;
use App\Models\TemuanAmi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TemuanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX PENILAIAN AUDIT
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $idAuditor = $this->getLoginUserId();

        $idPeriodeDitugaskan = DB::table('tim_ami')
            ->where('id_user', $idAuditor)
            ->pluck('id_periode_ami')
            ->filter()
            ->unique()
            ->values();

        $idStandarMutuDitugaskan = DB::table(
            'standarmutu_periodeami'
        )
            ->whereIn(
                'id_periode_ami',
                $idPeriodeDitugaskan
            )
            ->pluck('id_standar_mutu')
            ->filter()
            ->unique()
            ->values();

        $data = IndikatorStandar::with([
            'isiStandar',
            'isiStandar.standarMutu',

            'isiStandar.parent',
            'isiStandar.parent.standarMutu',

            'isiStandar.parent.parent',
            'isiStandar.parent.parent.standarMutu',

            'isiStandar.parent.parent.parent',
            'isiStandar.parent.parent.parent.standarMutu',

            'penerapan' => function ($query) use (
                $idPeriodeDitugaskan
            ) {
                $query
                    ->with([
                        'user',

                        'standarmutuPeriode',
                        'standarmutuPeriode.standarMutu',
                        'standarmutuPeriode.periodeAmi',
                        'standarmutuPeriode.periodeAmi.unitKerja',

                        'standarmutuPeriode.periodeAmi.kesimpulanAudit',
                        'standarmutuPeriode.periodeAmi.kesimpulanAudit.user',

                        'standarmutuPeriode.periodeAmi.lampiran',
                        'standarmutuPeriode.periodeAmi.lampiran.user',

                        /*
                        |------------------------------------------------------
                        | SKOR PENILAIAN
                        |------------------------------------------------------
                        */

                        'skor',
                        'skor.skalaSkor',

                        /*
                        |------------------------------------------------------
                        | TEMUAN DAN REKOMENDASI
                        |------------------------------------------------------
                        */

                        'temuan',
                        'temuan.rekomendasi',
                        'temuan.rekomendasi.user',

                        /*
                        |------------------------------------------------------
                        | TANGGAPAN DAN AKAR MASALAH
                        |------------------------------------------------------
                        */

                        'temuan.tanggapan',
                        'temuan.tanggapan.user',

                        'temuan.akarMasalah',
                        'temuan.akarMasalah.user',
                    ])
                    ->whereHas(
                        'standarmutuPeriode',
                        function ($subQuery) use (
                            $idPeriodeDitugaskan
                        ) {
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
                function ($query) use (
                    $idStandarMutuDitugaskan
                ) {
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
                    ?->where('status_temuan', 'open')
                    ->count() ?? 0;
            });

        $temuanClosed = $semuaPenerapan
            ->sum(function ($penerapan) {
                return $penerapan->temuan
                    ?->where('status_temuan', 'closed')
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
    | CREATE PENILAIAN
    |--------------------------------------------------------------------------
    */

    public function create(
        PenerapanStandar $penerapan
    ): View {
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
            'skor',
            'skor.skalaSkor',
        ]);

        $this->pastikanAuditorDitugaskan($penerapan);

        abort_unless(
            $this->penerapanLengkap($penerapan),
            403,
            'Penilaian belum dapat dibuat karena Auditee belum melengkapi hasil dan bukti penerapan.'
        );

        $skalaSkor = SkalaSkor::orderBy('nilai_skor')
            ->get();

        return view(
            'auditor.temuan.create',
            compact(
                'penerapan',
                'skalaSkor'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE PENILAIAN
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate(
            $this->validationRules(),
            $this->validationMessages()
        );

        $penerapan = PenerapanStandar::with([
            'standarmutuPeriode',
            'standarmutuPeriode.periodeAmi',
        ])->findOrFail(
            $validated['id_penerapan_standar']
        );

        $this->pastikanAuditorDitugaskan($penerapan);

        if (!$this->penerapanLengkap($penerapan)) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Penilaian belum dapat ditambahkan karena hasil atau bukti penerapan belum lengkap.'
                );
        }

        $idAuditor = $this->getLoginUserId();

        DB::transaction(function () use (
            $validated,
            $penerapan,
            $idAuditor
        ) {
            /*
            |--------------------------------------------------------------
            | SIMPAN ATAU PERBARUI SKOR PENERAPAN
            |--------------------------------------------------------------
            */

            SkorPenerapanStandar::updateOrCreate(
                [
                    'id_penerapan_standar' =>
                        $penerapan->id,
                ],
                [
                    'id_skala_skor' =>
                        $validated['id_skala_skor'],
                ]
            );

            /*
            |--------------------------------------------------------------
            | SIMPAN TEMUAN / HASIL PENILAIAN
            |--------------------------------------------------------------
            */

            $temuan = TemuanAmi::create([
                'id_penerapan_standar' =>
                    $penerapan->id,

                'jenis_temuan' =>
                    $validated['jenis_temuan'],

                'temuan' =>
                    in_array(
                        $validated['jenis_temuan'],
                        ['kts', 'ob'],
                        true
                    )
                        ? $validated['temuan']
                        : null,

                'status_temuan' =>
                    $validated['status_temuan'],
            ]);

            /*
            |--------------------------------------------------------------
            | SIMPAN REKOMENDASI PER TEMUAN
            |--------------------------------------------------------------
            */

            Rekomendasi::create([
                'id_temuan' =>
                    $temuan->id,

                'aspek' =>
                    $validated['aspek'],

                'deskripsi' =>
                    $validated['deskripsi'],

                'rekomendasi' =>
                    $validated['rekomendasi'],

                'id_user' =>
                    $idAuditor,
            ]);
        });

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
                'success',
                'Penilaian Audit berhasil ditambahkan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW PENILAIAN
    |--------------------------------------------------------------------------
    */

    public function show(
        TemuanAmi $temuan
    ): View {
        $temuan->load([
            'penerapanStandar',
            'penerapanStandar.user',

            'penerapanStandar.skor',
            'penerapanStandar.skor.skalaSkor',

            'penerapanStandar.indikator',
            'penerapanStandar.indikator.isiStandar',
            'penerapanStandar.indikator.isiStandar.parent',
            'penerapanStandar.indikator.isiStandar.parent.parent',
            'penerapanStandar.indikator.isiStandar.parent.parent.parent',

            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.standarMutu',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
            'penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',

            'penerapanStandar.standarmutuPeriode.periodeAmi.kesimpulanAudit',
            'penerapanStandar.standarmutuPeriode.periodeAmi.kesimpulanAudit.user',

            'penerapanStandar.standarmutuPeriode.periodeAmi.lampiran',
            'penerapanStandar.standarmutuPeriode.periodeAmi.lampiran.user',

            'rekomendasi',
            'rekomendasi.user',

            'tanggapan',
            'tanggapan.user',

            'akarMasalah',
            'akarMasalah.user',
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
    | EDIT PENILAIAN
    |--------------------------------------------------------------------------
    */

    public function edit(
        TemuanAmi $temuan
    ): View {
        $temuan->load([
            'penerapanStandar',
            'penerapanStandar.user',

            'penerapanStandar.skor',
            'penerapanStandar.skor.skalaSkor',

            'penerapanStandar.indikator',
            'penerapanStandar.indikator.isiStandar',
            'penerapanStandar.indikator.isiStandar.parent',
            'penerapanStandar.indikator.isiStandar.parent.parent',
            'penerapanStandar.indikator.isiStandar.parent.parent.parent',

            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.standarMutu',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
            'penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',

            'rekomendasi',
            'rekomendasi.user',

            'tanggapan',
            'tanggapan.user',

            'akarMasalah',
            'akarMasalah.user',
        ]);

        $this->pastikanAuditorDitugaskan(
            $temuan->penerapanStandar
        );

        $skalaSkor = SkalaSkor::orderBy('nilai_skor')
            ->get();

        return view(
            'auditor.temuan.edit',
            compact(
                'temuan',
                'skalaSkor'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PENILAIAN
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        TemuanAmi $temuan
    ): RedirectResponse {
        $validated = $request->validate(
            $this->validationRules(false),
            $this->validationMessages()
        );

        $temuan->load([
            'penerapanStandar',
            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
            'rekomendasi',
        ]);

        $penerapan = $temuan->penerapanStandar;

        $this->pastikanAuditorDitugaskan($penerapan);

        $idAuditor = $this->getLoginUserId();

        DB::transaction(function () use (
            $validated,
            $temuan,
            $penerapan,
            $idAuditor
        ) {
            SkorPenerapanStandar::updateOrCreate(
                [
                    'id_penerapan_standar' =>
                        $penerapan->id,
                ],
                [
                    'id_skala_skor' =>
                        $validated['id_skala_skor'],
                ]
            );

            $temuan->update([
                'jenis_temuan' =>
                    $validated['jenis_temuan'],

                'temuan' =>
                    in_array(
                        $validated['jenis_temuan'],
                        ['kts', 'ob'],
                        true
                    )
                        ? $validated['temuan']
                        : null,

                'status_temuan' =>
                    $validated['status_temuan'],
            ]);

            Rekomendasi::updateOrCreate(
                [
                    'id_temuan' =>
                        $temuan->id,
                ],
                [
                    'aspek' =>
                        $validated['aspek'],

                    'deskripsi' =>
                        $validated['deskripsi'],

                    'rekomendasi' =>
                        $validated['rekomendasi'],

                    'id_user' =>
                        $idAuditor,
                ]
            );
        });

        return redirect()
            ->route(
                'auditor.temuan.show',
                $temuan->id
            )
            ->with(
                'success',
                'Penilaian Audit berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    */

    public function close(
        TemuanAmi $temuan
    ): RedirectResponse {
        $temuan->load([
            'penerapanStandar',
            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
        ]);

        $this->pastikanAuditorDitugaskan(
            $temuan->penerapanStandar
        );

        $temuan->update([
            'status_temuan' => 'closed',
        ]);

        return back()->with(
            'success',
            'Status Penilaian Audit berhasil ditutup.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(
        TemuanAmi $temuan
    ): RedirectResponse {
        $temuan->load([
            'penerapanStandar',
            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.periodeAmi',

            'rekomendasi',
            'tanggapan',
            'akarMasalah',
        ]);

        $this->pastikanAuditorDitugaskan(
            $temuan->penerapanStandar
        );

        DB::transaction(function () use ($temuan) {
            /*
            | Rekomendasi harus dihapus manual karena TemuanAmi
            | menggunakan soft delete.
            */

            if ($temuan->rekomendasi) {
                $temuan->rekomendasi->delete();
            }

            $temuan->tanggapan()->delete();
            $temuan->akarMasalah()->delete();
            $temuan->delete();
        });

        return redirect()
            ->route('auditor.temuan.index')
            ->with(
                'success',
                'Penilaian Audit berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */

    private function validationRules(
        bool $includePenerapan = true
    ): array {
        $rules = [
            'id_skala_skor' => [
                'required',
                'integer',
                'exists:skala_skor,id',
            ],

            'jenis_temuan' => [
                'required',
                Rule::in([
                    'sesuai_standar',
                    'kts',
                    'ob',
                ]),
            ],

            'temuan' => [
                'nullable',
                'required_if:jenis_temuan,kts,ob',
                'string',
                'max:5000',
            ],

            'aspek' => [
                'required',
                'string',
                'max:255',
            ],

            'deskripsi' => [
                'required',
                'string',
                'max:5000',
            ],

            'rekomendasi' => [
                'required',
                'string',
                'max:5000',
            ],

            'status_temuan' => [
                'required',
                Rule::in([
                    'open',
                    'closed',
                ]),
            ],
        ];

        if ($includePenerapan) {
            $rules['id_penerapan_standar'] = [
                'required',
                'integer',
                'exists:penerapan_standar,id',
            ];
        }

        return $rules;
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION MESSAGES
    |--------------------------------------------------------------------------
    */

    private function validationMessages(): array
    {
        return [
            'id_penerapan_standar.required' =>
                'Data penerapan standar tidak ditemukan.',

            'id_penerapan_standar.exists' =>
                'Data penerapan standar tidak valid.',

            'id_skala_skor.required' =>
                'Skor penilaian wajib dipilih.',

            'id_skala_skor.exists' =>
                'Skor penilaian tidak valid.',

            'jenis_temuan.required' =>
                'Jenis temuan wajib dipilih.',

            'jenis_temuan.in' =>
                'Jenis temuan tidak valid.',

            'temuan.required_if' =>
                'Isi temuan wajib diisi untuk jenis KTS atau OB.',

            'temuan.max' =>
                'Isi temuan maksimal 5000 karakter.',

            'aspek.required' =>
                'Aspek penilaian wajib diisi.',

            'aspek.max' =>
                'Aspek maksimal 255 karakter.',

            'deskripsi.required' =>
                'Deskripsi wajib diisi.',

            'deskripsi.max' =>
                'Deskripsi maksimal 5000 karakter.',

            'rekomendasi.required' =>
                'Rekomendasi wajib diisi.',

            'rekomendasi.max' =>
                'Rekomendasi maksimal 5000 karakter.',

            'status_temuan.required' =>
                'Status penilaian wajib dipilih.',

            'status_temuan.in' =>
                'Status penilaian tidak valid.',
        ];
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
            ->where('id_user', $idAuditor)
            ->where('id_periode_ami', $idPeriode)
            ->exists();

        abort_unless(
            $ditugaskan,
            403,
            'Anda tidak ditugaskan pada periode AMI ini.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL ID USER LOGIN
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