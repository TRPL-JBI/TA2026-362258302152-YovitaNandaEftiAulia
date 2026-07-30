<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LampiranAudit extends Model
{
    use SoftDeletes;

    protected $table = 'lampiran_audit';

    public $timestamps = false;

    protected $fillable = [
        'id_periode_ami',
        'link_file',
        'id_user',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function periodeAmi(): BelongsTo
    {
        return $this->belongsTo(
            PeriodeAmi::class,
            'id_periode_ami'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_user'
        );
    }
}