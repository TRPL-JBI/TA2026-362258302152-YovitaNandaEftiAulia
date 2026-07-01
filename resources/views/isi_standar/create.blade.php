@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Isi Standar / Tambah
</h3>

<div class="form-container">

    <div class="form-card">

        <h3 class="form-title">
            Tambah Isi Standar
        </h3>

        <form action="{{ route('isi.store', $standarMutu->id) }}" method="POST">

            @csrf

            <div class="form-group">

                <label for="nama_standar">
                    Nama Isi Standar
                </label>

                <input
                    type="text"
                    id="nama_standar"
                    name="nama_standar"
                    value="{{ old('nama_standar') }}"
                    placeholder="Masukkan nama isi standar"
                    required
                >

                @error('nama_standar')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror

            </div>

            <div class="form-action">

                <button type="submit" class="btn-save">
                    Simpan
                </button>

                <a href="{{ route('isi.index', $standarMutu->id) }}"
                   class="btn-cancel">
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection