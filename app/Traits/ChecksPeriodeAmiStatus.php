<?php

namespace App\Traits;

use App\Models\PeriodeAmi;

trait ChecksPeriodeAmiStatus
{
    /*
    |--------------------------------------------------------------------------
    | PERIKSA STATUS PERIODE AMI
    |--------------------------------------------------------------------------
    |
    | Data yang berada pada periode AMI yang sudah ditutup tidak boleh
    | ditambah, diubah, dihapus, atau diproses kembali.
    |
    */

    protected function abortIfPeriodeClosed(
        ?PeriodeAmi $periode
    ): void {
        abort_unless(
            $periode,
            404,
            'Periode AMI tidak ditemukan.'
        );

        $status = strtolower(
            trim(
                (string) $periode->status
            )
        );

        abort_if(
            in_array(
                $status,
                [
                    'ditutup',
                    'closed',
                    'selesai',
                ],
                true
            ),
            403,
            'Periode AMI sudah ditutup dan data tidak dapat diubah.'
        );
    }
}