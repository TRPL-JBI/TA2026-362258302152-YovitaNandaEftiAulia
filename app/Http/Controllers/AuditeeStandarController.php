<?php

namespace App\Http\Controllers;

use App\Models\PenerapanStandar;
use App\Models\StandarMutuPeriodeAmi;
use App\Services\StandarTableService;

class AuditeeStandarController extends Controller
{
    public function index($id, StandarTableService $service)
    {
        $table = $service->generateTable($id);

        $standar = $table['standar'];
        $rows = $table['rows'];

        $maxLevel = collect($rows)
            ->map(fn ($row) => count($row['level']))
            ->max() ?? 0;

        /*
        |--------------------------------------------------------------------------
        | PERIODE AMI BERJALAN
        |--------------------------------------------------------------------------
        |
        | Status draf, berjalan, dan ditutup berada di tabel periode_ami.
        | Karena itu pengecekan dilakukan melalui relasi periodeAmi.
        |
        */
        $standarPeriode = StandarMutuPeriodeAmi::with('periodeAmi')
            ->where('id_standar_mutu', $standar->id)
            ->whereHas('periodeAmi', function ($query) {
                $query->whereRaw(
                    'LOWER(TRIM(status)) = ?',
                    ['berjalan']
                );
            })
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | USER AUDITEE
        |--------------------------------------------------------------------------
        */
        $user = request()->attributes->get('auth_user')
            ?? \App\Models\User::find(session('user_id'));

        abort_unless(
            $user && $user->status === 'aktif',
            403,
            'Akun tidak ditemukan atau sudah dinonaktifkan.'
        );
        $idUser = is_array($user)
            ? ($user['id'] ?? null)
            : ($user->id ?? null);

        /*
        |--------------------------------------------------------------------------
        | DATA PENERAPAN BERDASARKAN INDIKATOR
        |--------------------------------------------------------------------------
        */
        $penerapanByIndikator = collect();

        if ($standarPeriode && $idUser) {
            $penerapanByIndikator = PenerapanStandar::where(
                'id_standarmutu_periodeami',
                $standarPeriode->id
            )
                ->where('id_user', $idUser)
                ->get()
                ->keyBy('id_indikator');
        }

        return view('auditee.standar.index', compact(
            'standar',
            'rows',
            'maxLevel',
            'standarPeriode',
            'penerapanByIndikator'
        ));
    }
}