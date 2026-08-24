<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'kategori_unit_kerja',
        'id_user',
    ];

    /**
     * User yang menjadi Kepala Unit.
     */
    public function kepalaUnit()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id'
        );
    }

    /**
     * Seluruh user yang ditempatkan pada unit kerja ini.
     *
     * Relasi ini digunakan apabila tabel users mempunyai
     * kolom id_unit_kerja.
     */
    public function users()
    {
        return $this->hasMany(
            User::class,
            'id_unit_kerja',
            'id'
        );
    }
}