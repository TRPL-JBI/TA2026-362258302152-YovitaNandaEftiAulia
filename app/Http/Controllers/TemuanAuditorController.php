<?php

namespace App\Http\Controllers;

use App\Models\IndikatorStandar;
use App\Models\PenerapanStandar;
use App\Models\Rekomendasi;
use App\Models\TemuanAmi;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TemuanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Menampilkan indikator dan penerapan standar yang hanya berasal dari
    | periode AMI tempat auditor sedang ditugaskan.
    |
    */

    public function index(): View
    {
        $idAuditor = $this->getLoginUserId();

        $idPeriodeDitugaskan = $this
            ->idPeriodeAuditor($idAuditor);

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
            /*
            |--------------------------------------------------------------------------
            | STRUKTUR STANDAR
            |--------------------------------------------------------------------------
            */

            'isiStandar',
            'isiStandar.standarMutu',

            'isiStandar.parent',
            'isiStandar.parent.standarMutu',

            'isiStandar.parent.parent',
            'isiStandar.parent.parent.standarMutu',

            'isiStandar.parent.parent.parent',
            'isiStandar.parent.parent.parent.standarMutu',

            /*
            |--------------------------------------------------------------------------
            | PENERAPAN STANDAR
            |--------------------------------------------------------------------------
            */

            'penerapan' => function ($query) use (
                $idPeriodeDitugaskan
            ) {
                $query
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
                        |--------------------------------------------------------------------------
                        | TEMUAN DAN DATA TERKAIT
                        |--------------------------------------------------------------------------
                        */

                        'temuan',

                        'temuan.tanggapan',
                        'temuan.tanggapan.user',

                        'temuan.akarMasalah',
                        'temuan.akarMasalah.user',

                        /*
                        |--------------------------------------------------------------------------
                        | REKOMENDASI BARU
                        |--------------------------------------------------------------------------
                        |
                        | Rekomendasi mengikuti Temuan melalui id_temuan.
                        |
                        */

                        'temuan.rekomendasi',
                        'temuan.rekomendasi.user',
                    ])
                    ->orderByDesc('id');
            },
        ])
            ->when(
                $idStandarMutuDitugaskan->isNotEmpty(),
                function ($query) use (
                    $idStandarMutuDitugaskan
                ) {
                    $query->whereHas(
                        'isiStandar',
                        function ($subQuery) use (
                            $idStandarMutuDitugaskan
                        ) {
                            $subQuery->whereIn(
                                'id_standar_mutu',
                                $idStandarMutuDitugaskan
                            );
                        }
                    );
                },
                function ($query) {
                    $query->whereRaw('1 = 0');
                }
            )
            ->orderBy('id_isi_standar_mutu')
            ->orderBy('id')
            ->get()
            ->unique('id')
            ->values();

        $semuaPenerapan = $data
            ->flatMap(function ($indikator) {
                return $indikator->penerapan
                    ?? collect();
            });

        $totalPenerapan = $semuaPenerapan->count();

        $penerapanLengkap = $semuaPenerapan
            ->filter(function ($penerapan) {
                return $this->penerapanLengkap(
                    $penerapan
                );
            })
            ->count();

        $penerapanBelumLengkap =
            $totalPenerapan - $penerapanLengkap;

        $totalTemuan = $semuaPenerapan
            ->sum(function ($penerapan) {
                return $penerapan->temuan?->count()
                    ?? 0;
            });

        $temuanOpen = $semuaPenerapan
            ->sum(function ($penerapan) {
                return $penerapan->temuan
                    ?->filter(function ($temuan) {
                        return strtolower(
                            trim(
                                (string)
                                $temuan->status_temuan
                            )
                        ) === 'open';
                    })
                    ->count()
                    ?? 0;
            });

        $temuanClosed = $semuaPenerapan
            ->sum(function ($penerapan) {
                return $penerapan->temuan
                    ?->filter(function ($temuan) {
                        return strtolower(
                            trim(
                                (string)
                                $temuan->status_temuan
                            )
                        ) === 'closed';
                    })
                    ->count()
                    ?? 0;
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
    | Skala skor tetap dipertahankan.
    |
    */

    public function create(
        PenerapanStandar $penerapan
    ): View {
        $penerapan = $this->findPenerapanAuditor(
            (int) $penerapan->id
        );

        $this->pastikanPeriodeBelumDitutup(
            $penerapan
        );

        abort_unless(
            $this->penerapanLengkap($penerapan),
            403,
            'Temuan belum dapat dibuat karena Auditee belum melengkapi hasil dan bukti penerapan.'
        );

        /*
        |--------------------------------------------------------------------------
        | AMBIL SKALA SKOR
        |--------------------------------------------------------------------------
        */

        $tabelSkalaSkor =
            $this->tabelSkalaSkor();

        abort_unless(
            $tabelSkalaSkor,
            500,
            'Tabel skala skor tidak ditemukan.'
        );

        $skalaSkor = DB::table(
            $tabelSkalaSkor
        )
            ->orderBy('id')
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
    | STORE
    |--------------------------------------------------------------------------
    |
    | Saat membuat penilaian:
    |
    | - skala skor tetap disimpan;
    | - status penerapan tetap disimpan;
    | - jenis temuan tetap disimpan;
    | - rekomendasi disimpan melalui id_temuan;
    | - status temuan selalu dipaksa menjadi open;
    | - auditor harus terdaftar pada tim_ami;
    | - periode yang sudah ditutup tidak dapat diubah.
    |
    */

    public function store(
        Request $request
    ): RedirectResponse {
        $tabelSkalaSkor =
            $this->tabelSkalaSkor();

        abort_unless(
            $tabelSkalaSkor,
            500,
            'Tabel skala skor tidak ditemukan.'
        );

        $validated = $request->validate(
            [
                'id_penerapan_standar' => [
                    'required',
                    'integer',
                    'exists:penerapan_standar,id',
                ],

                'status_penerapan' => [
                    'required',
                    'string',
                    Rule::in([
                        'sesuai',
                        'belum_sesuai',
                    ]),
                ],

                'id_skala_skor' => [
                    'required',
                    'integer',
                    Rule::exists(
                        $tabelSkalaSkor,
                        'id'
                    ),
                ],

                'jenis_temuan' => [
                    'required',
                    'string',
                    Rule::in([
                        'sesuai_standar',
                        'kts',
                        'ob',
                    ]),
                ],

                /*
                | Isi temuan wajib untuk KTS dan OB.
                | Untuk Sesuai Standar, deskripsi rekomendasi
                | digunakan sebagai ringkasan temuan positif.
                */

                'temuan' => [
                    'nullable',
                    'required_if:jenis_temuan,kts,ob',
                    'string',
                    'max:5000',
                ],

                /*
                | Rekomendasi tetap wajib untuk semua jenis:
                |
                | - Sesuai Standar = rekomendasi peningkatan.
                | - KTS/OB = rekomendasi perbaikan.
                */

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
            ],
            [
                'id_penerapan_standar.required' =>
                    'Data penerapan standar tidak ditemukan.',

                'id_penerapan_standar.integer' =>
                    'Data penerapan standar tidak valid.',

                'id_penerapan_standar.exists' =>
                    'Data penerapan standar tidak ditemukan.',

                'status_penerapan.required' =>
                    'Status penerapan wajib dipilih.',

                'status_penerapan.in' =>
                    'Status penerapan tidak valid.',

                'id_skala_skor.required' =>
                    'Skala skor wajib dipilih.',

                'id_skala_skor.integer' =>
                    'Skala skor tidak valid.',

                'id_skala_skor.exists' =>
                    'Skala skor yang dipilih tidak ditemukan.',

                'jenis_temuan.required' =>
                    'Jenis temuan wajib dipilih.',

                'jenis_temuan.in' =>
                    'Jenis temuan tidak valid.',

                'temuan.required_if' =>
                    'Isi temuan wajib diisi untuk jenis KTS atau OB.',

                'temuan.string' =>
                    'Isi temuan audit harus berupa teks.',

                'temuan.max' =>
                    'Isi temuan audit maksimal 5000 karakter.',

                'aspek.required' =>
                    'Aspek rekomendasi wajib diisi.',

                'aspek.string' =>
                    'Aspek rekomendasi harus berupa teks.',

                'aspek.max' =>
                    'Aspek rekomendasi maksimal 255 karakter.',

                'deskripsi.required' =>
                    'Deskripsi rekomendasi wajib diisi.',

                'deskripsi.string' =>
                    'Deskripsi rekomendasi harus berupa teks.',

                'deskripsi.max' =>
                    'Deskripsi rekomendasi maksimal 5000 karakter.',

                'rekomendasi.required' =>
                    'Rekomendasi wajib diisi.',

                'rekomendasi.string' =>
                    'Rekomendasi harus berupa teks.',

                'rekomendasi.max' =>
                    'Rekomendasi maksimal 5000 karakter.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | PENERAPAN HARUS BERASAL DARI PERIODE TUGAS AUDITOR
        |--------------------------------------------------------------------------
        */

        $penerapan = $this->findPenerapanAuditor(
            (int) $validated[
                'id_penerapan_standar'
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE HARUS BELUM DITUTUP
        |--------------------------------------------------------------------------
        */

        $this->pastikanPeriodeBelumDitutup(
            $penerapan
        );

        /*
        |--------------------------------------------------------------------------
        | HASIL DAN BUKTI PENERAPAN HARUS LENGKAP
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

        $temuan = DB::transaction(
            function () use (
                $validated,
                $penerapan,
                $tabelSkalaSkor
            ) {
                /*
                |--------------------------------------------------------------------------
                | SIMPAN STATUS PENERAPAN
                |--------------------------------------------------------------------------
                */

                if (
                    Schema::hasColumn(
                        'penerapan_standar',
                        'status_penerapan'
                    )
                ) {
                    $dataStatusPenerapan = [
                        'status_penerapan' =>
                            $validated['status_penerapan'],
                    ];

                    if (
                        Schema::hasColumn(
                            'penerapan_standar',
                            'updated_at'
                        )
                    ) {
                        $dataStatusPenerapan['updated_at'] =
                            now();
                    }

                    DB::table('penerapan_standar')
                        ->where(
                            'id',
                            $penerapan->id
                        )
                        ->update(
                            $dataStatusPenerapan
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | SIAPKAN ISI TEMUAN
                |--------------------------------------------------------------------------
                |
                | Form Sesuai Standar tidak mewajibkan kolom temuan.
                | Agar rekomendasi tetap dapat terhubung melalui id_temuan,
                | deskripsi kondisi positif dipakai sebagai ringkasan temuan.
                |
                */

                $isiTemuan = trim(
                    (string) (
                        $validated['temuan']
                        ?? ''
                    )
                );

                if ($isiTemuan === '') {
                    $isiTemuan = trim(
                        $validated['deskripsi']
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | SIMPAN TEMUAN
                |--------------------------------------------------------------------------
                |
                | Status tidak diambil dari request.
                | Setiap temuan baru wajib berstatus Open.
                |
                */

                $dataTemuan = [
                    'id_penerapan_standar' =>
                        $penerapan->id,

                    'temuan' =>
                        $isiTemuan,

                    'status_temuan' =>
                        'open',
                ];

                if (
                    Schema::hasColumn(
                        'temuan_ami',
                        'jenis_temuan'
                    )
                ) {
                    $dataTemuan['jenis_temuan'] =
                        $validated['jenis_temuan'];
                }

                $temuan = TemuanAmi::create(
                    $dataTemuan
                );

                /*
                |--------------------------------------------------------------------------
                | SIMPAN SKALA SKOR
                |--------------------------------------------------------------------------
                */

                $this->simpanSkalaSkor(
                    idPenerapan: (int) $penerapan->id,
                    idSkalaSkor: (int) $validated[
                        'id_skala_skor'
                    ],
                    idTemuan: (int) $temuan->id,
                    tabelSkalaSkor: $tabelSkalaSkor
                );

                /*
                |--------------------------------------------------------------------------
                | SIMPAN REKOMENDASI BERDASARKAN TEMUAN
                |--------------------------------------------------------------------------
                |
                | Rekomendasi disimpan untuk semua jenis temuan:
                |
                | - Sesuai Standar = peningkatan.
                | - KTS/OB = perbaikan.
                |
                */

                Rekomendasi::create([
                    'id_temuan' =>
                        $temuan->id,

                    'aspek' =>
                        trim(
                            $validated['aspek']
                        ),

                    'deskripsi' =>
                        trim(
                            $validated['deskripsi']
                        ),

                    'rekomendasi' =>
                        trim(
                            $validated['rekomendasi']
                        ),

                    'id_user' =>
                        $this->getLoginUserId(),
                ]);

                return $temuan;
            }
        );

        return redirect()
            ->route(
                'auditor.temuan.show',
                $temuan->id
            )
            ->with(
                'success',
                'Penilaian, temuan, dan rekomendasi berhasil disimpan dengan status Open.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(
        TemuanAmi $temuan
    ): View {
        $temuan = $this->findTemuanAuditor(
            (int) $temuan->id
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

    public function edit(
        TemuanAmi $temuan
    ): View {
        $temuan = $this->findTemuanAuditor(
            (int) $temuan->id
        );

        $this->pastikanPeriodeBelumDitutup(
            $temuan->penerapanStandar
        );

        $this->pastikanTemuanMasihOpen(
            $temuan
        );

        /*
        | Tetap dikirim agar halaman edit dapat menggunakan
        | skala skor apabila diperlukan.
        */

        $tabelSkalaSkor =
            $this->tabelSkalaSkor();

        $skalaSkor = $tabelSkalaSkor
            ? DB::table($tabelSkalaSkor)
                ->orderBy('id')
                ->get()
            : collect();

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
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Status tidak dapat diubah melalui halaman edit.
    | Status closed hanya melalui method close().
    |
    */

    public function update(
        Request $request,
        TemuanAmi $temuan
    ): RedirectResponse {
        $tabelSkalaSkor =
            $this->tabelSkalaSkor();

        $rules = [
            'temuan' => [
                'required',
                'string',
                'max:5000',
            ],

            'jenis_temuan' => [
                'nullable',
                'string',
                Rule::in([
                    'sesuai_standar',
                    'kts',
                    'ob',
                ]),
            ],
        ];

        if ($tabelSkalaSkor) {
            $rules['id_skala_skor'] = [
                'nullable',
                'integer',
                Rule::exists(
                    $tabelSkalaSkor,
                    'id'
                ),
            ];
        }

        $validated = $request->validate(
            $rules,
            [
                'temuan.required' =>
                    'Isi temuan audit wajib diisi.',

                'temuan.string' =>
                    'Isi temuan audit harus berupa teks.',

                'temuan.max' =>
                    'Isi temuan audit maksimal 5000 karakter.',

                'jenis_temuan.in' =>
                    'Jenis temuan tidak valid.',

                'id_skala_skor.exists' =>
                    'Skala skor yang dipilih tidak ditemukan.',
            ]
        );

        $temuan = $this->findTemuanAuditor(
            (int) $temuan->id
        );

        $this->pastikanPeriodeBelumDitutup(
            $temuan->penerapanStandar
        );

        $this->pastikanTemuanMasihOpen(
            $temuan
        );

        DB::transaction(
            function () use (
                $temuan,
                $validated,
                $tabelSkalaSkor
            ) {
                $dataUpdate = [
                    'temuan' =>
                        $validated['temuan'],
                ];

                if (
                    Schema::hasColumn(
                        'temuan_ami',
                        'jenis_temuan'
                    )
                    && !empty(
                        $validated['jenis_temuan']
                    )
                ) {
                    $dataUpdate['jenis_temuan'] =
                        $validated['jenis_temuan'];
                }

                $temuan->update(
                    $dataUpdate
                );

                if (
                    $tabelSkalaSkor
                    && !empty(
                        $validated['id_skala_skor']
                    )
                ) {
                    $this->simpanSkalaSkor(
                        idPenerapan:
                            (int) $temuan
                                ->id_penerapan_standar,

                        idSkalaSkor:
                            (int) $validated[
                                'id_skala_skor'
                            ],

                        idTemuan:
                            (int) $temuan->id,

                        tabelSkalaSkor:
                            $tabelSkalaSkor
                    );
                }
            }
        );

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
    | SIMPAN ATAU PERBARUI REKOMENDASI
    |--------------------------------------------------------------------------
    |
    | Rekomendasi sekarang melekat ke Temuan melalui id_temuan.
    |
    */

    public function simpanRekomendasi(
        Request $request,
        TemuanAmi $temuan
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'aspek' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'deskripsi' => [
                    'required',
                    'string',
                    'max:10000',
                ],

                'rekomendasi' => [
                    'required',
                    'string',
                    'max:10000',
                ],
            ],
            [
                'aspek.required' =>
                    'Aspek rekomendasi wajib diisi.',

                'aspek.max' =>
                    'Aspek rekomendasi maksimal 255 karakter.',

                'deskripsi.required' =>
                    'Deskripsi rekomendasi wajib diisi.',

                'deskripsi.max' =>
                    'Deskripsi maksimal 10.000 karakter.',

                'rekomendasi.required' =>
                    'Isi rekomendasi wajib diisi.',

                'rekomendasi.max' =>
                    'Rekomendasi maksimal 10.000 karakter.',
            ]
        );

        $temuan = $this->findTemuanAuditor(
            (int) $temuan->id
        );

        $this->pastikanPeriodeBelumDitutup(
            $temuan->penerapanStandar
        );

        $this->pastikanTemuanMasihOpen(
            $temuan
        );

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
                    $this->getLoginUserId(),
            ]
        );

        return redirect()
            ->route(
                'auditor.temuan.show',
                $temuan->id
            )
            ->with(
                'success',
                'Rekomendasi berhasil disimpan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS REKOMENDASI
    |--------------------------------------------------------------------------
    */

    public function hapusRekomendasi(
        TemuanAmi $temuan
    ): RedirectResponse {
        $temuan = $this->findTemuanAuditor(
            (int) $temuan->id
        );

        $this->pastikanPeriodeBelumDitutup(
            $temuan->penerapanStandar
        );

        $this->pastikanTemuanMasihOpen(
            $temuan
        );

        $rekomendasi =
            $temuan->rekomendasi;

        if ($rekomendasi instanceof Collection) {
            $rekomendasi->each(
                fn ($item) => $item->delete()
            );
        } elseif ($rekomendasi) {
            $rekomendasi->delete();
        }

        return redirect()
            ->route(
                'auditor.temuan.show',
                $temuan->id
            )
            ->with(
                'success',
                'Rekomendasi berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CLOSE
    |--------------------------------------------------------------------------
    |
    | Status Closed hanya dapat diberikan melalui method ini.
    |
    | Syarat minimal:
    |
    | 1. User adalah Auditor yang ditugaskan pada tim_ami.
    | 2. Periode AMI belum ditutup.
    | 3. Tanggapan Auditee sudah ada.
    | 4. Akar masalah sudah ada khusus untuk KTS atau OB.
    | 5. Rekomendasi sudah ada.
    | 6. Bukti atau keterangan tindak lanjut sudah tersedia.
    |
    */

    public function close(
        TemuanAmi $temuan
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | FILTER BERDASARKAN TIM AMI
        |--------------------------------------------------------------------------
        |
        | findTemuanAuditor() memastikan temuan hanya dapat diakses
        | oleh Auditor yang ditugaskan pada periode terkait.
        |
        */

        $temuan = $this->findTemuanAuditor(
            (int) $temuan->id
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE HARUS BELUM DITUTUP
        |--------------------------------------------------------------------------
        */

        $this->pastikanPeriodeBelumDitutup(
            $temuan->penerapanStandar
        );

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS SAAT INI
        |--------------------------------------------------------------------------
        */

        if (
            strtolower(
                trim(
                    (string)
                    $temuan->status_temuan
                )
            ) === 'closed'
        ) {
            return redirect()
                ->route(
                    'auditor.temuan.show',
                    $temuan->id
                )
                ->with(
                    'success',
                    'Temuan sudah berstatus Closed.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | TANGGAPAN AUDITEE WAJIB ADA
        |--------------------------------------------------------------------------
        */

        $daftarTanggapan =
            $this->menjadiCollection(
                $temuan->tanggapan
            );

        $punyaTanggapan =
            $daftarTanggapan->contains(
                function ($tanggapan) {
                    return trim(
                        (string) (
                            $tanggapan->tanggapan
                            ?? ''
                        )
                    ) !== '';
                }
            );

        if (!$punyaTanggapan) {
            return redirect()
                ->route(
                    'auditor.temuan.show',
                    $temuan->id
                )
                ->with(
                    'error',
                    'Temuan belum dapat ditutup karena Auditee belum memberikan tanggapan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | AKAR MASALAH WAJIB KHUSUS KTS ATAU OB
        |--------------------------------------------------------------------------
        |
        | Untuk jenis Sesuai Standar, akar masalah tidak diwajibkan.
        |
        */

        $jenisTemuan = strtolower(
            trim(
                (string) (
                    $temuan->jenis_temuan
                    ?? ''
                )
            )
        );

        $wajibAkarMasalah = in_array(
            $jenisTemuan,
            [
                'kts',
                'ob',
            ],
            true
        );

        if ($wajibAkarMasalah) {
            $daftarAkarMasalah =
                $this->menjadiCollection(
                    $temuan->akarMasalah
                );

            $punyaAkarMasalah =
                $daftarAkarMasalah->contains(
                    function ($akarMasalah) {
                        return trim(
                            (string) (
                                $akarMasalah->akar_masalah
                                ?? ''
                            )
                        ) !== '';
                    }
                );

            if (!$punyaAkarMasalah) {
                return redirect()
                    ->route(
                        'auditor.temuan.show',
                        $temuan->id
                    )
                    ->with(
                        'error',
                        'Temuan KTS atau OB belum dapat ditutup karena akar masalah belum diisi.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | REKOMENDASI WAJIB ADA UNTUK SEMUA JENIS TEMUAN
        |--------------------------------------------------------------------------
        */

        $daftarRekomendasi =
            $this->menjadiCollection(
                $temuan->rekomendasi
            );

        $punyaRekomendasi =
            $daftarRekomendasi->contains(
                function ($rekomendasi) {
                    return trim(
                        (string) (
                            $rekomendasi->aspek
                            ?? ''
                        )
                    ) !== ''
                    && trim(
                        (string) (
                            $rekomendasi->deskripsi
                            ?? ''
                        )
                    ) !== ''
                    && trim(
                        (string) (
                            $rekomendasi->rekomendasi
                            ?? ''
                        )
                    ) !== '';
                }
            );

        if (!$punyaRekomendasi) {
            return redirect()
                ->route(
                    'auditor.temuan.show',
                    $temuan->id
                )
                ->with(
                    'error',
                    'Temuan belum dapat ditutup karena rekomendasi belum lengkap.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | BUKTI ATAU KETERANGAN TINDAK LANJUT WAJIB ADA
        |--------------------------------------------------------------------------
        */

        $punyaTindakLanjut =
            $daftarTanggapan->contains(
                function ($tanggapan) {
                    return $this
                        ->tanggapanMemilikiTindakLanjut(
                            $tanggapan
                        );
                }
            );

        if (!$punyaTindakLanjut) {
            return redirect()
                ->route(
                    'auditor.temuan.show',
                    $temuan->id
                )
                ->with(
                    'error',
                    'Temuan belum dapat ditutup karena bukti atau keterangan tindak lanjut belum tersedia.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | UBAH STATUS MENJADI CLOSED
        |--------------------------------------------------------------------------
        */

        $temuan->update([
            'status_temuan' =>
                'closed',
        ]);

        return redirect()
            ->route(
                'auditor.temuan.show',
                $temuan->id
            )
            ->with(
                'success',
                'Temuan berhasil diverifikasi dan ditutup.'
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
        $temuan = $this->findTemuanAuditor(
            (int) $temuan->id
        );

        $this->pastikanPeriodeBelumDitutup(
            $temuan->penerapanStandar
        );

        $this->pastikanTemuanMasihOpen(
            $temuan
        );

        DB::transaction(
            function () use ($temuan) {
                $temuan->tanggapan()
                    ->delete();

                $temuan->akarMasalah()
                    ->delete();

                $temuan->rekomendasi()
                    ->delete();

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
    | QUERY TEMUAN MILIK PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function queryTemuanAuditor(
        int $idAuditor
    ): Builder {
        $idPeriodeDitugaskan =
            $this->idPeriodeAuditor(
                $idAuditor
            );

        return TemuanAmi::query()
            ->whereHas(
                'penerapanStandar.standarmutuPeriode',
                function ($query) use (
                    $idPeriodeDitugaskan
                ) {
                    $query->whereIn(
                        'id_periode_ami',
                        $idPeriodeDitugaskan
                    );
                }
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CARI TEMUAN SESUAI TIM AMI
    |--------------------------------------------------------------------------
    */

    private function findTemuanAuditor(
        int $idTemuan
    ): TemuanAmi {
        $idAuditor =
            $this->getLoginUserId();

        return $this
            ->queryTemuanAuditor($idAuditor)
            ->with([
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

                'penerapanStandar.standarmutuPeriode.periodeAmi.kesimpulanAudit',
                'penerapanStandar.standarmutuPeriode.periodeAmi.kesimpulanAudit.user',

                'penerapanStandar.standarmutuPeriode.periodeAmi.lampiran',
                'penerapanStandar.standarmutuPeriode.periodeAmi.lampiran.user',

                'tanggapan',
                'tanggapan.user',

                'akarMasalah',
                'akarMasalah.user',

                'rekomendasi',
                'rekomendasi.user',
            ])
            ->findOrFail($idTemuan);
    }

    /*
    |--------------------------------------------------------------------------
    | CARI PENERAPAN SESUAI TIM AMI
    |--------------------------------------------------------------------------
    */

    private function findPenerapanAuditor(
        int $idPenerapan
    ): PenerapanStandar {
        $idAuditor =
            $this->getLoginUserId();

        $idPeriodeDitugaskan =
            $this->idPeriodeAuditor(
                $idAuditor
            );

        return PenerapanStandar::with([
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
            'temuan.tanggapan',
            'temuan.akarMasalah',
            'temuan.rekomendasi',
        ])
            ->whereHas(
                'standarmutuPeriode',
                function ($query) use (
                    $idPeriodeDitugaskan
                ) {
                    $query->whereIn(
                        'id_periode_ami',
                        $idPeriodeDitugaskan
                    );
                }
            )
            ->findOrFail($idPenerapan);
    }

    /*
    |--------------------------------------------------------------------------
    | ID PERIODE PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    */

    private function idPeriodeAuditor(
        int $idAuditor
    ): Collection {
        return DB::table('tim_ami')
            ->where(
                'id_user',
                $idAuditor
            )
            ->pluck('id_periode_ami')
            ->filter()
            ->unique()
            ->values();
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
            (string) (
                $penerapan->deskripsi_hasil
                ?? ''
            )
        );

        $linkBukti = trim(
            (string) (
                $penerapan->link_bukti
                ?? ''
            )
        );

        return $deskripsiHasil !== ''
            && $linkBukti !== '';
    }

    /*
    |--------------------------------------------------------------------------
    | KUNCI DATA SAAT PERIODE DITUTUP
    |--------------------------------------------------------------------------
    */

    private function pastikanPeriodeBelumDitutup(
        PenerapanStandar $penerapan
    ): void {
        $penerapan->loadMissing([
            'standarmutuPeriode',
            'standarmutuPeriode.periodeAmi',
        ]);

        $periode = $penerapan
            ->standarmutuPeriode
            ?->periodeAmi;

        abort_unless(
            $periode,
            404,
            'Periode AMI tidak ditemukan.'
        );

        $statusPeriode = strtolower(
            trim(
                (string) (
                    $periode->status
                    ?? ''
                )
            )
        );

        abort_if(
            in_array(
                $statusPeriode,
                [
                    'ditutup',
                    'closed',
                    'selesai',
                ],
                true
            ),
            403,
            'Data tidak dapat diubah karena periode AMI sudah ditutup.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PASTIKAN TEMUAN MASIH OPEN
    |--------------------------------------------------------------------------
    */

    private function pastikanTemuanMasihOpen(
        TemuanAmi $temuan
    ): void {
        $statusTemuan = strtolower(
            trim(
                (string) (
                    $temuan->status_temuan
                    ?? ''
                )
            )
        );

        abort_if(
            $statusTemuan === 'closed',
            403,
            'Temuan yang sudah ditutup tidak dapat diubah atau dihapus.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CARI NAMA TABEL SKALA SKOR
    |--------------------------------------------------------------------------
    */

    private function tabelSkalaSkor(): ?string
    {
        $daftarTabel = [
            'skala_skor',
            'skala_skor_audit',
            'skala_penilaian',
        ];

        foreach ($daftarTabel as $namaTabel) {
            if (Schema::hasTable($namaTabel)) {
                return $namaTabel;
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN SKALA SKOR
    |--------------------------------------------------------------------------
    |
    | Method ini mendukung beberapa struktur penyimpanan skor:
    |
    | 1. Kolom id_skala_skor pada penerapan_standar.
    | 2. Tabel skor_penerapan_standar.
    | 3. Kolom id_skala_skor pada temuan_ami.
    | 4. Kolom skor berupa angka.
    |
    */

    private function simpanSkalaSkor(
        int $idPenerapan,
        int $idSkalaSkor,
        int $idTemuan,
        string $tabelSkalaSkor
    ): void {
        $dataSkala = DB::table(
            $tabelSkalaSkor
        )
            ->where(
                'id',
                $idSkalaSkor
            )
            ->first();

        abort_unless(
            $dataSkala,
            422,
            'Skala skor yang dipilih tidak ditemukan.'
        );

        /*
        |--------------------------------------------------------------------------
        | SKOR LANGSUNG PADA PENERAPAN STANDAR
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasColumn(
                'penerapan_standar',
                'id_skala_skor'
            )
        ) {
            DB::table('penerapan_standar')
                ->where(
                    'id',
                    $idPenerapan
                )
                ->update([
                    'id_skala_skor' =>
                        $idSkalaSkor,
                ]);

            return;
        }

        if (
            Schema::hasColumn(
                'penerapan_standar',
                'skala_skor_id'
            )
        ) {
            DB::table('penerapan_standar')
                ->where(
                    'id',
                    $idPenerapan
                )
                ->update([
                    'skala_skor_id' =>
                        $idSkalaSkor,
                ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | TABEL KHUSUS SKOR PENERAPAN
        |--------------------------------------------------------------------------
        */

        $daftarTabelSkor = [
            'skor_penerapan_standar',
            'penilaian_penerapan_standar',
            'skor_penerapan',
        ];

        foreach (
            $daftarTabelSkor
            as $tabelSkor
        ) {
            if (
                !Schema::hasTable(
                    $tabelSkor
                )
            ) {
                continue;
            }

            if (
                !Schema::hasColumn(
                    $tabelSkor,
                    'id_skala_skor'
                )
            ) {
                continue;
            }

            $kunci = [];

            if (
                Schema::hasColumn(
                    $tabelSkor,
                    'id_penerapan_standar'
                )
            ) {
                $kunci[
                    'id_penerapan_standar'
                ] = $idPenerapan;
            } elseif (
                Schema::hasColumn(
                    $tabelSkor,
                    'id_temuan'
                )
            ) {
                $kunci[
                    'id_temuan'
                ] = $idTemuan;
            } else {
                continue;
            }

            $nilai = [
                'id_skala_skor' =>
                    $idSkalaSkor,
            ];

            if (
                Schema::hasColumn(
                    $tabelSkor,
                    'id_user'
                )
            ) {
                $nilai['id_user'] =
                    $this->getLoginUserId();
            }

            if (
                Schema::hasColumn(
                    $tabelSkor,
                    'updated_at'
                )
            ) {
                $nilai['updated_at'] =
                    now();
            }

            if (
                Schema::hasColumn(
                    $tabelSkor,
                    'created_at'
                )
            ) {
                $nilai['created_at'] =
                    now();
            }

            DB::table($tabelSkor)
                ->updateOrInsert(
                    $kunci,
                    $nilai
                );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SKOR LANGSUNG PADA TEMUAN
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasColumn(
                'temuan_ami',
                'id_skala_skor'
            )
        ) {
            DB::table('temuan_ami')
                ->where(
                    'id',
                    $idTemuan
                )
                ->update([
                    'id_skala_skor' =>
                        $idSkalaSkor,
                ]);

            return;
        }

        if (
            Schema::hasColumn(
                'temuan_ami',
                'skala_skor_id'
            )
        ) {
            DB::table('temuan_ami')
                ->where(
                    'id',
                    $idTemuan
                )
                ->update([
                    'skala_skor_id' =>
                        $idSkalaSkor,
                ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN NILAI SKOR LANGSUNG
        |--------------------------------------------------------------------------
        */

        $nilaiSkor =
            $dataSkala->nilai_skor
            ?? $dataSkala->skor
            ?? $dataSkala->nilai
            ?? null;

        if (
            $nilaiSkor !== null
            && Schema::hasColumn(
                'penerapan_standar',
                'skor'
            )
        ) {
            DB::table('penerapan_standar')
                ->where(
                    'id',
                    $idPenerapan
                )
                ->update([
                    'skor' =>
                        $nilaiSkor,
                ]);

            return;
        }

        if (
            $nilaiSkor !== null
            && Schema::hasColumn(
                'temuan_ami',
                'skor'
            )
        ) {
            DB::table('temuan_ami')
                ->where(
                    'id',
                    $idTemuan
                )
                ->update([
                    'skor' =>
                        $nilaiSkor,
                ]);

            return;
        }

        abort(
            500,
            'Tempat penyimpanan skala skor belum ditemukan pada struktur database.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UBAH RELASI MENJADI COLLECTION
    |--------------------------------------------------------------------------
    */

    private function menjadiCollection(
        mixed $data
    ): Collection {
        if ($data instanceof Collection) {
            return $data;
        }

        if ($data) {
            return collect([$data]);
        }

        return collect();
    }

    /*
    |--------------------------------------------------------------------------
    | CEK BUKTI ATAU KETERANGAN TINDAK LANJUT
    |--------------------------------------------------------------------------
    */

    private function tanggapanMemilikiTindakLanjut(
        mixed $tanggapan
    ): bool {
        $daftarKolomBukti = [
            'link_bukti',
            'bukti_tindak_lanjut',
            'link_bukti_tindak_lanjut',
            'dokumen_tindak_lanjut',
            'tindak_lanjut',
        ];

        foreach (
            $daftarKolomBukti
            as $namaKolom
        ) {
            $nilai = trim(
                (string) (
                    $tanggapan->{$namaKolom}
                    ?? ''
                )
            );

            if ($nilai !== '') {
                return true;
            }
        }

        /*
        | Apabila struktur tanggapan saat ini hanya memiliki
        | kolom tanggapan, isinya dipakai sebagai keterangan
        | tindak lanjut.
        */

        return trim(
            (string) (
                $tanggapan->tanggapan
                ?? ''
            )
        ) !== '';
    }

    /*
    |--------------------------------------------------------------------------
    | ID USER LOGIN
    |--------------------------------------------------------------------------
    |
    | Session hanya menyimpan user_id.
    | Data pengguna diperiksa kembali dari database agar status dan role
    | terbaru selalu digunakan.
    |
    */

    private function getLoginUserId(): int
    {
        $idUser = session('user_id');

        abort_unless(
            $idUser,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $user = request()
            ->attributes
            ->get('auth_user');

        if (
            !$user instanceof User
            || (int) $user->id !== (int) $idUser
        ) {
            $user = User::query()
                ->find($idUser);
        }

        abort_unless(
            $user,
            401,
            'Data pengguna yang sedang login tidak ditemukan.'
        );

        $statusUser = strtolower(
            trim(
                (string) $user->status
            )
        );

        $roleUser = strtolower(
            trim(
                (string) $user->role
            )
        );

        abort_unless(
            $statusUser === 'aktif',
            403,
            'Akun tidak ditemukan atau sudah dinonaktifkan.'
        );

        abort_unless(
            $roleUser === 'auditor',
            403,
            'Halaman ini hanya dapat diakses oleh Auditor.'
        );

        return (int) $user->id;
    }
}
