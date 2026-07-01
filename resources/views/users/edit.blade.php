@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Edit User
</h3>

<div class="form-card">

<form action="{{ route('user.update', $data->id) }}" method="POST">

    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Nama</label>
        <input
            type="text"
            name="nama"
            value="{{ $data->nama }}"
            required>
    </div>

    <div class="form-group">
        <label>Email</label>
        <input
            type="email"
            name="email"
            value="{{ $data->email }}"
            required>
    </div>

    <div class="form-group">
        <label>Password Baru</label>
        <input
            type="password"
            name="password"
            placeholder="Kosongkan jika tidak diubah">
    </div>

    <div class="form-group">

        <label>Role</label>

        <select name="role" required>

            <option value="admin"
                {{ $data->role == 'admin' ? 'selected' : '' }}>
                Admin
            </option>

            <option value="auditor"
                {{ $data->role == 'auditor' ? 'selected' : '' }}>
                Auditor
            </option>

            <option value="auditee"
                {{ $data->role == 'auditee' ? 'selected' : '' }}>
                Auditee
            </option>

        </select>

    </div>

    <div class="form-group">

        <label>Unit Kerja</label>

        <select name="id_unit_kerja">

            @foreach($unit as $u)

                <option
                    value="{{ $u->id }}"
                    {{ $data->id_unit_kerja == $u->id ? 'selected' : '' }}>

                    {{ $u->nama }}

                </option>

            @endforeach

        </select>

    </div>

    <div class="form-group">

        <label>Status</label>

        <select name="status">

            <option value="aktif"
                {{ $data->status == 'aktif' ? 'selected' : '' }}>
                Aktif
            </option>

            <option value="nonaktif"
                {{ $data->status == 'nonaktif' ? 'selected' : '' }}>
                Nonaktif
            </option>

        </select>

    </div>

    <div class="form-action">

        <button type="submit" class="btn-save">
            Update
        </button>

    </div>

</form>

</div>

@endsection