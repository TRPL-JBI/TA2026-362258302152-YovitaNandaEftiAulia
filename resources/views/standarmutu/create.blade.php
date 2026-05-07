@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <h3 class="breadcrumb">Dashboard / Tambah Standar Mutu</h3>

    <div class="form-card">

        <h2 class="form-title">Form Tambah Standar Mutu</h2>

        <form action="{{ route('standarmutu.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Nama Standar Mutu</label>
                <input 
                    type="text" 
                    name="nama_standar_mutu" 
                    placeholder="Masukkan nama standar mutu" 
                    required
                >
            </div>

            <div class="form-action">
                <button type="submit" class="btn-save">Simpan</button>

                <a href="{{ route('standarmutu.index') }}" class="btn-cancel">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>

@endsection