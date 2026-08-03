@extends('layouts.app')

@section('content')

<link
    rel="stylesheet"
    href="{{ asset('css/19-admin-unit-kerja.css') }}"
>

<div class="unit-page">

    <h3 class="unit-breadcrumb">
        Dashboard / Tambah Unit Kerja
    </h3>

    <div class="unit-card unit-form-card">

        <h1 class="unit-form-title">
            Form Tambah Unit Kerja
        </h1>

        <form
            action="{{ route('unit-kerja.store') }}"
            method="POST"
            class="unit-form"
        >
            @csrf

            <div class="unit-form-group">

                <label for="nama">
                    Nama Unit Kerja
                </label>

                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    placeholder="Masukkan nama unit kerja"
                    class="unit-input @error('nama') unit-input-error @enderror"
                    autocomplete="off"
                >

                @error('nama')
                    <small class="unit-error-text">
                        {{ $message }}
                    </small>
                @enderror

            </div>

            <div class="unit-form-group">

                <label for="kategori_unit_kerja">
                    Kategori Unit Kerja
                </label>

                <select
                    id="kategori_unit_kerja"
                    name="kategori_unit_kerja"
                    class="unit-select @error('kategori_unit_kerja') unit-input-error @enderror"
                >
                    <option value="">
                        -- Pilih Kategori --
                    </option>

                    <option
                        value="akademik"
                        {{ old('kategori_unit_kerja') === 'akademik' ? 'selected' : '' }}
                    >
                        Akademik
                    </option>

                    <option
                        value="non akademik"
                        {{ old('kategori_unit_kerja') === 'non akademik' ? 'selected' : '' }}
                    >
                        Non Akademik
                    </option>

                </select>

                @error('kategori_unit_kerja')
                    <small class="unit-error-text">
                        {{ $message }}
                    </small>
                @enderror

            </div>

            <div class="unit-form-actions">

                <button
                    type="submit"
                    class="unit-save-button"
                >
                    Simpan
                </button>

                <a
                    href="{{ route('unit-kerja.index') }}"
                    class="unit-cancel-button"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection