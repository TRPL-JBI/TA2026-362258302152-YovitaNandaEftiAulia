<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KesimpulanAudit extends Model
{
    protected $table = 'kesimpulan_audit';

    public $timestamps = false;

    protected $fillable = [
        'id_periode_ami',
        'kesimpulan',
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