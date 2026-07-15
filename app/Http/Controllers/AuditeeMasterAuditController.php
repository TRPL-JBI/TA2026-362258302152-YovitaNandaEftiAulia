<?php

namespace App\Http\Controllers;

use App\Models\JadwalAmi;
use App\Models\TemuanAmi;
use App\Models\TimAmi;

class AuditeeMasterAuditController extends Controller
{
    /**
     * Mengambil ID user yang sedang login.
     */
    private function getLoginUserId(): ?int
    {
        $user = session('user');

        if (is_array($user)) {
            return isset($user['id'])
                ? (int) $user['id']
                : null;
        }

        return isset($user->id)
            ? (int) $user->id
            : null;
    }

    /**
     * Mengambil ID unit kerja Auditee yang sedang login.
     */
    private function getLoginUnitId(): ?int
    {
        $user = session('user');

        if (is_array($user)) {
            return isset($user['id_unit_kerja'])
                ? (int) $user['id_unit_kerja']
                : null;
        }

        return isset($user->id_unit_kerja)
            ? (int) $user->id_unit_kerja
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | TEMUAN AUDIT
    |--------------------------------------------------------------------------
    */

    public function temuanIndex()
    {
        $idUnitKerja = $this->getLoginUnitId();

        abort_if(
            !$idUnitKerja,
            403,
            'Akun Auditee belum mempunyai unit kerja.'
        );

        $data = TemuanAmi::with([
            'pertanyaan',
            'pertanyaan.user',
            'pertanyaan.penerapan',
            'pertanyaan.penerapan.indikator',
            'pertanyaan.penerapan.user',
            'pertanyaan.penerapan.standarmutuPeriode',
            'pertanyaan.penerapan.standarmutuPeriode.standarMutu',
            'pertanyaan.penerapan.standarmutuPeriode.periodeAmi',
            'pertanyaan.penerapan.standarmutuPeriode.periodeAmi.unitKerja',
        ])
            ->whereHas(
                'pertanyaan.penerapan.standarmutuPeriode.periodeAmi',
                function ($query) use ($idUnitKerja) {
                    $query->where(
                        'id_unit_kerja',
                        $idUnitKerja
                    );
                }
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'auditee.master.temuan.index',
            compact('data')
        );
    }

    public function temuanShow($id)
    {
        $idUnitKerja = $this->getLoginUnitId();

        abort_if(
            !$idUnitKerja,
            403,
            'Akun Auditee belum mempunyai unit kerja.'
        );

        $temuan = TemuanAmi::with([
            'pertanyaan',
            'pertanyaan.user',
            'pertanyaan.penerapan',
            'pertanyaan.penerapan.indikator',
            'pertanyaan.penerapan.user',
            'pertanyaan.penerapan.standarmutuPeriode',
            'pertanyaan.penerapan.standarmutuPeriode.standarMutu',
            'pertanyaan.penerapan.standarmutuPeriode.periodeAmi',
            'pertanyaan.penerapan.standarmutuPeriode.periodeAmi.unitKerja',
            'tanggapan.user',
            'akarMasalah',
        ])
            ->whereHas(
                'pertanyaan.penerapan.standarmutuPeriode.periodeAmi',
                function ($query) use ($idUnitKerja) {
                    $query->where(
                        'id_unit_kerja',
                        $idUnitKerja
                    );
                }
            )
            ->findOrFail($id);

        return view(
            'auditee.master.temuan.show',
            compact('temuan')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TIM AUDIT
    |--------------------------------------------------------------------------
    */

    public function timIndex()
    {
        $idUnitKerja = $this->getLoginUnitId();

        abort_if(
            !$idUnitKerja,
            403,
            'Akun Auditee belum mempunyai unit kerja.'
        );

        $data = TimAmi::with([
            'user',
            'user.unit',
            'periode',
            'periode.standarMutu',
            'periode.unitKerja',
        ])
            ->whereHas(
                'periode',
                function ($query) use ($idUnitKerja) {
                    $query->where(
                        'id_unit_kerja',
                        $idUnitKerja
                    );
                }
            )
            ->orderByDesc('id_periode_ami')
            ->orderBy('role')
            ->get();

        return view(
            'auditee.master.tim.index',
            compact('data')
        );
    }

    public function timShow($id)
    {
        $idUnitKerja = $this->getLoginUnitId();

        abort_if(
            !$idUnitKerja,
            403,
            'Akun Auditee belum mempunyai unit kerja.'
        );

        $tim = TimAmi::with([
            'user',
            'user.unit',
            'periode',
            'periode.standarMutu',
            'periode.unitKerja',
        ])
            ->whereHas(
                'periode',
                function ($query) use ($idUnitKerja) {
                    $query->where(
                        'id_unit_kerja',
                        $idUnitKerja
                    );
                }
            )
            ->findOrFail($id);

        return view(
            'auditee.master.tim.show',
            compact('tim')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | JADWAL AUDIT
    |--------------------------------------------------------------------------
    */

    public function jadwalIndex()
    {
        $idUnitKerja = $this->getLoginUnitId();

        abort_if(
            !$idUnitKerja,
            403,
            'Akun Auditee belum mempunyai unit kerja.'
        );

        $data = JadwalAmi::with([
            'periode',
            'periode.standarMutu',
            'periode.unitKerja',
        ])
            ->whereHas(
                'periode',
                function ($query) use ($idUnitKerja) {
                    $query->where(
                        'id_unit_kerja',
                        $idUnitKerja
                    );
                }
            )
            ->orderByDesc('id_periode_ami')
            ->orderBy('id')
            ->get();

        return view(
            'auditee.master.jadwal.index',
            compact('data')
        );
    }

    public function jadwalShow($id)
    {
        $idUnitKerja = $this->getLoginUnitId();

        abort_if(
            !$idUnitKerja,
            403,
            'Akun Auditee belum mempunyai unit kerja.'
        );

        $jadwal = JadwalAmi::with([
            'periode',
            'periode.standarMutu',
            'periode.unitKerja',
        ])
            ->whereHas(
                'periode',
                function ($query) use ($idUnitKerja) {
                    $query->where(
                        'id_unit_kerja',
                        $idUnitKerja
                    );
                }
            )
            ->findOrFail($id);

        return view(
            'auditee.master.jadwal.show',
            compact('jadwal')
        );
    }
}