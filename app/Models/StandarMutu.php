<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandarMutu extends Model
{
    protected $table = 'standar_mutu';

    protected $fillable = ['nama_standar_mutu'];

    public $timestamps = false; // 🔥 TAMBAH INI

    public function isi()
    {
        return $this->hasMany(IsiStandarMutu::class, 'id_standar_mutu');
    }
}