<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\PenerapanStandar;

class PeriodeAuditorController extends Controller
{
    /**
     * Mengambil ID auditor dari session.
     */
    private function getAuditorId(): int
    {
        $auditorId = session('user_id');

        abort_if(
            empty($auditorId),
            403,
            'Sesi auditor tidak ditemukan. Silakan login kembali.'
        );

        return (int) $auditorId;
    }

    /**
     * Menampilkan daftar periode AMI
     * yang menjadi penugasan auditor.
     */
    public function index()
    {
        $auditorId = $this->getAuditorId();

        $data = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user',
            'tim.user',
        ])
            ->whereHas('tim', function ($query) use ($auditorId) {
                $query->where('id_user', $auditorId);
            })
            ->orderByDesc('tahun')
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.periode.index',
            compact('data')
        );
    }

    /**
     * Menampilkan detail periode AMI
     * beserta daftar penerapan standar.
     */
    public function detail($id)
    {
        $auditorId = $this->getAuditorId();

        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user',
            'tim.user',
        ])
            ->whereHas('tim', function ($query) use ($auditorId) {
                $query->where('id_user', $auditorId);
            })
            ->findOrFail($id);

        $data = PenerapanStandar::with([
            'user',
            'standarmutuPeriode.standarMutu',
        ])
            ->whereHas(
                'standarmutuPeriode',
                function ($query) use ($id) {
                    $query->where(
                        'id_periode_ami',
                        $id
                    );
                }
            )
            ->whereHas(
                'standarmutuPeriode.periodeAmi.tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->orderByDesc('id')
            ->get();

        return view(
            'auditor.periode.show',
            compact(
                'periode',
                'data'
            )
        );
    }

    /**
     * Menampilkan detail satu penerapan standar.
     */
    public function show($id, $penerapan)
    {
        $auditorId = $this->getAuditorId();

        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user',
            'tim.user',
        ])
            ->whereHas('tim', function ($query) use ($auditorId) {
                $query->where('id_user', $auditorId);
            })
            ->findOrFail($id);

        $data = PenerapanStandar::with([
            'user',
            'standarmutuPeriode.standarMutu',
        ])
            ->whereHas(
                'standarmutuPeriode',
                function ($query) use ($id) {
                    $query->where(
                        'id_periode_ami',
                        $id
                    );
                }
            )
            ->whereHas(
                'standarmutuPeriode.periodeAmi.tim',
                function ($query) use ($auditorId) {
                    $query->where(
                        'id_user',
                        $auditorId
                    );
                }
            )
            ->findOrFail($penerapan);

        return view(
            'auditor.periode.show',
            compact(
                'periode',
                'data'
            )
        );
    }
}