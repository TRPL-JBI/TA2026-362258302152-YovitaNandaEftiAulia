<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LampiranAudit extends Model
{
    protected $table = 'lampiran_audit';

    public $timestamps = false;

    protected $fillable = [
        'id_periode_ami',
        'link_file',
        'id_user',
    ];

    public function periodeAmi()
    {
        return $this->belongsTo(
            PeriodeAmi::class,
            'id_periode_ami'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }
}