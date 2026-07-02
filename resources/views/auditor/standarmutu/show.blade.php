@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Detail
</h3>

<div class="detail-card">

    <div class="detail-header">

        <div>

            <h2>Detail Standar Mutu</h2>

            <p class="detail-subtitle">
                Informasi lengkap mengenai Standar Mutu
            </p>

        </div>

        <a href="{{ route('auditor.standarmutu.index') }}"
             class="btn-back">

             <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <div class="detail-body">

        <div class="detail-item">

            <label>Nama Standar Mutu</label>

            <div class="detail-value">

                <i class="bi bi-journal-text"></i>

                <span>

                    {{ $standar->nama_standar_mutu }}

                </span>

            </div>

        </div>

    </div>

</div>

@endsection