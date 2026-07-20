@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Indikator / Detail
</h3>

<div class="detail-card">

    <div class="detail-header">

        <h2>Detail Indikator</h2>

        <a href="{{ route('indikator.index', $indikator->id_isi_standar_mutu) }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>
            Kembali

        </a>

    </div>

    <div class="detail-body">

        <div class="detail-item">

            <label>Isi Standar</label>

            <div class="detail-value">
                {{ $indikator->isiStandar->nama_standar }}
            </div>

        </div>

        <div class="detail-item">

            <label>Deskripsi Indikator</label>

            <div class="detail-value">

                {{ $indikator->deskripsi }}

            </div>

        </div>

    </div>

</div>

@endsection
