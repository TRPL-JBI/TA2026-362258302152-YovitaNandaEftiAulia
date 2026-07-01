<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IsiStandarMutu extends Model
{
    protected $table = 'isi_standar_mutu';

    protected $fillable = [
        'id_standar_mutu',
        'nama_standar',
        'parent_standar_id'
    ];

    public $timestamps = false;

    public function standarMutu()
    {
        return $this->belongsTo(
            StandarMutu::class,
            'id_standar_mutu'
        );
    }

    public function indikator()
    {
        return $this->hasMany(
            IndikatorStandar::class,
            'id_isi_standar_mutu'
        );
    }

    public function parent()
    {
        return $this->belongsTo(
            IsiStandarMutu::class,
            'parent_standar_id'
        );
    }

    public function children()
    {
        return $this->hasMany(
            IsiStandarMutu::class,
            'parent_standar_id'
        );
    }
}