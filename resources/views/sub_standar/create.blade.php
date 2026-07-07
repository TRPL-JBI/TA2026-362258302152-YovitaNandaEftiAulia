@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">

Dashboard / Tambah Sub Standar

</h3>

<div class="form-card">

<form
action="{{ route('substandar.store',$isiStandar->id) }}"
method="POST">

@csrf

<div class="form-group">

<label>

Nama Sub Standar

</label>

<input
type="text"
name="nama_standar"
required>

</div>

<div class="form-action">

<button
class="btn-save">

Simpan

</button>

<a
href="{{ route('substandar.index',$isiStandar->id) }}"
class="btn-cancel">

Kembali

</a>

</div>

</form>

</div>

@endsection