@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">

Dashboard / Standar Mutu / Isi Standar / Edit

</h3>

<div class="form-container">

<div class="form-card">

<h3 class="form-title">

Edit Isi Standar

</h3>

<form
action="{{ route('isi.update',$isiStandar->id) }}"
method="POST">

@csrf
@method('PUT')

<div class="form-group">

<label>

Nama Isi Standar

</label>

<input
type="text"
name="nama_standar"
value="{{ $isiStandar->nama_standar }}">

</div>

<div class="form-group">

<label>

Parent Standar

</label>

<select name="parent_standar_id">

<option value="">

Tidak Ada

</option>

@foreach($parent as $p)

<option
value="{{ $p->id }}"
{{ $isiStandar->parent_standar_id==$p->id?'selected':'' }}>

{{ $p->nama_standar }}

</option>

@endforeach

</select>

</div>

<div class="form-action">

<button
class="btn-save">

Update

</button>

<a
href="{{ route('isi.index',$isiStandar->id_standar_mutu) }}"
class="btn-cancel">

Batal

</a>

</div>

</form>

</div>

</div>

@endsection