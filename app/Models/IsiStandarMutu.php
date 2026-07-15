<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IsiStandarMutu extends Model
{
    protected $table = 'isi_standar_mutu';

    protected $fillable = [
        'id_standar_mutu',
        'nama_standar',
        'parent_standar_id'
    ];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | RELASI KE STANDAR MUTU
    |--------------------------------------------------------------------------
    */

    public function standarMutu()
    {
        return $this->belongsTo(
            StandarMutu::class,
            'id_standar_mutu'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI KE PARENT
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(
            IsiStandarMutu::class,
            'parent_standar_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI KE CHILD
    |--------------------------------------------------------------------------
    */

    public function children()
    {
        return $this->hasMany(
            IsiStandarMutu::class,
            'parent_standar_id'
        )->orderBy('id');
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI KE INDIKATOR
    |--------------------------------------------------------------------------
    */

    public function indikator()
    {
        return $this->hasMany(
            IndikatorStandar::class,
            'id_isi_standar_mutu'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CEK APAKAH PUNYA CHILD
    |--------------------------------------------------------------------------
    */

    public function hasChildren()
    {
        return $this->children()->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | CEK APAKAH NODE TERAKHIR (LEAF)
    |--------------------------------------------------------------------------
    */

    public function isLeaf()
    {
        return !$this->hasChildren();
    }

    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL SELURUH PARENT (UNTUK BREADCRUMB)
    |--------------------------------------------------------------------------
    */

    public function ancestors()
    {
        $items = [];

        $node = $this;

        while ($node) {

            array_unshift($items, $node);

            $node = $node->parent;

        }

        return collect($items);
    }

    /*
    |--------------------------------------------------------------------------
    | MENGAMBIL LEVEL HIERARKI
    |--------------------------------------------------------------------------
    */

    public function level()
    {
        $level = 0;

        $node = $this->parent;

        while ($node) {

            $level++;

            $node = $node->parent;

        }

        return $level;
    }

    /*
|--------------------------------------------------------------------------
| Recursive Children + Indikator
|--------------------------------------------------------------------------
*/

public function recursiveChildrenWithIndikator()
{
    return $this->children()->with([
        'recursiveChildrenWithIndikator',
        'indikator.penerapan',
        'standarMutu',
    ]);
}
}