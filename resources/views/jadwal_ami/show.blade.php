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

<div class="detail-card">

    <div class="detail-header">

        <div>

            <h2>Detail Jadwal AMI</h2>

            <p class="detail-subtitle">
                Informasi lengkap mengenai Jadwal AMI
            </p>

        </div>

        <a href="{{ route('jadwal.index',$jadwal->id_periode_ami) }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>
            Kembali

        </a>

    </div>

    <div class="detail-group">

        <label>Nama Kegiatan</label>

        <div class="detail-box">

            <i class="bi bi-calendar-event"></i>

            {{ $jadwal->kegiatan }}

        </div>

    </div>

    <div class="detail-group">

        <label>Waktu</label>

        <div class="detail-box">

            <i class="bi bi-clock"></i>

            {{ $jadwal->waktu }}

        </div>

    </div>

</div>

@endsection
