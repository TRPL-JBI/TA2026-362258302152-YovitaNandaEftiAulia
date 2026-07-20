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

    <a href="{{ route('pertanyaan.index',$jadwal->id_periode_ami) }}">
        Pertanyaan AMI
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
            Hapus Jadwal AMI
        </h3>

        <p style="text-align:center; margin:25px 0; font-size:16px;">

            Apakah Anda yakin ingin menghapus jadwal berikut?

        </p>

        <table class="detail-table">

            <tr>
                <th>Nama Kegiatan</th>
                <td>{{ $jadwal->kegiatan }}</td>
            </tr>

            <tr>
                <th>Waktu</th>
                <td>{{ $jadwal->waktu }}</td>
            </tr>

        </table>

        <form action="{{ route('jadwal.destroy',$jadwal->id) }}"
              method="POST">

            @csrf
            @method('DELETE')

            <div class="form-action">

                <button
                    type="submit"
                    class="btn-delete">

                    Hapus

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
