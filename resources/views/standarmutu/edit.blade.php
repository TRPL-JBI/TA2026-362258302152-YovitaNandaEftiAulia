@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <h3 class="breadcrumb">Dashboard / Edit Standar Mutu</h3>

    <div class="form-card">

        <h2 class="form-title">Form Edit Standar Mutu</h2>

        <form action="{{ route('standarmutu.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Standar Mutu</label>
                <input 
                    type="text" 
                    name="nama_standar_mutu" 
                    value="{{ $data->nama_standar_mutu }}" 
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