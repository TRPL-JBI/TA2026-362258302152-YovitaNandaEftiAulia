@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Isi Standar / Detail
</h3>

<div class="detail-card">

    <div class="detail-header">

        <div>

            <h2>Detail Isi Standar</h2>

            <p class="detail-subtitle">
                Informasi lengkap mengenai Isi Standar
            </p>

        </div>

        <a href="{{ route('isi.index',$isi->id_standar_mutu) }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <div class="detail-body">

        <div class="detail-item">

            <label>Standar Mutu</label>

            <div class="detail-value">

                <i class="bi bi-journal-bookmark"></i>

                {{ $isi->standarMutu->nama_standar_mutu }}

            </div>

        </div>

        <div class="detail-item">

            <label>Nama Isi Standar</label>

            <div class="detail-value">

                <i class="bi bi-file-earmark-text"></i>

                {{ $isi->nama_standar }}

            </div>

        </div>

        <div class="detail-item">

            <label>Jumlah Indikator</label>

            <div class="detail-value">

                <i class="bi bi-list-check"></i>

                {{ $isi->indikator->count() }} Indikator

            </div>

        </div>

    </div>

</div>

@endsection