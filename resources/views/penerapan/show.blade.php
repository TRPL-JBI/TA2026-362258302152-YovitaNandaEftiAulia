@extends('layouts.app')

@section('content')

<div class="application-detail-page">

    <div class="application-breadcrumb">
        Dashboard / Periode AMI / Penerapan Standar / Detail
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

    <div class="detail-page-heading">

        <div>

            <span>
                DETAIL PENERAPAN AUDITEE
            </span>

            <h2>
                Detail Penerapan Standar
            </h2>

            <p>
                Informasi lengkap hasil penerapan standar dan bukti
                yang telah dikirim oleh auditee.
            </p>

        </div>

        <a
            href="{{ route('penerapan.index', $periode->id) }}"
            class="detail-back-button"
        >

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <div class="detail-information-alert">

        <i class="bi bi-eye-fill"></i>

        <span>
            Halaman ini hanya menampilkan data. Penerapan standar
            tidak dapat diedit atau dihapus dari halaman admin.
        </span>

    </div>

    <div class="application-detail-card">

        <div class="detail-card-header">

            <div class="detail-header-icon">
                <i class="bi bi-clipboard-check"></i>
            </div>

            <div>

                <h3>
                    Informasi Penerapan
                </h3>

                <p>
                    Data penerapan standar pada Periode AMI
                    {{ $periode->tahun ?? '-' }}.
                </p>

            </div>

        </div>

        <div class="detail-content">

            <div class="detail-information-grid">

                <div class="detail-information-item">

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

                <div class="detail-information-item">

                    <span>
                        Periode AMI
                    </span>

                    <strong>
                        {{ $periode->tahun ?? '-' }}
                    </strong>

                </div>

                <div class="detail-information-item">

                    <span>
                        Auditee
                    </span>

                    <strong>
                        {{
                            $data->user->nama
                            ?? $data->user->name
                            ?? '-'
                        }}
                    </strong>

                </div>

                <div class="detail-information-item">

                    <span>
                        Jumlah Temuan
                    </span>

                    <strong>
                        {{ $data->temuan->count() }} temuan
                    </strong>

                </div>

            </div>

            <div class="detail-section">

                <div class="detail-section-title">

                    <i class="bi bi-journal-text"></i>

                    Standar Mutu

                </div>

                <div class="detail-value-box">

                    {{
                        $data
                            ->standarMutuPeriodeAmi
                            ->standarMutu
                            ->nama_standar_mutu
                        ?? $data
                            ->standarMutuPeriodeAmi
                            ->standarMutu
                            ->nama
                        ?? '-'
                    }}

                </div>

            </div>

            <div class="detail-section">

                <div class="detail-section-title">

                    <i class="bi bi-list-check"></i>

                    Indikator Standar

                </div>

                <div class="detail-value-box">

                    {{
                        $data->indikator->deskripsi
                        ?? $data->indikator->indikator
                        ?? '-'
                    }}

                </div>

            </div>

            <div class="detail-section">

                <div class="detail-section-title">

                    <i class="bi bi-check2-square"></i>

                    Hasil Penerapan Auditee

                </div>

                <div class="detail-result-box">

                    {!! nl2br(e(
                        $data->deskripsi_hasil
                        ?? '-'
                    )) !!}

                </div>

            </div>

            <div class="detail-section">

                <div class="detail-section-title">

                    <i class="bi bi-paperclip"></i>

                    Bukti Pendukung

                </div>

                <div class="detail-evidence-box">

                    @if(filled($data->link_bukti))

                        <a
                            href="{{ $data->link_bukti }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="detail-evidence-button"
                        >

                            <i class="bi bi-box-arrow-up-right"></i>

                            Buka Bukti Penerapan

                        </a>

                        <span>
                            {{ $data->link_bukti }}
                        </span>

                    @else

                        <div class="detail-empty-value">

                            <i class="bi bi-file-earmark-x"></i>

                            Auditee belum menyertakan bukti pendukung.

                        </div>

                    @endif

                </div>

            </div>

            <div class="detail-section">

                <div class="detail-section-title">

                    <i class="bi bi-exclamation-circle"></i>

                    Temuan Audit

                </div>

                @forelse($data->temuan as $temuan)

                    @php
                        $statusTemuan = strtolower(
                            trim(
                                (string) $temuan->status_temuan
                            )
                        );
                    @endphp

                    <div class="detail-finding-card">

                        <div class="finding-card-heading">

                            <strong>
                                Temuan {{ $loop->iteration }}
                            </strong>

                            @if($statusTemuan === 'closed')

                                <span class="detail-status detail-status-closed">
                                    Closed
                                </span>

                            @else

                                <span class="detail-status detail-status-open">
                                    Open
                                </span>

                            @endif

                        </div>

                        <p>
                            {!! nl2br(e(
                                $temuan->temuan
                                ?? '-'
                            )) !!}
                        </p>

                    </div>

                @empty

                    <div class="detail-empty-value">

                        <i class="bi bi-check-circle"></i>

                        Belum ada temuan audit pada penerapan ini.

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

<style>

    .application-detail-page {
        width: 100%;
        max-width: 1150px;
        margin: 0 auto;
    }

    .application-breadcrumb {
        margin-bottom: 20px;
        color: #172554;
        font-size: 13px;
        font-weight: 700;
    }

    .period-tab-menu {
        display: flex;
        align-items: center;
        gap: 30px;
        overflow-x: auto;
        margin-bottom: 20px;
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
    }

    .period-tab-link:hover {
        color: #4f46e5;
    }

    .period-tab-link.active {
        border-bottom-color: #4f46e5;
        color: #4f46e5;
        font-weight: 800;
    }

    .detail-page-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 17px;
    }

    .detail-page-heading > div > span {
        display: block;
        margin-bottom: 6px;
        color: #6366f1;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.1em;
    }

    .detail-page-heading h2 {
        margin: 0 0 7px;
        color: #172554;
        font-size: 27px;
    }

    .detail-page-heading p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
    }

    .detail-back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #ffffff;
        color: #475569;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
    }

    .detail-back-button:hover {
        border-color: #4f46e5;
        color: #4f46e5;
    }

    .detail-information-alert {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 18px;
        padding: 13px 15px;
        border: 1px solid #bfdbfe;
        border-radius: 11px;
        background: #eff6ff;
        color: #1e40af;
        font-size: 12px;
    }

    .application-detail-card {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 8px 28px rgba(15, 23, 42, 0.06);
    }

    .detail-card-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 20px 22px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .detail-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 46px;
        min-width: 46px;
        height: 46px;
        border-radius: 12px;
        background: #4f46e5;
        color: #ffffff;
        font-size: 20px;
    }

    .detail-card-header h3 {
        margin: 0 0 4px;
        color: #172554;
        font-size: 17px;
    }

    .detail-card-header p {
        margin: 0;
        color: #64748b;
        font-size: 12px;
    }

    .detail-content {
        padding: 22px;
    }

    .detail-information-grid {
        display: grid;
        grid-template-columns:
            repeat(4, minmax(0, 1fr));
        gap: 13px;
        margin-bottom: 22px;
    }

    .detail-information-item {
        padding: 13px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
    }

    .detail-information-item span {
        display: block;
        margin-bottom: 5px;
        color: #64748b;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .detail-information-item strong {
        color: #1e293b;
        font-size: 12px;
        line-height: 1.5;
    }

    .detail-section {
        margin-top: 20px;
    }

    .detail-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: #334155;
        font-size: 12px;
        font-weight: 800;
    }

    .detail-section-title i {
        color: #4f46e5;
    }

    .detail-value-box,
    .detail-result-box {
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
        color: #334155;
        font-size: 13px;
        line-height: 1.65;
    }

    .detail-result-box {
        border-left: 4px solid #16a34a;
        background: #f7fcf8;
    }

    .detail-evidence-box {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
    }

    .detail-evidence-box > span {
        color: #64748b;
        font-size: 11px;
        word-break: break-all;
    }

    .detail-evidence-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 13px;
        border-radius: 8px;
        background: #2563eb;
        color: #ffffff;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
    }

    .detail-evidence-button:hover {
        background: #1d4ed8;
        color: #ffffff;
    }

    .detail-finding-card {
        margin-bottom: 11px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #ea580c;
        border-radius: 10px;
        background: #ffffff;
    }

    .finding-card-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
    }

    .finding-card-heading strong {
        color: #172554;
        font-size: 12px;
    }

    .detail-finding-card p {
        margin: 0;
        color: #475569;
        font-size: 12px;
        line-height: 1.6;
    }

    .detail-status {
        display: inline-flex;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .detail-status-open {
        background: #ffedd5;
        color: #c2410c;
    }

    .detail-status-closed {
        background: #dcfce7;
        color: #15803d;
    }

    .detail-empty-value {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 16px;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        background: #f8fafc;
        color: #64748b;
        font-size: 12px;
    }

    @media (max-width: 900px) {

        .detail-information-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

    }

    @media (max-width: 650px) {

        .detail-page-heading {
            align-items: stretch;
            flex-direction: column;
        }

        .detail-information-grid {
            grid-template-columns: 1fr;
        }

    }

</style>

@endsection