<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\PenerapanStandar;
use App\Models\StandarMutu;
use App\Models\UnitKerja;
use App\Models\User;
use App\Traits\ChecksPeriodeAmiStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodeAmiController extends Controller
{
    use ChecksPeriodeAmiStatus;

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'unitKerjas',
            'user',
            'tim.user',
            'jadwal',
        ])
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        return view(
            'periode.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(Request $request)
    {
        $standarMutu = StandarMutu::query()
            ->orderBy('id')
            ->get();

        $unitKerja = UnitKerja::query()
            ->orderBy('id')
            ->get();

        $userLogin = $this->getLoggedInUser($request);

        return view(
            'periode.create',
            compact(
                'standarMutu',
                'unitKerja',
                'userLogin'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    |
    | Periode yang baru dibuat selalu memiliki status draft.
    |
    */

    public function store(Request $request)
    {
        $validated = $this->validatePeriode($request);

        $userLogin = $this->getLoggedInUser($request);

        $statusUser = strtolower(
            trim((string) $userLogin->status)
        );

        if ($statusUser !== 'aktif') {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Akun tidak ditemukan atau sudah dinonaktifkan.'
                );
        }

        DB::transaction(function () use (
            $validated,
            $userLogin
        ) {
            $unitKerjaDipilih = array_values(
                $validated['unit_kerja']
            );

            $periode = PeriodeAmi::create([
                'tahun' =>
                    $validated['tahun'],

                'id_standar_mutu' =>
                    $validated['id_standar_mutu'],

                /*
                |--------------------------------------------------------------------------
                | UNIT KERJA UTAMA
                |--------------------------------------------------------------------------
                |
                | Unit pertama tetap disimpan pada kolom lama
                | untuk menjaga kompatibilitas fitur sebelumnya.
                |
                */

                'id_unit_kerja' =>
                    $unitKerjaDipilih[0],

                'id_user' =>
                    $userLogin->id,

                'tujuan_audit' =>
                    $validated['tujuan_audit'],

                'lingkup_audit' =>
                    $validated['lingkup_audit'],

                'waktu_audit' =>
                    $this->formatWaktuAudit(
                        $validated['waktu_mulai'],
                        $validated['waktu_selesai']
                    ),

                'tanggal_buka_ami' =>
                    $validated['tanggal_buka_ami'],

                'tanggal_tutup_ami' =>
                    $validated['tanggal_tutup_ami'],

                /*
                |--------------------------------------------------------------------------
                | STATUS AWAL
                |--------------------------------------------------------------------------
                */

                'status' => 'draft',
            ]);

            /*
            |--------------------------------------------------------------------------
            | SIMPAN SELURUH UNIT KERJA
            |--------------------------------------------------------------------------
            */

            $periode
                ->unitKerjas()
                ->sync($unitKerjaDipilih);
        });

        return redirect()
            ->route('periode-ami.index')
            ->with(
                'success',
                'Periode AMI berhasil ditambahkan dengan status Draft.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'unitKerjas',
            'user',
            'tim.user',
            'jadwal',
            'kesimpulanAudit',
        ])->findOrFail($id);

        return view(
            'periode.detail',
            compact('periode')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        Request $request,
        $id
    ) {
        $data = PeriodeAmi::with([
            'unitKerjas',
        ])->findOrFail($id);

        $this->abortIfPeriodeClosed($data);

        $standarMutu = StandarMutu::query()
            ->orderBy('id')
            ->get();

        $unitKerja = UnitKerja::query()
            ->orderBy('id')
            ->get();

        $userLogin = $this->getLoggedInUser($request);

        /*
        |--------------------------------------------------------------------------
        | UNIT KERJA YANG SUDAH DIPILIH
        |--------------------------------------------------------------------------
        */

        $unitKerjaTerpilih = $data
            ->unitKerjas
            ->pluck('id')
            ->map(
                fn ($idUnit) => (string) $idUnit
            )
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | KOMPATIBILITAS DATA LAMA
        |--------------------------------------------------------------------------
        */

        if (
            count($unitKerjaTerpilih) === 0
            && $data->id_unit_kerja
        ) {
            $unitKerjaTerpilih = [
                (string) $data->id_unit_kerja,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | PECAH WAKTU AUDIT
        |--------------------------------------------------------------------------
        */

        $waktu = $this->parseWaktuAudit(
            $data->waktu_audit
        );

        $waktuMulai =
            $waktu['mulai'];

        $waktuSelesai =
            $waktu['selesai'];

        return view(
            'periode.edit',
            compact(
                'data',
                'standarMutu',
                'unitKerja',
                'unitKerjaTerpilih',
                'userLogin',
                'waktuMulai',
                'waktuSelesai'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    |
    | Status tidak dapat diubah melalui halaman edit.
    |
    */

    public function update(
        Request $request,
        $id
    ) {
        $data = PeriodeAmi::findOrFail($id);

        $this->abortIfPeriodeClosed($data);

        $validated = $this->validatePeriode($request);

        DB::transaction(function () use (
            $data,
            $validated
        ) {
            $unitKerjaDipilih = array_values(
                $validated['unit_kerja']
            );

            $data->update([
                'tahun' =>
                    $validated['tahun'],

                'id_standar_mutu' =>
                    $validated['id_standar_mutu'],

                'id_unit_kerja' =>
                    $unitKerjaDipilih[0],

                'tujuan_audit' =>
                    $validated['tujuan_audit'],

                'lingkup_audit' =>
                    $validated['lingkup_audit'],

                'waktu_audit' =>
                    $this->formatWaktuAudit(
                        $validated['waktu_mulai'],
                        $validated['waktu_selesai']
                    ),

                'tanggal_buka_ami' =>
                    $validated['tanggal_buka_ami'],

                'tanggal_tutup_ami' =>
                    $validated['tanggal_tutup_ami'],
            ]);

            $data
                ->unitKerjas()
                ->sync($unitKerjaDipilih);
        });

        return redirect()
            ->route('periode-ami.index')
            ->with(
                'success',
                'Periode AMI berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | MULAI PERIODE AMI
    |--------------------------------------------------------------------------
    |
    | Alur:
    |
    | draft -> berjalan
    |
    | Jika syarat belum lengkap, halaman tidak error.
    | Sistem kembali ke halaman sebelumnya dengan pesan.
    |
    */

    public function start($id)
    {
        $periode = PeriodeAmi::with([
            'tim.user',
            'jadwal',
        ])->findOrFail($id);

        $status = strtolower(
            trim((string) $periode->status)
        );

        /*
        |--------------------------------------------------------------------------
        | STATUS HARUS DRAFT
        |--------------------------------------------------------------------------
        */

        if ($status !== 'draft') {
            return back()->with(
                'error',
                'Periode AMI hanya dapat dimulai jika status masih Draft.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TIM AMI HARUS ADA
        |--------------------------------------------------------------------------
        */

        if (!$periode->tim()->exists()) {
            return back()->with(
                'error',
                'Periode AMI belum dapat dimulai karena Tim AMI belum dibuat.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MINIMAL ADA KETUA AUDITOR / AUDITOR
        |--------------------------------------------------------------------------
        */

        $adaAuditor = $periode
            ->tim()
            ->where(function ($query) {
                $query
                    ->whereRaw(
                        'LOWER(TRIM(role)) = ?',
                        ['ketua auditor']
                    )
                    ->orWhereRaw(
                        'LOWER(TRIM(role)) = ?',
                        ['auditor']
                    );
            })
            ->exists();

        if (!$adaAuditor) {
            return back()->with(
                'error',
                'Periode AMI belum dapat dimulai. Tambahkan minimal satu Ketua Auditor atau Auditor terlebih dahulu.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MINIMAL ADA AUDITEE
        |--------------------------------------------------------------------------
        */

        $adaAuditee = $periode
            ->tim()
            ->whereRaw(
                'LOWER(TRIM(role)) = ?',
                ['auditee']
            )
            ->exists();

        if (!$adaAuditee) {
            return back()->with(
                'error',
                'Periode AMI belum dapat dimulai. Tambahkan minimal satu Auditee terlebih dahulu.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | JADWAL AMI HARUS ADA
        |--------------------------------------------------------------------------
        */

        if (!$periode->jadwal()->exists()) {
            return back()->with(
                'error',
                'Periode AMI belum dapat dimulai karena Jadwal AMI belum dibuat.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UBAH STATUS MENJADI BERJALAN
        |--------------------------------------------------------------------------
        */

        $periode->update([
            'status' => 'berjalan',
        ]);

        return redirect()
            ->route(
                'periode-ami.show',
                $periode->id
            )
            ->with(
                'success',
                'Periode AMI berhasil dimulai dan status berubah menjadi Berjalan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TUTUP PERIODE AMI
    |--------------------------------------------------------------------------
    |
    | Alur:
    |
    | berjalan -> ditutup
    |
    | Jika proses belum lengkap, tampilkan pesan biasa
    | dan jangan menampilkan halaman exception 422.
    |
    */

    public function close($id)
    {
        $periode = PeriodeAmi::with([
            'tim.user',
            'jadwal',
            'kesimpulanAudit',
        ])->findOrFail($id);

        $status = strtolower(
            trim((string) $periode->status)
        );

        /*
        |--------------------------------------------------------------------------
        | STATUS HARUS BERJALAN
        |--------------------------------------------------------------------------
        */

        if ($status !== 'berjalan') {
            return back()->with(
                'error',
                'Periode AMI hanya dapat ditutup jika status sedang Berjalan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TIM AMI HARUS ADA
        |--------------------------------------------------------------------------
        */

        if (!$periode->tim()->exists()) {
            return back()->with(
                'error',
                'Periode AMI belum dapat ditutup karena Tim AMI belum tersedia.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AUDITOR HARUS ADA
        |--------------------------------------------------------------------------
        */

        $adaAuditor = $periode
            ->tim()
            ->where(function ($query) {
                $query
                    ->whereRaw(
                        'LOWER(TRIM(role)) = ?',
                        ['ketua auditor']
                    )
                    ->orWhereRaw(
                        'LOWER(TRIM(role)) = ?',
                        ['auditor']
                    );
            })
            ->exists();

        if (!$adaAuditor) {
            return back()->with(
                'error',
                'Periode AMI belum dapat ditutup karena belum memiliki Ketua Auditor atau Auditor.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | AUDITEE HARUS ADA
        |--------------------------------------------------------------------------
        */

        $adaAuditee = $periode
            ->tim()
            ->whereRaw(
                'LOWER(TRIM(role)) = ?',
                ['auditee']
            )
            ->exists();

        if (!$adaAuditee) {
            return back()->with(
                'error',
                'Periode AMI belum dapat ditutup karena belum memiliki Auditee.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | JADWAL HARUS ADA
        |--------------------------------------------------------------------------
        */

        if (!$periode->jadwal()->exists()) {
            return back()->with(
                'error',
                'Periode AMI belum dapat ditutup karena Jadwal AMI belum tersedia.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | QUERY PENERAPAN STANDAR
        |--------------------------------------------------------------------------
        */

        $penerapanQuery = PenerapanStandar::query()
            ->whereHas(
                'standarmutuPeriode',
                function ($query) use ($periode) {
                    $query->where(
                        'id_periode_ami',
                        $periode->id
                    );
                }
            );

        /*
        |--------------------------------------------------------------------------
        | AUDITEE HARUS SUDAH MENGISI PENERAPAN
        |--------------------------------------------------------------------------
        */

        if (!(clone $penerapanQuery)->exists()) {
            return back()->with(
                'error',
                'Periode AMI belum dapat ditutup karena Auditee belum mengisi penerapan standar.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SEMUA PENERAPAN HARUS SUDAH DINILAI
        |--------------------------------------------------------------------------
        */

        $adaPenerapanBelumDinilai =
            (clone $penerapanQuery)
                ->whereDoesntHave('skor')
                ->exists();

        if ($adaPenerapanBelumDinilai) {
            return back()->with(
                'error',
                'Periode AMI belum dapat ditutup karena masih terdapat penerapan standar yang belum dinilai oleh Auditor.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SEMUA TEMUAN HARUS SUDAH CLOSED
        |--------------------------------------------------------------------------
        */

        $adaTemuanBelumClosed =
            (clone $penerapanQuery)
                ->whereHas(
                    'temuan',
                    function ($query) {
                        $query->where(
                            function ($statusQuery) {
                                $statusQuery
                                    ->whereNull(
                                        'status_temuan'
                                    )
                                    ->orWhereRaw(
                                        'LOWER(TRIM(status_temuan)) != ?',
                                        ['closed']
                                    );
                            }
                        );
                    }
                )
                ->exists();

        if ($adaTemuanBelumClosed) {
            return back()->with(
                'error',
                'Periode AMI belum dapat ditutup karena masih terdapat temuan yang belum diselesaikan atau belum ditutup.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | KESIMPULAN AUDIT HARUS ADA
        |--------------------------------------------------------------------------
        */

        if (!$periode->kesimpulanAudit()->exists()) {
            return back()->with(
                'error',
                'Periode AMI belum dapat ditutup karena Kesimpulan Audit belum dibuat.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UBAH STATUS MENJADI DITUTUP
        |--------------------------------------------------------------------------
        */

        $periode->update([
            'status' => 'ditutup',
        ]);

        return redirect()
            ->route(
                'periode-ami.show',
                $periode->id
            )
            ->with(
                'success',
                'Periode AMI berhasil ditutup. Seluruh data audit pada periode ini sekarang dikunci.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE PAGE
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'unitKerjas',
            'user',
        ])->findOrFail($id);

        $this->abortIfPeriodeClosed($data);

        return view(
            'periode.delete',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $data = PeriodeAmi::findOrFail($id);

        $this->abortIfPeriodeClosed($data);

        $status = strtolower(
            trim((string) $data->status)
        );

        /*
        |--------------------------------------------------------------------------
        | PERIODE BERJALAN TIDAK BOLEH DIHAPUS
        |--------------------------------------------------------------------------
        */

        if ($status === 'berjalan') {
            return back()->with(
                'error',
                'Periode AMI yang sedang berjalan tidak dapat dihapus.'
            );
        }

        DB::transaction(function () use ($data) {
            $data
                ->unitKerjas()
                ->detach();

            $data->delete();
        });

        return redirect()
            ->route('periode-ami.index')
            ->with(
                'success',
                'Periode AMI berhasil dihapus.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI CREATE / UPDATE
    |--------------------------------------------------------------------------
    |
    | Status sengaja tidak divalidasi dari form karena perubahan
    | status dilakukan melalui aksi Mulai AMI dan Tutup AMI.
    |
    */

    private function validatePeriode(
        Request $request
    ): array {
        return $request->validate(
            [
                'tahun' => [
                    'required',
                    'integer',
                    'min:2025',
                    'max:2035',
                ],

                'id_standar_mutu' => [
                    'required',
                    'exists:standar_mutu,id',
                ],

                'unit_kerja' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'unit_kerja.*' => [
                    'required',
                    'distinct',
                    'exists:unit_kerja,id',
                ],

                'tujuan_audit' => [
                    'required',
                    'string',
                    'max:2000',
                ],

                'lingkup_audit' => [
                    'required',
                    'string',
                    'max:2000',
                ],

                'waktu_mulai' => [
                    'required',
                    'date_format:H:i',
                ],

                'waktu_selesai' => [
                    'required',
                    'date_format:H:i',
                    'after:waktu_mulai',
                ],

                'tanggal_buka_ami' => [
                    'required',
                    'date',
                ],

                'tanggal_tutup_ami' => [
                    'required',
                    'date',
                    'after_or_equal:tanggal_buka_ami',
                ],
            ],
            [
                'tahun.required' =>
                    'Tahun wajib dipilih.',

                'tahun.integer' =>
                    'Tahun tidak valid.',

                'id_standar_mutu.required' =>
                    'Standar mutu wajib dipilih.',

                'id_standar_mutu.exists' =>
                    'Standar mutu tidak ditemukan.',

                'unit_kerja.required' =>
                    'Pilih minimal satu unit kerja.',

                'unit_kerja.array' =>
                    'Pilihan unit kerja tidak valid.',

                'unit_kerja.min' =>
                    'Pilih minimal satu unit kerja.',

                'unit_kerja.*.distinct' =>
                    'Unit kerja tidak boleh dipilih dua kali.',

                'unit_kerja.*.exists' =>
                    'Salah satu unit kerja tidak ditemukan.',

                'tujuan_audit.required' =>
                    'Tujuan audit wajib diisi.',

                'lingkup_audit.required' =>
                    'Lingkup audit wajib diisi.',

                'waktu_mulai.required' =>
                    'Waktu mulai wajib diisi.',

                'waktu_mulai.date_format' =>
                    'Format waktu mulai tidak valid.',

                'waktu_selesai.required' =>
                    'Waktu selesai wajib diisi.',

                'waktu_selesai.date_format' =>
                    'Format waktu selesai tidak valid.',

                'waktu_selesai.after' =>
                    'Waktu selesai harus lebih akhir daripada waktu mulai.',

                'tanggal_buka_ami.required' =>
                    'Tanggal buka audit wajib diisi.',

                'tanggal_buka_ami.date' =>
                    'Tanggal buka audit tidak valid.',

                'tanggal_tutup_ami.required' =>
                    'Tanggal tutup audit wajib diisi.',

                'tanggal_tutup_ami.date' =>
                    'Tanggal tutup audit tidak valid.',

                'tanggal_tutup_ami.after_or_equal' =>
                    'Tanggal tutup tidak boleh sebelum tanggal buka.',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AMBIL USER LOGIN
    |--------------------------------------------------------------------------
    */

    private function getLoggedInUser(
        Request $request
    ): User {
        $user = $request
            ->attributes
            ->get('auth_user');

        if (!$user instanceof User) {
            $user = User::query()->find(
                session('user_id')
            );
        }

        abort_unless(
            $user,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        return $user;
    }

    /*
    |--------------------------------------------------------------------------
    | FORMAT WAKTU AUDIT
    |--------------------------------------------------------------------------
    */

    private function formatWaktuAudit(
        string $waktuMulai,
        string $waktuSelesai
    ): string {
        $mulai = str_replace(
            ':',
            '.',
            $waktuMulai
        );

        $selesai = str_replace(
            ':',
            '.',
            $waktuSelesai
        );

        return $mulai
            . ' - '
            . $selesai
            . ' WIB';
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE WAKTU AUDIT
    |--------------------------------------------------------------------------
    */

    private function parseWaktuAudit(
        ?string $waktuAudit
    ): array {
        $hasil = [
            'mulai' => '08:00',
            'selesai' => '15:00',
        ];

        if (!$waktuAudit) {
            return $hasil;
        }

        preg_match(
            '/(\d{1,2})[.:](\d{2})\s*-\s*(\d{1,2})[.:](\d{2})/',
            $waktuAudit,
            $cocok
        );

        if (count($cocok) >= 5) {
            $hasil['mulai'] =
                str_pad(
                    $cocok[1],
                    2,
                    '0',
                    STR_PAD_LEFT
                )
                . ':'
                . $cocok[2];

            $hasil['selesai'] =
                str_pad(
                    $cocok[3],
                    2,
                    '0',
                    STR_PAD_LEFT
                )
                . ':'
                . $cocok[4];
        }

        return $hasil;
    }
}