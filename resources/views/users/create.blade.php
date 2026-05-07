@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">Tambah User</h3>

<div class="form-card">

<form action="{{ route('user.store') }}" method="POST">
@csrf

<div class="form-group">
    <label>Nama</label>
    <input type="text" name="nama" required>
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" required>
</div>

<div class="form-group">
    <label>Password</label>
    <input type="text" name="password" required>
</div>

<div class="form-group">
    <label>Unit Kerja</label>
    <select name="id_unit_kerja">
        @foreach($unit as $u)
            <option value="{{ $u->id }}">{{ $u->nama }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>Status</label>
    <select name="status">
        <option value="aktif">Aktif</option>
        <option value="nonaktif">Nonaktif</option>
    </select>
</div>

<div class="form-action">
    <button class="btn-save">Simpan</button>
</div>

</form>

</div>

@endsection