@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Isi Standar / Tambah
</h3>

<div class="form-container">

    <div class="form-card">

        <h3 class="form-title">

            @if(isset($parent))
                Tambah Sub Standar
            @else
                Tambah Isi Standar
            @endif

        </h3>

        @if(isset($parent))

            <form action="{{ route('isi.node.store',$parent->id) }}" method="POST">

        @else

            <form action="{{ route('isi.store',$standarMutu->id) }}" method="POST">

        @endif

            @csrf

            @if(isset($parent))

                <input
                    type="hidden"
                    name="parent_standar_id"
                    value="{{ $parent->id }}">

                <div class="form-group">

                    <label>Parent Standar</label>

                    <input
                        type="text"
                        class="readonly-input"
                        value="{{ $parent->nama_standar }}"
                        readonly
                        disabled>

                </div>

            @endif

            <div class="form-group">

                <label for="nama_standar">

                    @if(isset($parent))
                        Nama Sub Standar
                    @else
                        Nama Isi Standar
                    @endif

                </label>

                <input
                    type="text"
                    id="nama_standar"
                    name="nama_standar"
                    value="{{ old('nama_standar') }}"
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

                    Simpan

                </button>

                @if(isset($parent))

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

<style>

.readonly-input{

    background:#F3F4F6 !important;
    color:#6B7280 !important;
    border:1px solid #D1D5DB !important;
    cursor:not-allowed;
    pointer-events:none;
    user-select:none;

}

.readonly-input:focus{

    outline:none;
    box-shadow:none;

}

</style>

@endsection
