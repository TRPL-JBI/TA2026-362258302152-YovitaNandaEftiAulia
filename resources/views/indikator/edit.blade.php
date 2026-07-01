@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Indikator / Edit
</h3>

<div class="form-container">

<div class="form-card">

<h3 class="form-title">

Edit Indikator

</h3>

<form
action="{{ route('indikator.update',$indikator->id) }}"
method="POST">

@csrf
@method('PUT')

<div class="form-group">

<label>

Deskripsi Indikator

</label>

<textarea
name="deskripsi"
rows="5"
style="width:100%;padding:12px;border:1px solid #ccc;border-radius:8px;">{{ old('deskripsi',$indikator->deskripsi) }}</textarea>

</div>

<div class="form-action">

<button
class="btn-save">

Update

</button>

<a
href="{{ route('indikator.index',$indikator->id_isi_standar_mutu) }}"
class="btn-cancel">

Batal

</a>

</div>

</form>

</div>

</div>

@endsection