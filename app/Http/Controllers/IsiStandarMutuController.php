<?php

namespace App\Http\Controllers;

use App\Models\StandarMutu;
use App\Models\IsiStandarMutu;
use Illuminate\Http\Request;

class IsiStandarMutuController extends Controller
{

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

public function index($standar)
{
    $standarMutu = StandarMutu::findOrFail($standar);

    $data = IsiStandarMutu::with('children')
                ->where('id_standar_mutu',$standar)
                ->whereNull('parent_standar_id')
                ->orderBy('id')
                ->get();

    return view(
        'isi_standar.index',
        [
            'standarMutu'=>$standarMutu,
            'data'=>$data,
            'parent'=>null,
            'breadcrumb'=>[]
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

    /*
    |----------------------------------------------------------
    | Ambil semua child
    |----------------------------------------------------------
    */

    $data = IsiStandarMutu::with('children')
                ->where('parent_standar_id',$id)
                ->orderBy('id')
                ->get();

    

    return view(
        'isi_standar.index',
        [
            'standarMutu'=>$parent->standarMutu,
            'data'=>$data,
            'parent'=>$parent,
            'breadcrumb'=>$this->breadcrumb($id)
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
        'isi_standar.show',
        [
            'isi'=>$isi,
            'breadcrumb'=>$this->breadcrumb($id)
        ]
    );
}

/*
|--------------------------------------------------------------------------
| CREATE
|--------------------------------------------------------------------------
*/

public function create(Request $request, $param)
{
    // Tambah dari Standar Mutu
    if ($request->route()->getName() == 'isi.create') {

        $standarMutu = StandarMutu::findOrFail($param);

        return view('isi_standar.create',[
            'standarMutu'=>$standarMutu,
            'parent'=>null
        ]);
    }

    // Tambah dari Isi Standar
    $parent = IsiStandarMutu::with('standarMutu')->findOrFail($param);

    return view('isi_standar.create',[
        'standarMutu'=>$parent->standarMutu,
        'parent'=>$parent
    ]);
}
/*
|--------------------------------------------------------------------------
| STORE
|--------------------------------------------------------------------------
*/

public function store(Request $request, $param)
{
    $request->validate([
        'nama_standar'=>'required|max:255'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Dari Standar Mutu
    |--------------------------------------------------------------------------
    */

    if($request->route()->getName()=='isi.store'){

        IsiStandarMutu::create([

            'id_standar_mutu'=>$param,

            'nama_standar'=>$request->nama_standar,

            'parent_standar_id'=>null

        ]);

        return redirect()
            ->route('isi.index',$param)
            ->with('success','Data berhasil ditambahkan.');

    }

    /*
    |--------------------------------------------------------------------------
    | Dari Isi Standar
    |--------------------------------------------------------------------------
    */

    $parent = IsiStandarMutu::findOrFail($param);

    IsiStandarMutu::create([

        'id_standar_mutu'=>$parent->id_standar_mutu,

        'nama_standar'=>$request->nama_standar,

        'parent_standar_id'=>$parent->id

    ]);

    return redirect()
        ->route('isi.show',$parent->id)
        ->with('success','Data berhasil ditambahkan.');
}
/*
|--------------------------------------------------------------------------
| EDIT
|--------------------------------------------------------------------------
*/

public function edit($id)
{
    $data = IsiStandarMutu::with([
        'standarMutu',
        'parent'
    ])->findOrFail($id);

    return view(
        'isi_standar.edit',
        [
            'data'=>$data,
            'parent'=>$data->parent,
            'standarMutu'=>$data->standarMutu,
            'breadcrumb'=>$this->breadcrumb($id)
        ]
    );
}

/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

public function update(Request $request,$id)
{
    $request->validate([
        'nama_standar'=>'required|max:255'
    ]);

    $data = IsiStandarMutu::findOrFail($id);

    $data->update([

        'nama_standar'=>$request->nama_standar

    ]);

    if($data->parent_standar_id){

        return redirect()
            ->route(
                'isi.show',
                $data->parent_standar_id
            )
            ->with(
                'success',
                'Data berhasil diperbarui.'
            );

    }

    return redirect()
        ->route(
            'isi.index',
            $data->id_standar_mutu
        )
        ->with(
            'success',
            'Data berhasil diperbarui.'
        );
}
/*
|--------------------------------------------------------------------------
| DESTROY
|--------------------------------------------------------------------------
*/

public function destroy($id)
{
    $node = IsiStandarMutu::findOrFail($id);

    $parent = $node->parent_standar_id;

    $standar = $node->id_standar_mutu;

    $this->deleteRecursive($node);

    if($parent){

        return redirect()
            ->route(
                'isi.show',
                $parent
            )
            ->with(
                'success',
                'Data berhasil dihapus.'
            );

    }

    return redirect()
        ->route(
            'isi.index',
            $standar
        )
        ->with(
            'success',
            'Data berhasil dihapus.'
        );
}

/*
|--------------------------------------------------------------------------
| DELETE RECURSIVE
|--------------------------------------------------------------------------
*/

private function deleteRecursive(IsiStandarMutu $node)
{
    foreach($node->children as $child){

        $this->deleteRecursive($child);

    }

    foreach($node->indikator as $indikator){

        $indikator->delete();

    }

    $node->delete();
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

    while($node){

        array_unshift($items,$node);

        $node = $node->parent;

    }

    return $items;
}

}