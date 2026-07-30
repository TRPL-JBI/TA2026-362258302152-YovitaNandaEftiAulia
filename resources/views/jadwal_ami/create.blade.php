@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('periode-ami.show',$periodeAmi->id) }}">
        Detail Periode AMI
    </a>

    <a href="{{ route('penerapan.index',$periodeAmi->id) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('penerapan.index') }}">
        Penerapan Standar
    </a>

    <a href="{{ route('tim-ami.index',$periodeAmi->id) }}">
        Tim AMI
    </a>

    <a href="{{ route('jadwal.index',$periodeAmi->id) }}"
       class="active">
        Jadwal AMI
    </a>

</div>

<div class="form-container">

    <div class="form-card">

        <h3 class="form-title">
            Tambah Jadwal AMI
        </h3>

        <form action="{{ route('jadwal.store',$periodeAmi->id) }}"
              method="POST">

            @csrf

            <!-- KEGIATAN -->
            <div class="form-group">

                <label>
                    Nama Kegiatan
                </label>

                <input
                    type="text"
                    name="kegiatan"
                    value="{{ old('kegiatan') }}"
                    required>

            </div>

            <!-- WAKTU -->
            <div class="form-group">

                <label>
                    Waktu
                </label>

                <input
                    type="text"
                    name="waktu"
                    placeholder="Contoh : 08.00 - 09.00"
                    value="{{ old('waktu') }}"
                    required>

            </div>

            <div class="form-action">

                <button
                    type="submit"
                    class="btn-save">

                    Simpan

                </button>

                <a href="{{ route('jadwal.index',$periodeAmi->id) }}"
                   class="btn-cancel">

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection



