@extends('layouts.auditee')

@section('content')

@php
    $penerapan = $temuan->penerapan;

    $standarPeriode =
        $penerapan?->standarmutuPeriode;

    $periodeAmi =
        $standarPeriode?->periodeAmi;

    $standarMutu =
        $standarPeriode?->standarMutu;

    $indikator =
        $penerapan?->indikator;

    $tanggapanPertama =
        $temuan->tanggapan->first();

    $statusTemuan = strtolower(
        trim((string) ($temuan->status_temuan ?? ''))
    );

    $jenisTemuan = strtolower(
        trim((string) ($temuan->jenis_temuan ?? ''))
    );

    $statusClass = match ($statusTemuan) {
        'open' => 'finding-status-open',
        'closed' => 'finding-status-closed',
        default => 'finding-status-default',
    };

    $jenisClass = match ($jenisTemuan) {
        'kts' => 'finding-type-kts',
        'ob' => 'finding-type-ob',
        'sesuai_standar' => 'finding-type-sesuai',
        default => 'finding-type-default',
    };

    $labelJenis = $temuan->label_jenis_temuan
        ?? match ($jenisTemuan) {
            'kts' => 'KTS',
            'ob' => 'OB',
            'sesuai_standar' => 'Sudah Sesuai Standar',
            default => ucfirst(
                str_replace('_', ' ', $jenisTemuan ?: '-')
            ),
        };
@endphp

<style>
    .finding-detail-page {
        width: 100%;
    }

    .finding-heading {
        margin-bottom: 24px;
    }

    .finding-breadcrumb {
        margin-bottom: 8px;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
    }

    .finding-heading h2 {
        margin: 0 0 8px;
        color: #0f172a;
        font-size: 30px;
        font-weight: 800;
        line-height: 1.2;
    }

    .finding-heading p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    .finding-card {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
    }

    .finding-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 24px 28px;
        border-bottom: 1px solid #e2e8f0;
    }

    .finding-card-header h3 {
        margin: 0 0 5px;
        color: #0f172a;
        font-size: 20px;
        font-weight: 800;
    }

    .finding-card-header p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    .finding-readonly-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        border: 1px solid #bfdbfe;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .finding-table-scroll {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
    }

    .finding-table-scroll::-webkit-scrollbar {
        height: 10px;
    }

    .finding-table-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .finding-table-scroll::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background: #cbd5e1;
    }

    .finding-table {
        width: 100%;
        min-width: 2450px;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .finding-table thead th {
        padding: 15px 14px;
        border-right: 1px solid #dbe3ec;
        border-bottom: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #1e293b;
        font-size: 12px;
        font-weight: 800;
        line-height: 1.4;
        text-align: left;
        text-transform: uppercase;
        vertical-align: middle;
    }

    .finding-table tbody td {
        padding: 17px 14px;
        border-right: 1px solid #e2e8f0;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        font-size: 13px;
        line-height: 1.6;
        vertical-align: top;
        overflow-wrap: anywhere;
    }

    .finding-table thead th:last-child,
    .finding-table tbody td:last-child {
        border-right: none;
    }

    .finding-table tbody tr:last-child td {
        border-bottom: none;
    }

    .finding-table tbody tr:hover {
        background: #fafcff;
    }

    .col-number {
        width: 60px;
        text-align: center !important;
    }

    .col-year {
        width: 95px;
    }

    .col-unit {
        width: 170px;
    }

    .col-standard {
        width: 240px;
    }

    .col-indicator {
        width: 250px;
    }

    .col-result {
        width: 280px;
    }

    .col-evidence {
        width: 150px;
    }

    .col-type {
        width: 160px;
    }

    .col-finding {
        width: 280px;
    }

    .col-status {
        width: 125px;
    }

    .col-response {
        width: 280px;
    }

    .col-root {
        width: 270px;
    }

    .col-action {
        width: 190px;
    }

    .number-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 13px;
        font-weight: 800;
    }

    .main-value {
        color: #1e293b;
        font-weight: 600;
    }

    .long-value {
        color: #334155;
        line-height: 1.7;
        white-space: pre-line;
    }

    .finding-type {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        line-height: 1.3;
        text-transform: uppercase;
    }

    .finding-type-kts {
        border: 1px solid #fecaca;
        background: #fef2f2;
        color: #b91c1c;
    }

    .finding-type-ob {
        border: 1px solid #fed7aa;
        background: #fff7ed;
        color: #c2410c;
    }

    .finding-type-sesuai {
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #15803d;
    }

    .finding-type-default {
        border: 1px solid #dbeafe;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .finding-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-width: 78px;
        padding: 7px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .finding-status-open {
        background: #dcfce7;
        color: #15803d;
    }

    .finding-status-closed {
        background: #e2e8f0;
        color: #475569;
    }

    .finding-status-default {
        background: #f1f5f9;
        color: #64748b;
    }

    .evidence-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 8px 12px;
        border: 1px solid #86efac;
        border-radius: 8px;
        background: #f0fdf4;
        color: #15803d;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        box-sizing: border-box;
        transition: 0.2s ease;
    }

    .evidence-button:hover {
        background: #dcfce7;
        color: #166534;
    }

    .information-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .information-card {
        padding: 10px 11px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #f8fafc;
    }

    .information-card strong {
        display: block;
        margin-bottom: 5px;
        color: #475569;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .information-card div {
        color: #334155;
        font-size: 12px;
        line-height: 1.6;
        white-space: pre-line;
    }

    .empty-value {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #94a3b8;
        font-size: 12px;
        font-style: italic;
    }

    .action-stack {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .action-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: 100%;
        min-height: 40px;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.2;
        text-align: center;
        text-decoration: none;
        box-sizing: border-box;
        transition: 0.2s ease;
    }

    .action-button-primary {
        border: 1px solid #4f46e5;
        background: #4f46e5;
        color: #ffffff;
    }

    .action-button-primary:hover {
        border-color: #4338ca;
        background: #4338ca;
        color: #ffffff;
    }

    .action-button-edit {
        border: 1px solid #facc15;
        background: #fefce8;
        color: #a16207;
    }

    .action-button-edit:hover {
        background: #fef9c3;
        color: #854d0e;
    }

    @media (max-width: 768px) {
        .finding-card-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .finding-heading h2 {
            font-size: 25px;
        }
    }
</style>

<div class="finding-detail-page">

    <div class="finding-heading">

        <div class="finding-breadcrumb">
            Dashboard / Standar Mutu / Detail Temuan Audit
        </div>

        <h2>
            Detail Temuan Audit
        </h2>

        <p>
            Seluruh data berikut merupakan data penerapan standar dan
            temuan yang telah dimasukkan oleh auditor.
        </p>

    </div>

    <div class="finding-card">

        <div class="finding-card-header">

            <div>

                <h3>
                    Data Pemeriksaan dan Tanggapan
                </h3>

                <p>
                    Geser tabel ke kanan untuk melihat seluruh informasi.
                </p>

            </div>

            <span class="finding-readonly-badge">

                <i class="bi bi-eye"></i>

                Temuan hanya dapat dilihat

            </span>

        </div>

        <div class="finding-table-scroll">

            <table class="finding-table">

                <thead>

                    <tr>

                        <th class="col-number">
                            No.
                        </th>

                        <th class="col-year">
                            Tahun AMI
                        </th>

                        <th class="col-unit">
                            Unit Kerja
                        </th>

                        <th class="col-standard">
                            Standar Mutu
                        </th>

                        <th class="col-indicator">
                            Penerapan Standar
                        </th>

                        <th class="col-result">
                            Hasil Penerapan Auditee
                        </th>

                        <th class="col-evidence">
                            Bukti Pendukung
                        </th>

                        <th class="col-type">
                            Jenis Temuan
                        </th>

                        <th class="col-finding">
                            Temuan Auditor
                        </th>

                        <th class="col-status">
                            Status Temuan
                        </th>

                        <th class="col-response">
                            Tanggapan Auditee
                        </th>

                        <th class="col-root">
                            Akar Masalah
                        </th>

                        <th class="col-action">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        {{-- NOMOR --}}
                        <td class="col-number">

                            <span class="number-badge">
                                1
                            </span>

                        </td>

                        {{-- TAHUN AMI --}}
                        <td>

                            <span class="main-value">
                                {{ $periodeAmi?->tahun ?? '-' }}
                            </span>

                        </td>

                        {{-- UNIT KERJA --}}
                        <td>

                            <span class="main-value">

                                {{
                                    $periodeAmi?->unitKerja?->nama
                                    ?? $periodeAmi?->unitKerja?->nama_unit_kerja
                                    ?? '-'
                                }}

                            </span>

                        </td>

                        {{-- STANDAR MUTU --}}
                        <td>

                            <span class="main-value">

                                {{
                                    $standarMutu?->nama_standar_mutu
                                    ?? $standarMutu?->nama_standar
                                    ?? $standarMutu?->nama
                                    ?? '-'
                                }}

                            </span>

                        </td>

                        {{-- PENERAPAN / INDIKATOR --}}
                        <td>

                            <div class="long-value">

                                {{
                                    $indikator?->deskripsi
                                    ?? $indikator?->indikator
                                    ?? '-'
                                }}

                            </div>

                        </td>

                        {{-- HASIL PENERAPAN AUDITEE --}}
                        <td>

                            <div class="long-value">
                                {{ $penerapan?->deskripsi_hasil ?? '-' }}
                            </div>

                        </td>

                        {{-- BUKTI PENDUKUNG --}}
                        <td>

                            @if(!empty($penerapan?->link_bukti))

                                <a
                                    href="{{ $penerapan->link_bukti }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="evidence-button"
                                >

                                    <i class="bi bi-box-arrow-up-right"></i>

                                    Lihat Bukti

                                </a>

                            @else

                                <span class="empty-value">

                                    <i class="bi bi-link-45deg"></i>

                                    Belum ada bukti

                                </span>

                            @endif

                        </td>

                        {{-- JENIS TEMUAN --}}
                        <td>

                            <span class="finding-type {{ $jenisClass }}">

                                <i class="bi bi-exclamation-diamond"></i>

                                {{ $labelJenis }}

                            </span>

                        </td>

                        {{-- TEMUAN AUDITOR --}}
                        <td>

                            <div class="long-value">
                                {{ $temuan->temuan ?? '-' }}
                            </div>

                        </td>

                        {{-- STATUS TEMUAN --}}
                        <td>

                            <span class="finding-status {{ $statusClass }}">

                                <i class="bi bi-circle-fill"></i>

                                {{ ucfirst($statusTemuan ?: '-') }}

                            </span>

                        </td>

                        {{-- TANGGAPAN AUDITEE --}}
                        <td>

                            @forelse($temuan->tanggapan as $tanggapan)

                                @if($loop->first)
                                    <div class="information-list">
                                @endif

                                    <div class="information-card">

                                        <strong>
                                            Tanggapan {{ $loop->iteration }}
                                        </strong>

                                        <div>
                                            {{ $tanggapan->tanggapan ?? '-' }}
                                        </div>

                                    </div>

                                @if($loop->last)
                                    </div>
                                @endif

                            @empty

                                <span class="empty-value">

                                    <i class="bi bi-chat-square-text"></i>

                                    Belum ada tanggapan

                                </span>

                            @endforelse

                        </td>

                        {{-- AKAR MASALAH --}}
                        <td>

                            @forelse($temuan->akarMasalah as $akar)

                                @if($loop->first)
                                    <div class="information-list">
                                @endif

                                    <div class="information-card">

                                        <strong>
                                            Akar Masalah {{ $loop->iteration }}
                                        </strong>

                                        <div>
                                            {{ $akar->akar_masalah ?? '-' }}
                                        </div>

                                    </div>

                                @if($loop->last)
                                    </div>
                                @endif

                            @empty

                                <span class="empty-value">

                                    <i class="bi bi-diagram-3"></i>

                                    Belum ada akar masalah

                                </span>

                            @endforelse

                        </td>

                        {{-- AKSI --}}
                        <td>

                            <div class="action-stack">

                                @if(!$tanggapanPertama)

                                    <a
                                        href="{{ route(
                                            'auditee.tanggapan.create',
                                            $temuan->id
                                        ) }}"
                                        class="action-button action-button-primary"
                                    >

                                        <i class="bi bi-chat-dots"></i>

                                        Beri Tanggapan

                                    </a>

                                @else

                                    <a
                                        href="{{ route(
                                            'auditee.tanggapan.edit',
                                            $tanggapanPertama->id
                                        ) }}"
                                        class="action-button action-button-edit"
                                    >

                                        <i class="bi bi-pencil-square"></i>

                                        Edit Tanggapan

                                    </a>

                                @endif

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection