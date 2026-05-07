@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">Edit User</h3>

<div class="form-card">

<form action="{{ route('user.update', $data->id) }}" method="POST">
@csrf
@method('PUT')

<input type="text" name="nama" value="{{ $data->nama }}">
<input type="email" name="email" value="{{ $data->email }}">
<input type="text" name="password" value="{{ $data->password }}">

<select name="id_unit_kerja">
    @foreach($unit as $u)
        <option value="{{ $u->id }}" {{ $data->id_unit_kerja == $u->id ? 'selected' : '' }}>
            {{ $u->nama }}
        </option>
    @endforeach
</select>

<select name="status">
    <option value="aktif" {{ $data->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
    <option value="nonaktif" {{ $data->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
</select>

<button class="btn-save">Update</button>

</form>

</div>

@endsection