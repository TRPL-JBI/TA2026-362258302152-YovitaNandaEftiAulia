@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">
    Dashboard / Audit AMI / Tim Audit / Detail
</div>

<div class="card auditee-detail-card">

    <div class="auditee-master-header">

        <div>
            <h2>
                Detail Tim Audit
            </h2>

            <p>
                Informasi auditor yang ditugaskan.
            </p>
        </div>

        <a
            href="{{ route('auditee.audit.tim.index') }}"
            class="auditee-back-button"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

    </div>

    <div class="auditee-detail-grid">

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Nama Auditor
            </span>

            <strong>
                {{ $tim->user->nama ?? '-' }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Email
            </span>

            <strong>
                {{ $tim->user->email ?? '-' }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Peran
            </span>

            <strong>
                {{ ucwords($tim->role ?? '-') }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Tahun AMI
            </span>

            <strong>
                {{ $tim->periode->tahun ?? '-' }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Unit Kerja Audit
            </span>

            <strong>
                {{
                    $tim->periode
                        ->unitKerja
                        ->nama
                    ?? '-'
                }}
            </strong>

        </div>

        <div class="auditee-detail-item">

            <span class="auditee-detail-label">
                Status Periode
            </span>

            <strong>
                {{
                    ucfirst(
                        $tim->periode->status ?? '-'
                    )
                }}
            </strong>

        </div>

        <div class="auditee-detail-item detail-full">

            <span class="auditee-detail-label">
                Standar Mutu
            </span>

            <strong>
                {{
                    $tim->periode
                        ->standarMutu
                        ->nama_standar_mutu
                    ?? '-'
                }}
            </strong>

        </div>

    </div>

</div>

@endsection
