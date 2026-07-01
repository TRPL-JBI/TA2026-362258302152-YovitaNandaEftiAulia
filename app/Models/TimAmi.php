<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimAmi extends Model
{
    protected $table = 'tim_ami';

    protected $fillable = [
        'id_periode_ami',
        'id_user',
        'role'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }

    public function periode()
    {
        return $this->belongsTo(
            PeriodeAmi::class,
            'id_periode_ami'
        );
    }
}