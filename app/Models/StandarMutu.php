<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandarMutu extends Model
{
    protected $table = 'standar_mutu';

    protected $fillable = [
        'nama_standar_mutu'
    ];

    // TAMBAHKAN INI
    public $timestamps = false;

    public function isiStandar()
    {
        return $this->hasMany(
            IsiStandarMutu::class,
            'id_standar_mutu'
        );
    }
}