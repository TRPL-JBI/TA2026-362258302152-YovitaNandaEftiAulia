<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandarMutuPeriodeAmi extends Model
{
    protected $table = 'standarmutu_periodeami';

    public $timestamps = false;

    protected $fillable = [
        'id_standar_mutu',
        'id_periode_ami',
        'status'
    ];

    public function standarMutu()
    {
        return $this->belongsTo(
            StandarMutu::class,
            'id_standar_mutu'
        );
    }

    public function periodeAmi()
    {
        return $this->belongsTo(
            PeriodeAmi::class,
            'id_periode_ami'
        );
    }

    public function penerapanStandar()
    {
        return $this->hasMany(
            PenerapanStandar::class,
            'id_standarmutu_periodeami'
        );
    }


    /*
|--------------------------------------------------------------------------
| INDIKATOR
|--------------------------------------------------------------------------
*/

public function indikator()
{
    return $this->belongsTo(

        IndikatorStandar::class,

        'id_indikator'

    );
}
    
}