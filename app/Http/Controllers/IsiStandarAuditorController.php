<?php

namespace App\Http\Controllers;

use App\Models\StandarMutu;
use App\Models\IsiStandarMutu;

class IsiStandarAuditorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ROOT
    |--------------------------------------------------------------------------
    */

    public function index($standar)
    {
        $standar = StandarMutu::findOrFail($standar);

        $data = IsiStandarMutu::with('children')
                    ->where('id_standar_mutu', $standar->id)
                    ->whereNull('parent_standar_id')
                    ->orderBy('id')
                    ->get();

        return view(
            'auditor.isi.index',
            [
                'standar'    => $standar,
                'data'       => $data,
                'parent'     => null,
                'breadcrumb' => []
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | OPEN NODE
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $parent = IsiStandarMutu::with([
            'standarMutu',
            'parent',
            'children'
        ])->findOrFail($id);

        $data = IsiStandarMutu::with('children')
                    ->where('parent_standar_id', $id)
                    ->orderBy('id')
                    ->get();

        return view(
            'auditor.isi.index',
            [
                'standar'    => $parent->standarMutu,
                'data'       => $data,
                'parent'     => $parent,
                'breadcrumb' => $this->breadcrumb($id)
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */

    public function detail($id)
    {
        $isi = IsiStandarMutu::with([
            'standarMutu',
            'parent',
            'children',
            'indikator'
        ])->findOrFail($id);

        return view(
            'auditor.isi.show',
            [
                'isi'        => $isi,
                'breadcrumb' => $this->breadcrumb($id)
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BREADCRUMB
    |--------------------------------------------------------------------------
    */

    private function breadcrumb($id)
    {
        $items = [];

        $node = IsiStandarMutu::with('parent')->find($id);

        while ($node) {

            array_unshift($items, $node);

            $node = $node->parent;
        }

        return $items;
    }
}