<?php

namespace App\Http\Controllers;

use App\Models\JadwalAmi;
use App\Models\TemuanAmi;
use App\Models\TimAmi;

class AuditeeMasterAuditController extends Controller
{
    /**
     * Menampilkan daftar temuan audit Auditee.
     *
     * Method ini dipakai oleh route:
     * AuditeeMasterAuditController::temuanIndex
     */
    public function temuanIndex()
    {
        $user = $this->currentUser();

        $temuan = TemuanAmi::with([
            'penerapanStandar',
            'penerapanStandar.indikator',
            'penerapanStandar.user',
            'penerapanStandar.standarmutuPeriode',
            'penerapanStandar.standarmutuPeriode.standarMutu',
            'penerapanStandar.standarmutuPeriode.periodeAmi',
            'penerapanStandar.standarmutuPeriode.periodeAmi.unitKerja',
            'tanggapan',
        ])
            ->whereHas(
                'penerapanStandar',
                function ($query) use ($user) {
                    $query->where(
                        'id_user',
                        $user['id']
                    );
                }
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'auditee.temuan.index',
            compact('temuan')
        );
    }

    /**
     * Alias agar route lama yang mungkin memakai method temuan()
     * tetap dapat digunakan.
     */
    public function temuan()
    {
        return $this->temuanIndex();
    }

    /**
     * Menampilkan daftar tim audit Auditee.
     */
    public function timIndex()
    {
        $user = $this->currentUser();

        $tim = TimAmi::with([
            'user',
            'periodeAmi',
            'periodeAmi.unitKerja',
            'periodeAmi.standarMutu',
        ])
            ->whereHas(
                'periodeAmi',
                function ($query) use ($user) {
                    $query->where(
                        'id_unit_kerja',
                        $user['id_unit_kerja']
                    );
                }
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'auditee.audit.tim.index',
            compact('tim')
        );
    }

    /**
     * Alias agar route lama yang mungkin memakai method tim()
     * tetap dapat digunakan.
     */
    public function tim()
    {
        return $this->timIndex();
    }

    /**
     * Menampilkan daftar jadwal audit Auditee.
     */
    public function jadwalIndex()
    {
        $user = $this->currentUser();

        $jadwal = JadwalAmi::with([
            'periodeAmi',
            'periodeAmi.unitKerja',
            'periodeAmi.standarMutu',
        ])
            ->whereHas(
                'periodeAmi',
                function ($query) use ($user) {
                    $query->where(
                        'id_unit_kerja',
                        $user['id_unit_kerja']
                    );
                }
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'auditee.audit.jadwal.index',
            compact('jadwal')
        );
    }

    /**
     * Alias agar route lama yang mungkin memakai method jadwal()
     * tetap dapat digunakan.
     */
    public function jadwal()
    {
        return $this->jadwalIndex();
    }

    /**
     * Mengambil data pengguna yang sedang login.
     */
    private function currentUser(): array
    {
        $sessionUser = session('user');

        abort_unless(
            $sessionUser,
            401,
            'Sesi pengguna tidak ditemukan. Silakan login kembali.'
        );

        $idUser = is_array($sessionUser)
            ? ($sessionUser['id'] ?? null)
            : ($sessionUser->id ?? null);

        $idUnitKerja = is_array($sessionUser)
            ? ($sessionUser['id_unit_kerja'] ?? null)
            : ($sessionUser->id_unit_kerja ?? null);

        abort_unless(
            $idUser,
            401,
            'ID pengguna tidak ditemukan pada sesi.'
        );

        abort_unless(
            $idUnitKerja,
            403,
            'Unit kerja pengguna belum ditentukan.'
        );

        return [
            'id' => (int) $idUser,
            'id_unit_kerja' => (int) $idUnitKerja,
        ];
    }
}