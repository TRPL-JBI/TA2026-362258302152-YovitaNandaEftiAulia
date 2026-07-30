<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkalaSkor extends Model
{
    protected $table = 'skala_skor';

    protected $fillable = [
        'label_skor',
        'nilai_skor',
    ];

    protected $casts = [
        'nilai_skor' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | SKOR PENERAPAN STANDAR
    |--------------------------------------------------------------------------
    */

    public function skorPenerapan(): HasMany
    {
        return $this->hasMany(
            SkorPenerapanStandar::class,
            'id_skala_skor',
            'id'
        );
    }
}