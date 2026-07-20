@extends('layouts.app')

@section('content')

<div class="admin-application-page">

    {{-- Breadcrumb --}}
    <div class="application-breadcrumb">
        Dashboard / Periode AMI / Penerapan Standar
    </div>

    {{-- Tab menu --}}
    <div class="period-tab-menu">

        <a
            href="{{ route('periode-ami.show', $periode->id) }}"
            class="period-tab-link"
        >
            Detail Periode AMI
        </a>

        <a
            href="{{ route('penerapan.index', $periode->id) }}"
            class="period-tab-link active"
        >
            Penerapan Standar
        </a>

        <a
            href="{{ route('tim-ami.index', $periode->id) }}"
            class="period-tab-link"
        >
            Tim AMI
        </a>

        <a
            href="{{ route('jadwal.index', $periode->id) }}"
            class="period-tab-link"
        >
            Jadwal AMI
        </a>

    </div>

    {{-- Judul halaman --}}
    <div class="application-page-heading">

        <div>

            <span class="application-heading-label">
                AUDIT MUTU INTERNAL
            </span>

            <h2>
                Penerapan Standar
            </h2>

            <p>
                Menampilkan seluruh hasil penerapan standar yang
                telah dikirim oleh auditee pada periode AMI ini.
                Halaman ini hanya dapat digunakan untuk melihat data.
            </p>

        </div>

        <div class="application-period-badge">

            <i class="bi bi-calendar-check"></i>

            Periode {{ $periode->tahun ?? '-' }}

        </div>

    </div>

    {{-- Informasi periode --}}
    <div class="application-period-card">

        <div class="period-information-item">

            <span>
                Unit Kerja
            </span>

            <strong>
                {{
                    $periode->unitKerja->nama
                    ?? $periode->unitKerja->nama_unit_kerja
                    ?? '-'
                }}
            </strong>

        </div>

        <div class="period-information-item">

            <span>
                Standar Mutu
            </span>

            <strong>
                {{
                    $periode->standarMutu->nama_standar_mutu
                    ?? $periode->standarMutu->nama
                    ?? '-'
                }}
            </strong>

        </div>

        <div class="period-information-item">

            <span>
                Status Periode
            </span>

            @php
                $statusPeriode = strtolower(
                    trim((string) $periode->status)
                );
            @endphp

            @if($statusPeriode === 'berjalan')

                <span class="application-status application-status-running">
                    <i class="bi bi-play-circle-fill"></i>
                    Berjalan
                </span>

            @elseif($statusPeriode === 'ditutup')

                <span class="application-status application-status-closed">
                    <i class="bi bi-check-circle-fill"></i>
                    Ditutup
                </span>

            @else

                <span class="application-status application-status-draft">
                    <i class="bi bi-file-earmark"></i>
                    {{ ucfirst($periode->status ?? 'Draft') }}
                </span>

            @endif

        </div>

    </div>

    {{-- Ringkasan --}}
    <div class="application-summary-grid">

        <div class="application-summary-card">

            <div class="summary-icon summary-icon-blue">
                <i class="bi bi-clipboard-check"></i>
            </div>

            <div>

                <span>
                    Penerapan
                </span>

                <strong>
                    {{ $jumlahPenerapan }}
                </strong>

            </div>

        </div>

        <div class="application-summary-card">

            <div class="summary-icon summary-icon-green">
                <i class="bi bi-file-earmark-check"></i>
            </div>

            <div>

                <span>
                    Bukti Tersedia
                </span>

                <strong>
                    {{ $jumlahBukti }}
                </strong>

            </div>

        </div>

        <div class="application-summary-card">

            <div class="summary-icon summary-icon-purple">
                <i class="bi bi-people"></i>
            </div>

            <div>

                <span>
                    Auditee
                </span>

                <strong>
                    {{ $jumlahAuditee }}
                </strong>

            </div>

        </div>

        <div class="application-summary-card">

            <div class="summary-icon summary-icon-orange">
                <i class="bi bi-exclamation-circle"></i>
            </div>

            <div>

                <span>
                    Temuan Audit
                </span>

                <strong>
                    {{ $jumlahTemuan }}
                </strong>

            </div>

        </div>

    </div>

    {{-- Informasi read-only --}}
    <div class="application-information-alert">

        <i class="bi bi-info-circle-fill"></i>

        <div>

            <strong>
                Tampilan hanya-baca
            </strong>

            <span>
                Data penerapan dibuat oleh auditee. Pada halaman ini
                data tidak dapat ditambah, diedit, atau dihapus.
            </span>

        </div>

    </div>

    {{-- Tabel penerapan --}}
    <div class="application-table-card">

        <div class="application-table-header">

            <div>

                <h3>
                    Data Penerapan Auditee
                </h3>

                <p>
                    Hasil penerapan, bukti pendukung, dan informasi
                    auditee pada periode yang dipilih.
                </p>

            </div>

            <div class="horizontal-scroll-hint">

                <i class="bi bi-arrow-left-right"></i>

                Dapat digulir horizontal

            </div>

        </div>

        <div class="application-table-scroll">

            <table class="application-table">

                <thead>

                    <tr>

                        <th class="column-number">
                            No.
                        </th>

                        <th class="column-standard">
                            Standar Mutu
                        </th>

                        <th class="column-indicator">
                            Indikator Standar
                        </th>

                        <th class="column-result">
                            Hasil Penerapan Auditee
                        </th>

                        <th class="column-evidence">
                            Bukti Pendukung
                        </th>

                        <th class="column-auditee">
                            Auditee
                        </th>

                        <th class="column-finding">
                            Temuan
                        </th>

                        <th class="column-action">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $item)

                        @php
                            $jumlahTemuanItem =
                                $item->temuan->count();

                            $namaStandar =
                                $item
                                    ->standarMutuPeriodeAmi
                                    ->standarMutu
                                    ->nama_standar_mutu
                                ?? $item
                                    ->standarMutuPeriodeAmi
                                    ->standarMutu
                                    ->nama
                                ?? '-';

                            $namaIndikator =
                                $item->indikator->deskripsi
                                ?? $item->indikator->indikator
                                ?? '-';

                            $namaAuditee =
                                $item->user->nama
                                ?? $item->user->name
                                ?? '-';
                        @endphp

                        <tr>

                            <td class="cell-number">

                                <span class="application-row-number">
                                    {{ $loop->iteration }}
                                </span>

                            </td>

                            <td>

                                <div class="application-standard-box">

                                    <div class="standard-box-icon">
                                        <i class="bi bi-journal-check"></i>
                                    </div>

                                    <span>
                                        {{ $namaStandar }}
                                    </span>

                                </div>

                            </td>

                            <td>

                                <div class="application-indicator-box">

                                    <span class="indicator-label">
                                        Indikator
                                    </span>

                                    <p>
                                        {{ $namaIndikator }}
                                    </p>

                                </div>

                            </td>

                            <td>

                                <div class="application-result-box">

                                    <div class="result-box-heading">

                                        <i class="bi bi-check2-square"></i>

                                        Hasil Penerapan

                                    </div>

                                    <p>
                                        {{
                                            $item->deskripsi_hasil
                                            ?? '-'
                                        }}
                                    </p>

                                </div>

                            </td>

                            <td class="cell-center">

                                @if(filled($item->link_bukti))

                                    <a
                                        href="{{ $item->link_bukti }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="application-evidence-button"
                                    >

                                        <i class="bi bi-box-arrow-up-right"></i>

                                        Lihat Bukti

                                    </a>

                                @else

                                    <span class="application-empty-evidence">

                                        <i class="bi bi-file-earmark-x"></i>

                                        Belum tersedia

                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="application-user-box">

                                    <div class="application-user-avatar">
                                        <i class="bi bi-person"></i>
                                    </div>

                                    <div>

                                        <strong>
                                            {{ $namaAuditee }}
                                        </strong>

                                        <span>
                                            Auditee
                                        </span>

                                    </div>

                                </div>

                            </td>

                            <td class="cell-center">

                                @if($jumlahTemuanItem > 0)

                                    <span class="application-finding-status has-finding">

                                        <i class="bi bi-exclamation-circle-fill"></i>

                                        {{ $jumlahTemuanItem }} Temuan

                                    </span>

                                @else

                                    <span class="application-finding-status no-finding">

                                        <i class="bi bi-check-circle-fill"></i>

                                        Belum ada temuan

                                    </span>

                                @endif

                            </td>

                            <td class="cell-center">

                                <a
                                    href="{{ route(
                                        'penerapan.show',
                                        [
                                            $periode->id,
                                            $item->id
                                        ]
                                    ) }}"
                                    class="application-detail-button"
                                >

                                    <i class="bi bi-eye"></i>

                                    Lihat Detail

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="application-empty-state"
                            >

                                <i class="bi bi-inbox"></i>

                                <strong>
                                    Belum Ada Penerapan Standar
                                </strong>

                                <span>
                                    Auditee belum mengirim hasil penerapan
                                    standar untuk periode AMI ini.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<style>

    .admin-application-page {
        width: 100%;
    }

    .application-breadcrumb {
        margin-bottom: 20px;
        color: #172554;
        font-size: 13px;
        font-weight: 700;
    }

    /*
    |--------------------------------------------------------------------------
    | TAB
    |--------------------------------------------------------------------------
    */

    .period-tab-menu {
        display: flex;
        align-items: center;
        gap: 30px;
        overflow-x: auto;
        margin-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    .period-tab-link {
        display: inline-flex;
        align-items: center;
        min-height: 46px;
        padding: 0 0 11px;
        border-bottom: 3px solid transparent;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: 0.2s ease;
    }

    .period-tab-link:hover {
        color: #4f46e5;
    }

    .period-tab-link.active {
        border-bottom-color: #4f46e5;
        color: #4f46e5;
        font-weight: 800;
    }

    /*
    |--------------------------------------------------------------------------
    | JUDUL
    |--------------------------------------------------------------------------
    */

    .application-page-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 20px;
        padding: 23px 25px;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
    }

    .application-heading-label {
        display: block;
        margin-bottom: 7px;
        color: #6366f1;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.12em;
    }

    .application-page-heading h2 {
        margin: 0 0 8px;
        color: #172554;
        font-size: 27px;
    }

    .application-page-heading p {
        max-width: 760px;
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.65;
    }

    .application-period-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        padding: 10px 13px;
        border: 1px solid #c7d2fe;
        border-radius: 999px;
        background: #eef2ff;
        color: #4338ca;
        font-size: 12px;
        font-weight: 800;
    }

    /*
    |--------------------------------------------------------------------------
    | INFORMASI PERIODE
    |--------------------------------------------------------------------------
    */

    .application-period-card {
        display: grid;
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
        gap: 1px;
        overflow: hidden;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #e2e8f0;
    }

    .period-information-item {
        min-height: 90px;
        padding: 17px 19px;
        background: #ffffff;
    }

    .period-information-item > span:first-child {
        display: block;
        margin-bottom: 7px;
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .period-information-item strong {
        display: block;
        color: #1e293b;
        font-size: 13px;
        line-height: 1.55;
    }

    .application-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
    }

    .application-status-running {
        background: #fef3c7;
        color: #b45309;
    }

    .application-status-closed {
        background: #dcfce7;
        color: #15803d;
    }

    .application-status-draft {
        background: #f1f5f9;
        color: #64748b;
    }

    /*
    |--------------------------------------------------------------------------
    | RINGKASAN
    |--------------------------------------------------------------------------
    */

    .application-summary-grid {
        display: grid;
        grid-template-columns:
            repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .application-summary-card {
        display: flex;
        align-items: center;
        gap: 13px;
        min-height: 86px;
        padding: 15px 17px;
        border: 1px solid #e2e8f0;
        border-radius: 13px;
        background: #ffffff;
        box-shadow: 0 5px 18px rgba(15, 23, 42, 0.04);
    }

    .summary-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 43px;
        min-width: 43px;
        height: 43px;
        border-radius: 11px;
        font-size: 19px;
    }

    .summary-icon-blue {
        background: #dbeafe;
        color: #2563eb;
    }

    .summary-icon-green {
        background: #dcfce7;
        color: #16a34a;
    }

    .summary-icon-purple {
        background: #ede9fe;
        color: #7c3aed;
    }

    .summary-icon-orange {
        background: #ffedd5;
        color: #ea580c;
    }

    .application-summary-card span {
        display: block;
        margin-bottom: 3px;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
    }

    .application-summary-card strong {
        color: #172554;
        font-size: 22px;
    }

    /*
    |--------------------------------------------------------------------------
    | INFORMASI READ-ONLY
    |--------------------------------------------------------------------------
    */

    .application-information-alert {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-bottom: 18px;
        padding: 14px 17px;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        background: #eff6ff;
        color: #1e40af;
    }

    .application-information-alert > i {
        margin-top: 2px;
        font-size: 17px;
    }

    .application-information-alert strong {
        display: block;
        margin-bottom: 3px;
        font-size: 13px;
    }

    .application-information-alert span {
        color: #3b5f91;
        font-size: 12px;
        line-height: 1.55;
    }

    /*
    |--------------------------------------------------------------------------
    | TABEL
    |--------------------------------------------------------------------------
    */

    .application-table-card {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
    }

    .application-table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 20px 22px;
        border-bottom: 1px solid #e2e8f0;
    }

    .application-table-header h3 {
        margin: 0 0 5px;
        color: #172554;
        font-size: 17px;
    }

    .application-table-header p {
        margin: 0;
        color: #64748b;
        font-size: 12px;
    }

    .horizontal-scroll-hint {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        flex-shrink: 0;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
    }

    .application-table-scroll {
        overflow-x: auto;
    }

    .application-table {
        width: 100%;
        min-width: 1450px;
        border-collapse: collapse;
    }

    .application-table th {
        padding: 14px 13px;
        border-bottom: 1px solid #cbd5e1;
        background: #eaf0ff;
        color: #172554;
        font-size: 11px;
        font-weight: 800;
        text-align: left;
        vertical-align: middle;
    }

    .application-table td {
        padding: 14px 13px;
        border-bottom: 1px solid #edf1f6;
        color: #334155;
        font-size: 12px;
        line-height: 1.55;
        vertical-align: top;
    }

    .application-table tbody tr:hover {
        background: #fbfdff;
    }

    .application-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .column-number {
        width: 65px;
        text-align: center !important;
    }

    .column-standard {
        min-width: 240px;
    }

    .column-indicator {
        min-width: 280px;
    }

    .column-result {
        min-width: 300px;
    }

    .column-evidence {
        min-width: 145px;
        text-align: center !important;
    }

    .column-auditee {
        min-width: 175px;
    }

    .column-finding {
        min-width: 150px;
        text-align: center !important;
    }

    .column-action {
        min-width: 145px;
        text-align: center !important;
    }

    .cell-number,
    .cell-center {
        text-align: center;
        vertical-align: middle !important;
    }

    .application-row-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 29px;
        height: 29px;
        border-radius: 50%;
        background: #eef2ff;
        color: #4338ca;
        font-size: 11px;
        font-weight: 800;
    }

    .application-standard-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .standard-box-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 31px;
        min-width: 31px;
        height: 31px;
        border-radius: 8px;
        background: #eef2ff;
        color: #4f46e5;
    }

    .application-standard-box span {
        color: #1e293b;
        font-weight: 700;
        line-height: 1.55;
    }

    .application-indicator-box {
        padding: 11px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #f8fafc;
    }

    .indicator-label {
        display: block;
        margin-bottom: 4px;
        color: #6366f1;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.07em;
        text-transform: uppercase;
    }

    .application-indicator-box p {
        margin: 0;
        color: #334155;
    }

    .application-result-box {
        padding: 11px 12px;
        border: 1px solid #dbe3ee;
        border-radius: 9px;
        background: #ffffff;
    }

    .result-box-heading {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
        color: #15803d;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .application-result-box p {
        margin: 0;
        color: #334155;
    }

    .application-evidence-button,
    .application-detail-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 37px;
        padding: 9px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .application-evidence-button {
        border: 1px solid #bfdbfe;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .application-evidence-button:hover {
        border-color: #93c5fd;
        background: #dbeafe;
        color: #1e40af;
    }

    .application-detail-button {
        background: #4f46e5;
        color: #ffffff;
    }

    .application-detail-button:hover {
        background: #4338ca;
        color: #ffffff;
    }

    .application-empty-evidence {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #94a3b8;
        font-size: 11px;
    }

    .application-user-box {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .application-user-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        min-width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #ede9fe;
        color: #7c3aed;
        font-size: 16px;
    }

    .application-user-box strong {
        display: block;
        margin-bottom: 2px;
        color: #1e293b;
        font-size: 12px;
    }

    .application-user-box span {
        color: #94a3b8;
        font-size: 10px;
    }

    .application-finding-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        white-space: nowrap;
    }

    .application-finding-status.has-finding {
        background: #ffedd5;
        color: #c2410c;
    }

    .application-finding-status.no-finding {
        background: #dcfce7;
        color: #15803d;
    }

    .application-empty-state {
        padding: 65px 20px !important;
        text-align: center;
    }

    .application-empty-state i {
        display: block;
        margin-bottom: 11px;
        color: #94a3b8;
        font-size: 36px;
    }

    .application-empty-state strong {
        display: block;
        margin-bottom: 5px;
        color: #334155;
        font-size: 15px;
    }

    .application-empty-state span {
        color: #94a3b8;
        font-size: 12px;
    }

    @media (max-width: 1050px) {

        .application-summary-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .application-period-card {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 700px) {

        .application-page-heading,
        .application-table-header {
            align-items: stretch;
            flex-direction: column;
        }

        .application-summary-grid {
            grid-template-columns: 1fr;
        }

        .period-tab-menu {
            gap: 20px;
        }

    }

</style>

@endsection