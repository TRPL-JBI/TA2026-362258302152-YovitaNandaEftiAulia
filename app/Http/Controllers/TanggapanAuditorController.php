<?php

namespace App\Http\Controllers;

use App\Models\TanggapanAuditee;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TanggapanAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    |
    | Menampilkan seluruh tanggapan yang berasal dari periode AMI
    | tempat auditor login ditugaskan.
    |
    */

    public function index(): View
    {
        $auditorId = $this->getAuditorId();

        $temuanIds = $this->getTemuanIdsAuditor(
            $auditorId
        );

        $data = TanggapanAuditee::query()
            ->with([
                'temuan',
                'user',
            ])
            ->whereIn(
                'id_temuan_ami',
                $temuanIds
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.tanggapan.index',
            compact('data')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    |
    | Menampilkan detail satu tanggapan Auditee.
    | Auditor hanya dapat membuka tanggapan dari periode penugasannya.
    |
    */

    public function show($id): View
    {
        $auditorId = $this->getAuditorId();

        $temuanIds = $this->getTemuanIdsAuditor(
            $auditorId
        );

        $tanggapan = TanggapanAuditee::query()
            ->with([
                'temuan',
                'temuan.penerapan',
                'temuan.penerapan.indikator',
                'user',
            ])
            ->whereIn(
                'id_temuan_ami',
                $temuanIds
            )
            ->findOrFail($id);

        return view(
            'auditor.tanggapan.show',
            compact('tanggapan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL ID TEMUAN SESUAI PENUGASAN AUDITOR
    |--------------------------------------------------------------------------
    |
    | Alur tabel:
    |
    | temuan_ami
    |     -> penerapan_standar
    |     -> standarmutu_periodeami
    |     -> tim_ami
    |
    */

    private function getTemuanIdsAuditor(
        int $auditorId
    ): Collection {
        return DB::table('temuan_ami as temuan')
            ->join(
                'penerapan_standar as penerapan',
                'penerapan.id',
                '=',
                'temuan.id_penerapan_standar'
            )
            ->join(
                'standarmutu_periodeami as standar_periode',
                'standar_periode.id',
                '=',
                'penerapan.id_standarmutu_periodeami'
            )
            ->join(
                'tim_ami as tim',
                'tim.id_periode_ami',
                '=',
                'standar_periode.id_periode_ami'
            )
            ->where(
                'tim.id_user',
                $auditorId
            )
            ->select('temuan.id')
            ->distinct()
            ->pluck('temuan.id');
    }

    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL ID AUDITOR LOGIN
    |--------------------------------------------------------------------------
    */

    private function getAuditorId(): int
    {
        $auditorId = session('user_id');

        /*
         * Cadangan apabila session menyimpan data user
         * dalam bentuk object atau array.
         */

        if (!$auditorId) {
            $sessionUser = session('user');

            if (is_array($sessionUser)) {
                $auditorId =
                    $sessionUser['id']
                    ?? null;
            }

            if (is_object($sessionUser)) {
                $auditorId =
                    $sessionUser->id
                    ?? null;
            }
        }

        /*
         * Cadangan dari request attribute middleware.
         */

        if (!$auditorId) {
            $requestUser = request()
                ->attributes
                ->get('auth_user');

            if (is_array($requestUser)) {
                $auditorId =
                    $requestUser['id']
                    ?? null;
            }

            if (is_object($requestUser)) {
                $auditorId =
                    $requestUser->id
                    ?? null;
            }
        }

        abort_if(
            !$auditorId,
            401,
            'Sesi auditor tidak ditemukan. Silakan login kembali.'
        );

        $auditor = User::query()
            ->find($auditorId);

        abort_unless(
            $auditor
            && strtolower(
                trim(
                    (string) $auditor->status
                )
            ) === 'aktif',
            403,
            'Akun auditor tidak ditemukan atau sudah dinonaktifkan.'
        );

        return (int) $auditorId;
    }
}