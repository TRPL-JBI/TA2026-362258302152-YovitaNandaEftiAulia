@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Isi Standar / Indikator / Detail
</h3>

<div class="detail-card">

    <div class="detail-header">

        <div>

            <h2>Detail Indikator</h2>

            <p class="detail-subtitle">

                Informasi lengkap Indikator Standar

            </p>

        </div>

        <a href="{{ route('auditor.indikator.index',$indikator->id_isi_standar_mutu) }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <div class="detail-group">

        <label>Deskripsi Indikator</label>

        <div class="detail-box">

            <i class="bi bi-list-check"></i>

            {{ $indikator->deskripsi }}

        </div>

    </div>

</div>

@endsection