@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <h3 class="breadcrumb">Dashboard / Tambah Unit Kerja</h3>

    <div class="form-card">

        <h2 class="form-title">Form Tambah Unit Kerja</h2>

        <form action="{{ route('unit-kerja.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Unit Kerja</label>
                <input type="text" name="nama" placeholder="Masukkan nama unit kerja" required>
            </div>

            <div class="form-group">
                <label>Kategori Unit Kerja</label>
                <select name="kategori_unit_kerja" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Akademik">Akademik</option>
                    <option value="Non Akademik">Non Akademik</option>
                </select>
            </div>

            <div class="form-action">
                <button type="submit" class="btn-save">Simpan</button>
                <a href="{{ route('unit-kerja.index') }}" class="btn-cancel">Batal</a>
            </div>

        </form>

    </div>

</div>

@endsection