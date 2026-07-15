@extends('layouts.auditee')

@section('content')

@php
    $penerapan =
        $temuan->pertanyaan->penerapan ?? null;

    $standarPeriode =
        $penerapan->standarmutuPeriode ?? null;

    $periode =
        $standarPeriode->periodeAmi ?? null;
@endphp

<div class="breadcrumb">
    Dashboard / Audit AMI / Temuan Audit / Detail
</div>

<div class="card auditee-detail-card">

    <div class="auditee-master-header">

        <div>
            <h2>
                Detail Temuan Audit
            </h2>

            <p>
                Informasi temuan auditor untuk unit kerja Anda.
            </p>
        </div>

        <a
            href="{{ route('auditee.audit.temuan.index') }}"
            class="auditee-back-button"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

    </div>

    <div class="auditee-detail-grid">

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Tahun AMI
            </span>

            <strong>
                {{ $periode->tahun ?? '-' }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Unit Kerja
            </span>

            <strong>
                {{ $periode->unitKerja->nama ?? '-' }}
            </strong>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Standar Mutu
            </span>

            <strong>
                {{
                    $standarPeriode
                        ->standarMutu
                        ->nama_standar_mutu
                    ?? '-'
                }}
            </strong>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Indikator
            </span>

            <div class="auditee-detail-text">
                {{
                    $penerapan->indikator->deskripsi
                    ?? $penerapan->indikator->indikator
                    ?? '-'
                }}
            </div>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Pertanyaan Audit
            </span>

            <div class="auditee-detail-text">
                {{
                    $temuan->pertanyaan->pertanyaan
                    ?? '-'
                }}
            </div>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Temuan Auditor
            </span>

            <div class="auditee-detail-text">
                {{ $temuan->temuan ?? '-' }}
            </div>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Status Temuan
            </span>

            <strong>
                {{
                    ucfirst(
                        $temuan->status_temuan ?? '-'
                    )
                }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Auditor
            </span>

            <strong>
                {{
                    $temuan->pertanyaan->user->nama
                    ?? '-'
                }}
            </strong>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Hasil Penerapan Auditee
            </span>

            <div class="auditee-detail-text">
                {{
                    $penerapan->deskripsi_hasil
                    ?? '-'
                }}
            </div>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Bukti Pendukung
            </span>

            @if(!empty($penerapan->link_bukti))

                <a
                    href="{{ $penerapan->link_bukti }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="auditee-evidence-button"
                >
                    <i class="bi bi-box-arrow-up-right"></i>
                    Lihat Bukti Pendukung
                </a>

            @else

                <span class="auditee-empty-value">
                    Belum ada bukti pendukung.
                </span>

            @endif

        </div>

    </div>

</div>

@endsection