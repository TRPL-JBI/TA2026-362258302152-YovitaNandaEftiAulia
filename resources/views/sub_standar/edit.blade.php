@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">

Dashboard / Edit Sub Standar

</h3>

<div class="form-card">

<form
action="{{ route('substandar.update',$subStandar->id) }}"
method="POST">

@csrf

@method('PUT')

<div class="form-group">

<label>

Nama Sub Standar

</label>

<input

type="text"

name="nama_standar"

value="{{ $subStandar->nama_standar }}"

required>

</div>

<div class="form-action">

<button
class="btn-save">

Update

</button>

<a
href="{{ route('substandar.index',$subStandar->parent_standar_id) }}"
class="btn-cancel">

Kembali

</a>

</div>

</form>

</div>

@endsection