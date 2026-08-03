<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\StandarMutu;
use App\Models\UnitKerja;
use App\Models\User;
use App\Traits\ChecksPeriodeAmiStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodeAmiController extends Controller
{
    use ChecksPeriodeAmiStatus;

    // =========================
    // INDEX
    // =========================
    public function index()
    {
        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'unitKerjas',
            'user',
        ])
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        return view(
            'periode.index',
            compact('data')
        );
    }

    // =========================
    // CREATE
    // =========================
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

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $validated = $this->validatePeriode($request);

        $userLogin = $this->getLoggedInUser($request);

        $statusUser = strtolower(
            trim((string) $userLogin->status)
        );

        abort_unless(
            $statusUser === 'aktif',
            403,
            'Akun tidak ditemukan atau sudah dinonaktifkan.'
        );

        DB::transaction(function () use (
            $validated,
            $userLogin
        ) {
            $unitKerjaDipilih = array_values(
                $validated['unit_kerja']
            );

            $periode = new PeriodeAmi([
                'tahun' =>
                    $validated['tahun'],

                'id_standar_mutu' =>
                    $validated['id_standar_mutu'],

                /*
                | Unit pertama tetap disimpan pada kolom lama.
                */
                'id_unit_kerja' =>
                    $unitKerjaDipilih[0],

                'id_user' =>
                    $userLogin->id,

                'tujuan_audit' =>
                    $validated['tujuan_audit'],

                'lingkup_audit' =>
                    $validated['lingkup_audit'],

                /*
                | Dua input jam digabung ke satu kolom waktu_audit.
                */
                'waktu_audit' =>
                    $this->formatWaktuAudit(
                        $validated['waktu_mulai'],
                        $validated['waktu_selesai']
                    ),

                'tanggal_buka_ami' =>
                    $validated['tanggal_buka_ami'],

                'tanggal_tutup_ami' =>
                    $validated['tanggal_tutup_ami'],

                'status' =>
                    $validated['status'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | PERIODE BARU TIDAK BOLEH LANGSUNG DITUTUP
            |--------------------------------------------------------------------------
            */

            $this->abortIfPeriodeClosed($periode);

            $periode->save();

            /*
            |--------------------------------------------------------------------------
            | SIMPAN SEMUA UNIT KERJA
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
                'Periode AMI berhasil ditambahkan.'
            );
    }

    // =========================
    // DETAIL
    // =========================
    public function show($id)
    {
        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'unitKerjas',
            'user',
        ])->findOrFail($id);

        return view(
            'periode.detail',
            compact('periode')
        );
    }

    // =========================
    // EDIT
    // =========================
    public function edit(Request $request, $id)
    {
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
        | Digunakan untuk data lama yang belum masuk ke tabel penghubung.
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
        | PECAH WAKTU AUDIT LAMA
        |--------------------------------------------------------------------------
        |
        | Contoh nilai database:
        | 08.00 - 15.00 WIB
        |
        */

        $waktu = $this->parseWaktuAudit(
            $data->waktu_audit
        );

        $waktuMulai = $waktu['mulai'];
        $waktuSelesai = $waktu['selesai'];

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

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, $id)
    {
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

                /*
                | Unit pertama tetap disimpan di kolom lama.
                */
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

                'status' =>
                    $validated['status'],
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

    // =========================
    // DELETE PAGE
    // =========================
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

    // =========================
    // DESTROY
    // =========================
    public function destroy($id)
    {
        $data = PeriodeAmi::findOrFail($id);

        $this->abortIfPeriodeClosed($data);

        DB::transaction(function () use ($data) {
            $data->unitKerjas()->detach();
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
    | VALIDASI
    |--------------------------------------------------------------------------
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

                'status' => [
                    'required',
                    'in:draft,berjalan,ditutup',
                ],
            ],
            [
                'tahun.required' =>
                    'Tahun wajib dipilih.',

                'id_standar_mutu.required' =>
                    'Standar mutu wajib dipilih.',

                'unit_kerja.required' =>
                    'Pilih minimal satu unit kerja.',

                'unit_kerja.array' =>
                    'Pilihan unit kerja tidak valid.',

                'unit_kerja.min' =>
                    'Pilih minimal satu unit kerja.',

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

                'tanggal_tutup_ami.required' =>
                    'Tanggal tutup audit wajib diisi.',

                'tanggal_tutup_ami.after_or_equal' =>
                    'Tanggal tutup tidak boleh sebelum tanggal buka.',

                'status.required' =>
                    'Status wajib dipilih.',
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
    | FORMAT WAKTU UNTUK DATABASE
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
    | PECAH WAKTU DARI DATABASE
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