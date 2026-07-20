@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Edit Standar Mutu
</h3>

<div class="form-container">

    <div class="form-card">

        <h3 class="form-title">
            Edit Standar Mutu
        </h3>

        <form action="{{ route('standarmutu.update',$standar->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">

                <label>
                    Nama Standar Mutu
                </label>

                <input type="text"
                       name="nama_standar_mutu"
                       value="{{ old('nama_standar_mutu',$standar->nama_standar_mutu) }}">

                @error('nama_standar_mutu')

                    <small style="color:red">

                        {{ $message }}

                    </small>

                @enderror

            </div>

            <div class="form-action">

                <button type="submit"
                        class="btn-save">

                    Update

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
