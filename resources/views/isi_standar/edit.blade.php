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

        <form action="{{ route('isi.update',$data->id) }}" method="POST">

            @csrf
            @method('PUT')

            @if($parent)

                <div class="form-group">

                    <label>Parent Standar</label>

                    <input
                        type="text"
                        value="{{ $parent->nama_standar }}"
                        readonly>

                </div>

            @endif

            <div class="form-group">

                <label for="nama_standar">

                    Nama Isi Standar

                </label>

                <input
                    type="text"
                    id="nama_standar"
                    name="nama_standar"
                    value="{{ old('nama_standar',$data->nama_standar) }}"
                    placeholder="Masukkan nama isi standar"
                    required>

                @error('nama_standar')

                    <small class="text-danger">

                        {{ $message }}

                    </small>

                @enderror

            </div>

            <div class="form-action">

                <button
                    type="submit"
                    class="btn-save">

                    Update

                </button>

                @if($parent)

                    <a
                        href="{{ route('isi.show',$parent->id) }}"
                        class="btn-cancel">

                        Batal

                    </a>

                @else

                    <a
                        href="{{ route('isi.index',$standarMutu->id) }}"
                        class="btn-cancel">

                        Batal

                    </a>

                @endif

            </div>

        </form>

    </div>

</div>

@endsection