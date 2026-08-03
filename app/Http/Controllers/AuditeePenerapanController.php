<?php

namespace App\Http\Controllers;

use App\Models\IndikatorStandar;
use App\Models\PenerapanStandar;
use App\Models\StandarMutuPeriodeAmi;
use App\Models\User;
use App\Traits\ChecksPeriodeAmiStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuditeePenerapanController extends Controller
{
    use ChecksPeriodeAmiStatus;

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    |
    | Menampilkan form tambah penerapan standar.
    | Form tidak dapat dibuka apabila periode AMI sudah ditutup.
    |
    */

    public function create(
        Request $request,
        int $standar
    ): View|RedirectResponse {
        $user = $this->currentUser();

        $standarPeriode = StandarMutuPeriodeAmi::with([
            'standarMutu',
            'periodeAmi',
        ])
            ->where('status', 'aktif')
            ->whereHas(
                'periodeAmi',
                function ($query) use ($user) {
                    $query->where(
                        'id_unit_kerja',
                        $user['id_unit_kerja']
                    );
                }
            )
            ->findOrFail($standar);

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed(
            $standarPeriode->periodeAmi
        );

        $request->validate(
            [
                'indikator' => [
                    'required',
                    'integer',
                    'exists:indikator_standar,id',
                ],
            ],
            [
                'indikator.required' =>
                    'Indikator wajib dipilih.',

                'indikator.integer' =>
                    'Indikator yang dipilih tidak valid.',

                'indikator.exists' =>
                    'Indikator yang dipilih tidak ditemukan.',
            ]
        );

        $indikator = IndikatorStandar::with(
            'isiStandar'
        )
            ->findOrFail(
                $request->integer('indikator')
            );

        abort_unless(
            $indikator->isiStandar
            && (int) $indikator
                ->isiStandar
                ->id_standar_mutu
                ===
                (int) $standarPeriode
                    ->id_standar_mutu,
            404,
            'Indikator tidak sesuai dengan standar mutu yang dipilih.'
        );

        $idUser = $user['id'];

        /*
        |--------------------------------------------------------------------------
        | CEK PENERAPAN YANG SUDAH ADA
        |--------------------------------------------------------------------------
        */

        $penerapan = PenerapanStandar::query()
            ->where(
                'id_standarmutu_periodeami',
                $standarPeriode->id
            )
            ->where(
                'id_indikator',
                $indikator->id
            )
            ->where(
                'id_user',
                $idUser
            )
            ->first();

        if ($penerapan) {
            return redirect()->route(
                'auditee.penerapan.edit',
                $penerapan->id
            );
        }

        return view(
            'auditee.penerapan.create',
            [
                'standar' => $standarPeriode,
                'indikator' => $indikator,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Menyimpan penerapan standar milik Auditee.
    | Penyimpanan ditolak apabila periode AMI sudah ditutup.
    |
    */

    public function store(
        Request $request
    ): RedirectResponse {
        $user = $this->currentUser();
        $idUser = $user['id'];

        $validated = $request->validate(
            [
                'id_standarmutu_periodeami' => [
                    'required',
                    'integer',
                    'exists:standarmutu_periodeami,id',
                ],

                'id_indikator' => [
                    'required',
                    'integer',
                    'exists:indikator_standar,id',

                    Rule::unique(
                        'penerapan_standar',
                        'id_indikator'
                    )->where(
                        fn ($query) => $query
                            ->where(
                                'id_standarmutu_periodeami',
                                $request->integer(
                                    'id_standarmutu_periodeami'
                                )
                            )
                            ->where(
                                'id_user',
                                $idUser
                            )
                    ),
                ],

                'deskripsi_hasil' => [
                    'required',
                    'string',
                ],

                'link_bukti' => [
                    'nullable',
                    'url',
                    'max:2048',
                ],
            ],
            [
                'id_standarmutu_periodeami.required' =>
                    'Standar mutu periode AMI tidak ditemukan.',

                'id_standarmutu_periodeami.integer' =>
                    'Standar mutu periode AMI tidak valid.',

                'id_standarmutu_periodeami.exists' =>
                    'Standar mutu periode AMI tidak ditemukan.',

                'id_indikator.required' =>
                    'Indikator wajib dipilih.',

                'id_indikator.integer' =>
                    'Indikator yang dipilih tidak valid.',

                'id_indikator.exists' =>
                    'Indikator yang dipilih tidak ditemukan.',

                'id_indikator.unique' =>
                    'Penerapan untuk indikator ini sudah pernah diisi.',

                'deskripsi_hasil.required' =>
                    'Deskripsi hasil penerapan wajib diisi.',

                'deskripsi_hasil.string' =>
                    'Deskripsi hasil penerapan harus berupa teks.',

                'link_bukti.url' =>
                    'Link bukti harus berupa URL yang valid.',

                'link_bukti.max' =>
                    'Link bukti maksimal 2048 karakter.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | AMBIL STANDAR PERIODE MILIK UNIT KERJA AUDITEE
        |--------------------------------------------------------------------------
        */

        $standarPeriode = StandarMutuPeriodeAmi::with(
            'periodeAmi'
        )
            ->where('status', 'aktif')
            ->whereHas(
                'periodeAmi',
                function ($query) use ($user) {
                    $query->where(
                        'id_unit_kerja',
                        $user['id_unit_kerja']
                    );
                }
            )
            ->findOrFail(
                $validated[
                    'id_standarmutu_periodeami'
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM STORE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed(
            $standarPeriode->periodeAmi
        );

        $indikator = IndikatorStandar::with(
            'isiStandar'
        )
            ->findOrFail(
                $validated['id_indikator']
            );

        abort_unless(
            $indikator->isiStandar
            && (int) $indikator
                ->isiStandar
                ->id_standar_mutu
                ===
                (int) $standarPeriode
                    ->id_standar_mutu,
            422,
            'Indikator tidak sesuai dengan standar mutu yang dipilih.'
        );

        PenerapanStandar::create([
            'id_standarmutu_periodeami' =>
                $standarPeriode->id,

            'id_indikator' =>
                $indikator->id,

            'deskripsi_hasil' =>
                trim(
                    $validated['deskripsi_hasil']
                ),

            'link_bukti' =>
                !empty($validated['link_bukti'])
                    ? trim($validated['link_bukti'])
                    : null,

            'id_user' =>
                $idUser,
        ]);

        return redirect()
            ->route(
                'auditee.standar.index',
                $standarPeriode->id_standar_mutu
            )
            ->with(
                'success',
                'Penerapan standar berhasil disimpan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    |
    | Form edit tidak dapat dibuka apabila periode AMI sudah ditutup.
    |
    */

    public function edit(
        int $id
    ): View {
        $data = $this->findOwnedPenerapan($id);

        $data->load([
            'standarmutuPeriode.standarMutu',
            'standarmutuPeriode.periodeAmi',
            'indikator.isiStandar',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed(
            $data
                ->standarmutuPeriode
                ?->periodeAmi
        );

        return view(
            'auditee.penerapan.edit',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Penerapan tidak dapat diperbarui apabila periode AMI sudah ditutup.
    |
    */

    public function update(
        Request $request,
        int $id
    ): RedirectResponse {
        $validated = $request->validate(
            [
                'deskripsi_hasil' => [
                    'required',
                    'string',
                ],

                'link_bukti' => [
                    'nullable',
                    'url',
                    'max:2048',
                ],
            ],
            [
                'deskripsi_hasil.required' =>
                    'Deskripsi hasil penerapan wajib diisi.',

                'deskripsi_hasil.string' =>
                    'Deskripsi hasil penerapan harus berupa teks.',

                'link_bukti.url' =>
                    'Link bukti harus berupa URL yang valid.',

                'link_bukti.max' =>
                    'Link bukti maksimal 2048 karakter.',
            ]
        );

        $data = $this->findOwnedPenerapan($id);

        $data->loadMissing([
            'standarmutuPeriode',
            'standarmutuPeriode.periodeAmi',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE SEBELUM UPDATE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed(
            $data
                ->standarmutuPeriode
                ?->periodeAmi
        );

        $data->update([
            'deskripsi_hasil' =>
                trim(
                    $validated['deskripsi_hasil']
                ),

            'link_bukti' =>
                !empty($validated['link_bukti'])
                    ? trim($validated['link_bukti'])
                    : null,
        ]);

        return redirect()
            ->route(
                'auditee.standar.index',
                $data
                    ->standarmutuPeriode
                    ->id_standar_mutu
            )
            ->with(
                'success',
                'Penerapan standar berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    |
    | Penerapan hanya boleh dihapus apabila:
    |
    | - periode AMI belum ditutup;
    | - belum mempunyai temuan audit;
    | - belum mempunyai skor atau penilaian Auditor.
    |
    */

    public function destroy(
        int $id
    ): RedirectResponse {
        $data = $this->findOwnedPenerapan($id);

        $data->loadMissing([
            'standarmutuPeriode',
            'standarmutuPeriode.periodeAmi',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE
        |--------------------------------------------------------------------------
        */

        $this->abortIfPeriodeClosed(
            $data
                ->standarmutuPeriode
                ?->periodeAmi
        );

        /*
        |--------------------------------------------------------------------------
        | CEK TEMUAN AUDIT
        |--------------------------------------------------------------------------
        |
        | Auditee tidak boleh menghapus penerapan yang sudah menjadi dasar
        | temuan Auditor.
        |
        */

        abort_if(
            $data->temuan()->exists(),
            403,
            'Penerapan sudah diaudit dan tidak dapat dihapus.'
        );

        /*
        |--------------------------------------------------------------------------
        | CEK SKOR ATAU PENILAIAN AUDITOR
        |--------------------------------------------------------------------------
        */

        abort_if(
            $this->penerapanSudahDinilai($data),
            403,
            'Penerapan sudah dinilai dan tidak dapat dihapus.'
        );

        $standarMutuId = $data
            ->standarmutuPeriode
            ->id_standar_mutu;

        $data->delete();

        return redirect()
            ->route(
                'auditee.standar.index',
                $standarMutuId
            )
            ->with(
                'success',
                'Penerapan standar berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CARI PENERAPAN MILIK AUDITEE
    |--------------------------------------------------------------------------
    |
    | Auditee hanya dapat membuka penerapan milik akunnya sendiri dan
    | penerapan tersebut harus berasal dari unit kerja Auditee.
    |
    */

    private function findOwnedPenerapan(
        int $id
    ): PenerapanStandar {
        $user = $this->currentUser();

        return PenerapanStandar::query()
            ->where(
                'id_user',
                $user['id']
            )
            ->whereHas(
                'standarmutuPeriode.periodeAmi',
                function ($query) use ($user) {
                    $query->where(
                        'id_unit_kerja',
                        $user['id_unit_kerja']
                    );
                }
            )
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | CEK PENERAPAN SUDAH DINILAI
    |--------------------------------------------------------------------------
    |
    | Pemeriksaan menyesuaikan beberapa kemungkinan lokasi penyimpanan
    | nilai skor yang sudah digunakan pada aplikasi.
    |
    */

    private function penerapanSudahDinilai(
        PenerapanStandar $data
    ): bool {
        /*
        |--------------------------------------------------------------------------
        | CEK MELALUI RELASI SKOR
        |--------------------------------------------------------------------------
        |
        | Bagian ini dijalankan hanya jika model PenerapanStandar memiliki
        | method relasi skor().
        |
        */

        if (method_exists($data, 'skor')) {
            try {
                if ($data->skor()->exists()) {
                    return true;
                }
            } catch (\Throwable $exception) {
                /*
                | Pemeriksaan dilanjutkan melalui kolom dan tabel database.
                */
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SKALA SKOR LANGSUNG PADA PENERAPAN_STANDAR
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasColumn(
                'penerapan_standar',
                'id_skala_skor'
            )
        ) {
            $idSkalaSkor = $data->getAttribute(
                'id_skala_skor'
            );

            if (
                $idSkalaSkor !== null
                && $idSkalaSkor !== ''
            ) {
                return true;
            }
        }

        if (
            Schema::hasColumn(
                'penerapan_standar',
                'skala_skor_id'
            )
        ) {
            $idSkalaSkor = $data->getAttribute(
                'skala_skor_id'
            );

            if (
                $idSkalaSkor !== null
                && $idSkalaSkor !== ''
            ) {
                return true;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | NILAI SKOR LANGSUNG PADA PENERAPAN_STANDAR
        |--------------------------------------------------------------------------
        |
        | Nilai 0 tetap dianggap sebagai nilai yang sudah diberikan.
        |
        */

        if (
            Schema::hasColumn(
                'penerapan_standar',
                'skor'
            )
        ) {
            $nilaiSkor = $data->getAttribute(
                'skor'
            );

            if (
                $nilaiSkor !== null
                && $nilaiSkor !== ''
            ) {
                return true;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TABEL PENILAIAN TERPISAH
        |--------------------------------------------------------------------------
        */

        $daftarTabelSkor = [
            'skor_penerapan_standar',
            'penilaian_penerapan_standar',
            'skor_penerapan',
        ];

        foreach ($daftarTabelSkor as $tabelSkor) {
            if (!Schema::hasTable($tabelSkor)) {
                continue;
            }

            if (
                !Schema::hasColumn(
                    $tabelSkor,
                    'id_penerapan_standar'
                )
            ) {
                continue;
            }

            $sudahDinilai = DB::table($tabelSkor)
                ->where(
                    'id_penerapan_standar',
                    $data->id
                )
                ->exists();

            if ($sudahDinilai) {
                return true;
            }
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | DATA USER LOGIN
    |--------------------------------------------------------------------------
    */

    private function currentUser(): array
    {
        $user = request()
            ->attributes
            ->get('auth_user');

        if (!$user) {
            $user = User::query()->find(
                session('user_id')
            );
        }

        abort_unless(
            $user,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $idUser = is_array($user)
            ? ($user['id'] ?? null)
            : ($user->id ?? null);

        $idUnitKerja = is_array($user)
            ? ($user['id_unit_kerja'] ?? null)
            : ($user->id_unit_kerja ?? null);

        $statusUser = is_array($user)
            ? ($user['status'] ?? null)
            : ($user->status ?? null);

        $statusUser = strtolower(
            trim((string) $statusUser)
        );

        abort_unless(
            $statusUser === 'aktif',
            403,
            'Akun tidak ditemukan atau sudah dinonaktifkan.'
        );

        abort_unless(
            $idUser && $idUnitKerja,
            403,
            'Data pengguna atau unit kerja tidak ditemukan.'
        );

        return [
            'id' =>
                (int) $idUser,

            'id_unit_kerja' =>
                (int) $idUnitKerja,
        ];
    }
}