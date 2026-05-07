<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'id_unit_kerja',
        'status'
    ];

    public $timestamps = false;

    public function unit()
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_kerja');
    }
}