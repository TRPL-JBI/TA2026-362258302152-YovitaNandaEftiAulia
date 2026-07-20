@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Indikator / Tambah
</h3>

<div class="form-container">

<div class="form-card">

<h3 class="form-title">

Tambah Indikator

</h3>

<form
action="{{ route('indikator.store',$isiStandar->id) }}"
method="POST">

@csrf

<div class="form-group">

<label>

Deskripsi Indikator

</label>

<textarea
name="deskripsi"
rows="5"
style="width:100%;padding:12px;border:1px solid #ccc;border-radius:8px;">{{ old('deskripsi') }}</textarea>

</div>

<div class="form-action">

<button
class="btn-save">

Simpan

</button>

<a
href="{{ route('indikator.index',$isiStandar->id) }}"
class="btn-cancel">

Batal

</a>

</div>

</form>

</div>

</div>

@endsection
