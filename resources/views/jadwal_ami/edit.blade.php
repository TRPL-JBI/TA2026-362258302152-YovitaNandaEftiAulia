@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('periode-ami.show',$jadwal->id_periode_ami) }}">
        Detail Periode AMI
    </a>

    <a href="{{ route('penerapan.index',$jadwal->id_periode_ami) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('penerapan.index') }}">
        Penerapan Standar
    </a>

    <a href="{{ route('tim-ami.index',$jadwal->id_periode_ami) }}">
        Tim AMI
    </a>

    <a href="{{ route('jadwal.index',$jadwal->id_periode_ami) }}"
       class="active">
        Jadwal AMI
    </a>

</div>

<div class="form-container">

    <div class="form-card">

        <h3 class="form-title">
            Edit Jadwal AMI
        </h3>

        <form action="{{ route('jadwal.update',$jadwal->id) }}"
              method="POST">

            @csrf
            @method('PUT')

            <!-- Nama Kegiatan -->
            <div class="form-group">

                <label>
                    Nama Kegiatan
                </label>

                <input
                    type="text"
                    name="kegiatan"
                    value="{{ old('kegiatan',$jadwal->kegiatan) }}"
                    required>

            </div>

            <!-- Waktu -->
            <div class="form-group">

                <label>
                    Waktu
                </label>

                <input
                    type="text"
                    name="waktu"
                    value="{{ old('waktu',$jadwal->waktu) }}"
                    placeholder="Contoh : 08.00 - 09.00"
                    required>

            </div>

            <div class="form-action">

                <button
                    type="submit"
                    class="btn-save">

                    Simpan

                </button>

                <a href="{{ route('jadwal.index',$jadwal->id_periode_ami) }}"
                   class="btn-cancel">

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection



