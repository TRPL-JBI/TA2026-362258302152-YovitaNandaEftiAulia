<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAmi;
use App\Models\PenerapanStandar;

class PenerapanStandarController extends Controller
{
    /**
     * Menampilkan seluruh penerapan standar dalam satu periode AMI.
     *
     * Halaman ini hanya bersifat read-only.
     */
    public function index($id)
    {
        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user',
            'standarMutuPeriode',
            'standarMutuPeriode.standarMutu',
        ])->findOrFail($id);

        $data = PenerapanStandar::with([
            'user',
            'indikator',

            'standarMutuPeriodeAmi',
            'standarMutuPeriodeAmi.standarMutu',
            'standarMutuPeriodeAmi.periodeAmi',

            'temuan',
        ])
            ->whereHas(
                'standarMutuPeriodeAmi',
                function ($query) use ($id) {
                    $query->where(
                        'id_periode_ami',
                        $id
                    );
                }
            )
            ->orderBy('id_indikator')
            ->orderBy('id')
            ->get();

        $jumlahPenerapan = $data->count();

        $jumlahBukti = $data
            ->filter(function ($item) {
                return filled($item->link_bukti);
            })
            ->count();

        $jumlahAuditee = $data
            ->pluck('id_user')
            ->filter()
            ->unique()
            ->count();

        $jumlahTemuan = $data
            ->sum(function ($item) {
                return $item->temuan->count();
            });

        return view(
            'penerapan.index',
            compact(
                'periode',
                'data',
                'jumlahPenerapan',
                'jumlahBukti',
                'jumlahAuditee',
                'jumlahTemuan'
            )
        );
    }

    /**
     * Menampilkan detail satu penerapan standar.
     *
     * Tidak ada proses edit atau hapus pada halaman ini.
     */
    public function show($id, $penerapan)
    {
        $periode = PeriodeAmi::with([
            'standarMutu',
            'unitKerja',
            'user',
        ])->findOrFail($id);

        $data = PenerapanStandar::with([
            'user',
            'indikator',

            'standarMutuPeriodeAmi',
            'standarMutuPeriodeAmi.standarMutu',
            'standarMutuPeriodeAmi.periodeAmi',

            'temuan',
        ])
            ->whereHas(
                'standarMutuPeriodeAmi',
                function ($query) use ($id) {
                    $query->where(
                        'id_periode_ami',
                        $id
                    );
                }
            )
            ->findOrFail($penerapan);

        return view(
            'penerapan.show',
            compact(
                'periode',
                'data'
            )
        );
    }
}