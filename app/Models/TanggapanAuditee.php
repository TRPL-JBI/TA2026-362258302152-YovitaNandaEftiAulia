<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TanggapanAuditee extends Model
{
    use SoftDeletes;

    protected $table = 'tanggapan_auditee';

    public $timestamps = false;

    protected $fillable = [
        'id_temuan_ami',
        'tanggapan',
        'id_user',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    public function temuan(): BelongsTo
    {
        return $this->belongsTo(
            TemuanAmi::class,
            'id_temuan_ami'
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