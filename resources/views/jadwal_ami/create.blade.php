@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('periode-ami.show', $periodeAmi->id) }}">
        Detail Periode AMI
    </a>

    <a href="{{ route('penerapan.index', $periodeAmi->id) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('tim-ami.index', $periodeAmi->id) }}">
        Tim AMI
    </a>

    <a
        href="{{ route('jadwal.index', $periodeAmi->id) }}"
        class="active"
    >
        Jadwal AMI
    </a>

</div>

<div class="form-container">

    <div class="form-card">

        <h3 class="form-title">
            Tambah Jadwal AMI
        </h3>

        {{-- PESAN ERROR VALIDASI --}}
        @if ($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Data belum dapat disimpan.
                </strong>

                <ul style="margin: 8px 0 0 18px;">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form
            action="{{ route('jadwal.store', $periodeAmi->id) }}"
            method="POST"
        >

            @csrf

            <!-- KEGIATAN -->
            <div class="form-group">

                <label for="kegiatan">
                    Nama Kegiatan
                </label>

                <input
                    type="text"
                    name="kegiatan"
                    id="kegiatan"
                    value="{{ old('kegiatan') }}"
                    placeholder="Masukkan nama kegiatan"
                    required
                >

                @error('kegiatan')

                    <span class="error-text">
                        {{ $message }}
                    </span>

                @enderror

            </div>

            <!-- WAKTU -->
            <div class="form-group">

                <label for="waktu">
                    Waktu
                </label>

                <input
                    type="text"
                    name="waktu"
                    id="waktu"
                    placeholder="Contoh: 08.00 - 09.00 WIB"
                    value="{{ old('waktu') }}"
                    required
                >

                @error('waktu')

                    <span class="error-text">
                        {{ $message }}
                    </span>

                @enderror

            </div>

            <div class="form-action">

                <button
                    type="submit"
                    class="btn-save"
                >
                    Simpan
                </button>

                <a
                    href="{{ route('jadwal.index', $periodeAmi->id) }}"
                    class="btn-cancel"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection