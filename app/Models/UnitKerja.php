<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnitKerja extends Model
{
    protected $table = 'unit_kerja';

    protected $fillable = [
        'nama',
        'kategori_unit_kerja',
        'id_user',
    ];

    /**
     * User/Auditee yang menjadi Kepala Unit.
     */
    public function kepalaUnit(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id'
        );
    }

    /**
     * Data Periode AMI yang memakai Unit Kerja.
     */
    public function periodeAmi(): HasMany
    {
        return $this->hasMany(
            PeriodeAmi::class,
            'id_unit_kerja',
            'id'
        );
    }
}