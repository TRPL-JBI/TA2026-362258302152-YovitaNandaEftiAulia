@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Tambah Standar Mutu
</h3>

<div class="form-container">

    <div class="form-card">

        <h3 class="form-title">
            Tambah Standar Mutu
        </h3>

        <form action="{{ route('standarmutu.store') }}"
              method="POST">

            @csrf

            <div class="form-group">

                <label>
                    Nama Standar Mutu
                </label>

                <input type="text"
                       name="nama_standar_mutu"
                       value="{{ old('nama_standar_mutu') }}"
                       placeholder="Masukkan Nama Standar Mutu">

                @error('nama_standar_mutu')

                    <small style="color:red">

                        {{ $message }}

                    </small>

                @enderror

            </div>

            <div class="form-action">

                <button type="submit"
                        class="btn-save">

                    Simpan

                </button>

                <a href="{{ route('standarmutu.index') }}"
                   class="btn-cancel">

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection