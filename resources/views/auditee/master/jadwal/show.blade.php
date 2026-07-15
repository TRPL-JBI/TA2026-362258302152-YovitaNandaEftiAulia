@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">
    Dashboard / Audit AMI / Jadwal Audit / Detail
</div>

<div class="card auditee-detail-card">

    <div class="auditee-master-header">

        <div>
            <h2>
                Detail Jadwal Audit
            </h2>

            <p>
                Informasi lengkap jadwal Audit Mutu Internal.
            </p>
        </div>

        <a
            href="{{ route('auditee.audit.jadwal.index') }}"
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
                {{ $jadwal->periode->tahun ?? '-' }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Status Periode
            </span>

            <strong>
                {{
                    ucfirst(
                        $jadwal->periode->status ?? '-'
                    )
                }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Unit Kerja
            </span>

            <strong>
                {{
                    $jadwal->periode
                        ->unitKerja
                        ->nama
                    ?? '-'
                }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Waktu Kegiatan
            </span>

            <strong>
                {{ $jadwal->waktu ?? '-' }}
            </strong>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Standar Mutu
            </span>

            <strong>
                {{
                    $jadwal->periode
                        ->standarMutu
                        ->nama_standar_mutu
                    ?? '-'
                }}
            </strong>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Kegiatan Audit
            </span>

            <div class="auditee-detail-text">
                {{ $jadwal->kegiatan ?? '-' }}
            </div>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Tanggal Buka AMI
            </span>

            <strong>
                {{
                    $jadwal->periode->tanggal_buka_ami
                    ?? '-'
                }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Tanggal Tutup AMI
            </span>

            <strong>
                {{
                    $jadwal->periode->tanggal_tutup_ami
                    ?? '-'
                }}
            </strong>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Tujuan Audit
            </span>

            <div class="auditee-detail-text">
                {{
                    $jadwal->periode->tujuan_audit
                    ?? '-'
                }}
            </div>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Lingkup Audit
            </span>

            <div class="auditee-detail-text">
                {{
                    $jadwal->periode->lingkup_audit
                    ?? '-'
                }}
            </div>

        </div>

    </div>

</div>

@endsection