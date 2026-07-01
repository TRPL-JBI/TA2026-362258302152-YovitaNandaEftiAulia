<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorStandar extends Model
{
    protected $table = 'indikator_standar';

    protected $fillable = [
        'id_isi_standar_mutu',
        'deskripsi'
    ];

    public $timestamps = false;

    /**
     * Indikator dimiliki oleh satu Isi Standar
     */
    public function isiStandar()
    {
        return $this->belongsTo(
            IsiStandarMutu::class,
            'id_isi_standar_mutu'
        );
    }
}