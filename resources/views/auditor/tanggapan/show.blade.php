@extends('layouts.auditor')

@section('content')

@php
    $temuan = $tanggapan->temuan;
    $penerapan = $temuan?->penerapan;
    $indikator = $penerapan?->indikator;

    $jenisTemuan = strtolower(
        trim(
            (string) (
                $temuan?->jenis_temuan
                ?? ''
            )
        )
    );

    $labelJenisTemuan = match ($jenisTemuan) {
        'sesuai_standar' => 'Sesuai Standar',
        'kts' => 'KTS',
        'ob' => 'OB',
        default => '-',
    };

    $statusTemuan = strtolower(
        trim(
            (string) (
                $temuan?->status_temuan
                ?? ''
            )
        )
    );
@endphp

<style>
    .tanggapan-detail-page {
        width: 100%;
        padding: 28px 24px 40px;
    }

    .tanggapan-breadcrumb {
        margin-bottom: 22px;
        color: #1e293b;
        font-size: 14px;
        font-weight: 700;
    }

    .tanggapan-detail-card {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        background: #ffffff;
        box-shadow: 0 12px 35px rgba(15, 23, 42, 0.06);
    }

    .tanggapan-detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 28px 34px;
        border-bottom: 1px solid #e2e8f0;
    }

    .tanggapan-detail-header h2 {
        margin: 0 0 7px;
        color: #0f172a;
        font-size: 25px;
        font-weight: 800;
    }

    .tanggapan-detail-header p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .tanggapan-back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 48px;
        padding: 11px 22px;
        border-radius: 12px;
        background: #4f46e5;
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        white-space: nowrap;
        transition: 0.2s ease;
    }

    .tanggapan-back-button:hover {
        background: #4338ca;
        color: #ffffff;
        transform: translateY(-1px);
    }

    .tanggapan-table-wrapper {
        width: 100%;
        padding: 25px 32px 32px;
        overflow-x: auto;
    }

    .tanggapan-horizontal-table {
        width: 100%;
        min-width: 1450px;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #dbe2ea;
        border-radius: 14px;
        overflow: hidden;
    }

    .tanggapan-horizontal-table thead th {
        padding: 16px 18px;
        border-right: 1px solid #dbe2ea;
        border-bottom: 1px solid #dbe2ea;
        background: #eef2ff;
        color: #1e293b;
        font-size: 13px;
        font-weight: 800;
        text-align: center;
        white-space: nowrap;
    }

    .tanggapan-horizontal-table thead th:last-child {
        border-right: 0;
    }

    .tanggapan-horizontal-table tbody td {
        min-height: 100px;
        padding: 22px 18px;
        border-right: 1px solid #e2e8f0;
        background: #ffffff;
        color: #1e293b;
        font-size: 14px;
        line-height: 1.65;
        text-align: center;
        vertical-align: middle;
    }

    .tanggapan-horizontal-table tbody td:last-child {
        border-right: 0;
    }

    .column-type {
        width: 180px;
    }

    .column-indicator {
        width: 280px;
    }

    .column-finding {
        width: 300px;
    }

    .column-response {
        width: 300px;
    }

    .column-user {
        width: 170px;
    }

    .column-status {
        width: 150px;
    }

    .tanggapan-text {
        text-align: left;
        overflow-wrap: anywhere;
    }

    .tanggapan-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 8px 13px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .badge-sesuai {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-kts {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-ob {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-default {
        background: #e2e8f0;
        color: #475569;
    }

    .badge-open {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-closed {
        background: #dcfce7;
        color: #15803d;
    }

    .tanggapan-scroll-info {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 14px;
        padding: 8px 12px;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 12px;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .tanggapan-detail-page {
            padding: 18px;
        }

        .tanggapan-detail-header {
            align-items: stretch;
            flex-direction: column;
            padding: 22px;
        }

        .tanggapan-back-button {
            width: 100%;
        }

        .tanggapan-table-wrapper {
            padding: 20px;
        }
    }
</style>

<div class="tanggapan-detail-page">

    {{-- BREADCRUMB --}}

    <div class="tanggapan-breadcrumb">
        Dashboard /
        Audit Mutu Internal /
        Tanggapan Auditee /
        Detail
    </div>

    {{-- CARD UTAMA --}}

    <section class="tanggapan-detail-card">

        {{-- HEADER --}}

        <div class="tanggapan-detail-header">

            <div>

                <h2>
                    Detail Tanggapan Auditee
                </h2>

                <p>
                    Informasi tanggapan yang diberikan Auditee
                    terhadap hasil Penilaian Audit.
                </p>

            </div>

            <a
                href="{{ route('auditor.temuan.index') }}"
                class="tanggapan-back-button"
            >
                <i class="bi bi-arrow-left"></i>

                Kembali
            </a>

        </div>

        {{-- TABEL PANJANG --}}

        <div class="tanggapan-table-wrapper">

            <div class="tanggapan-scroll-info">

                <i class="bi bi-arrow-left-right"></i>

                Geser tabel ke kanan untuk melihat seluruh data

            </div>

            <table class="tanggapan-horizontal-table">

                <thead>

                    <tr>

                        <th class="column-type">
                            Jenis Penilaian
                        </th>

                        <th class="column-indicator">
                            Indikator
                        </th>

                        <th class="column-finding">
                            Temuan Audit
                        </th>

                        <th class="column-response">
                            Tanggapan Auditee
                        </th>

                        <th class="column-user">
                            Nama Auditee
                        </th>

                        <th class="column-status">
                            Status Temuan
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        {{-- JENIS PENILAIAN --}}

                        <td>

                            @if($jenisTemuan === 'sesuai_standar')

                                <span class="tanggapan-badge badge-sesuai">

                                    <i class="bi bi-check-circle-fill"></i>

                                    {{ $labelJenisTemuan }}

                                </span>

                            @elseif($jenisTemuan === 'kts')

                                <span class="tanggapan-badge badge-kts">

                                    <i class="bi bi-exclamation-circle-fill"></i>

                                    {{ $labelJenisTemuan }}

                                </span>

                            @elseif($jenisTemuan === 'ob')

                                <span class="tanggapan-badge badge-ob">

                                    <i class="bi bi-info-circle-fill"></i>

                                    {{ $labelJenisTemuan }}

                                </span>

                            @else

                                <span class="tanggapan-badge badge-default">
                                    -
                                </span>

                            @endif

                        </td>

                        {{-- INDIKATOR --}}

                        <td>

                            <div class="tanggapan-text">

                                {{
                                    trim(
                                        (string) (
                                            $indikator?->deskripsi
                                            ?? ''
                                        )
                                    ) !== ''
                                        ? $indikator->deskripsi
                                        : '-'
                                }}

                            </div>

                        </td>

                        {{-- TEMUAN AUDIT --}}

                        <td>

                            <div class="tanggapan-text">

                                @if(
                                    trim(
                                        (string) (
                                            $temuan?->temuan
                                            ?? ''
                                        )
                                    ) !== ''
                                )

                                    {!!
                                        nl2br(
                                            e(
                                                $temuan->temuan
                                            )
                                        )
                                    !!}

                                @elseif($jenisTemuan === 'sesuai_standar')

                                    Penerapan telah dinilai sesuai
                                    dengan standar yang ditetapkan.

                                @else

                                    -

                                @endif

                            </div>

                        </td>

                        {{-- TANGGAPAN AUDITEE --}}

                        <td>

                            <div class="tanggapan-text">

                                @if(
                                    trim(
                                        (string) (
                                            $tanggapan->tanggapan
                                            ?? ''
                                        )
                                    ) !== ''
                                )

                                    {!!
                                        nl2br(
                                            e(
                                                $tanggapan->tanggapan
                                            )
                                        )
                                    !!}

                                @else

                                    -

                                @endif

                            </div>

                        </td>

                        {{-- NAMA AUDITEE --}}

                        <td>

                            <strong>

                                {{
                                    trim(
                                        (string) (
                                            $tanggapan->user?->nama
                                            ?? ''
                                        )
                                    ) !== ''
                                        ? $tanggapan->user->nama
                                        : '-'
                                }}

                            </strong>

                        </td>

                        {{-- STATUS TEMUAN --}}

                        <td>

                            @if($statusTemuan === 'open')

                                <span class="tanggapan-badge badge-open">

                                    <i class="bi bi-exclamation-circle-fill"></i>

                                    Open

                                </span>

                            @elseif($statusTemuan === 'closed')

                                <span class="tanggapan-badge badge-closed">

                                    <i class="bi bi-check-circle-fill"></i>

                                    Closed

                                </span>

                            @else

                                <span class="tanggapan-badge badge-default">
                                    -
                                </span>

                            @endif

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection