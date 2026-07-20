@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/laporan-auditor.css') }}"
    >
@endpush

@section('content')

<div class="report-page">

    <div class="quality-page-heading report-page-heading">

        <div>

            <div class="breadcrumb quality-breadcrumb">
                Dashboard / Laporan Audit Mutu Internal
            </div>

            <h2>
                Laporan Audit Mutu Internal
            </h2>

            <p>
                Laporan resmi Audit Mutu Internal yang dibuat
                otomatis berdasarkan seluruh data audit.
            </p>

        </div>

        <div class="report-heading-icon">
            <i class="bi bi-file-earmark-pdf-fill"></i>
        </div>

    </div>

    @if(session('success'))

        <div class="quality-alert quality-alert-success">

            <i class="bi bi-check-circle-fill"></i>

            {{ session('success') }}

        </div>

    @endif

    <div class="report-summary-banner">

        <div class="report-banner-icon">
            <i class="bi bi-shield-check"></i>
        </div>

        <div>

            <strong>
                Dokumen Laporan Resmi
            </strong>

            <p>
                Klik tombol Buka PDF untuk menghasilkan laporan
                berdasarkan data terbaru yang tersimpan di database.
            </p>

        </div>

    </div>

    <div class="report-list">

        @forelse($data as $item)

            @php
                $jumlahTemuan = $item
                    ->standarMutuPeriode
                    ->flatMap(function ($standarPeriode) {
                        return $standarPeriode
                            ->penerapanStandar;
                    })
                    ->flatMap(function ($penerapan) {
                        return $penerapan->temuan;
                    })
                    ->count();

                $jumlahAuditor =
                    $item->tim->count();

                $status =
                    strtolower(
                        trim((string) $item->status)
                    );

                $namaUnit =
                    $item->unitKerja->nama
                    ?? $item->unitKerja->nama_unit_kerja
                    ?? '-';

                $namaStandar =
                    $item->standarMutu->nama_standar_mutu
                    ?? $item->standarMutu->nama
                    ?? '-';
            @endphp

            <article class="report-card">

                <div class="report-card-accent"></div>

                <div class="report-card-main">

                    <div class="report-document-icon">

                        <i class="bi bi-file-earmark-text"></i>

                    </div>

                    <div class="report-card-content">

                        <div class="report-card-title-row">

                            <div>

                                <span class="report-label">
                                    LAPORAN HASIL AMI
                                </span>

                                <h3>
                                    {{ $namaUnit }}
                                </h3>

                            </div>

                            @if(
                                in_array(
                                    $status,
                                    ['ditutup', 'selesai', 'closed']
                                )
                            )

                                <span class="report-status report-status-finished">

                                    <i class="bi bi-check-circle-fill"></i>

                                    Audit Selesai

                                </span>

                            @elseif(
                                in_array(
                                    $status,
                                    ['berjalan', 'aktif', 'open']
                                )
                            )

                                <span class="report-status report-status-running">

                                    <i class="bi bi-clock-fill"></i>

                                    Sedang Berjalan

                                </span>

                            @else

                                <span class="report-status report-status-draft">

                                    <i class="bi bi-file-earmark"></i>

                                    Draft

                                </span>

                            @endif

                        </div>

                        <div class="report-information-grid">

                            <div>

                                <span>
                                    Periode AMI
                                </span>

                                <strong>
                                    {{ $item->tahun ?? '-' }}
                                </strong>

                            </div>

                            <div>

                                <span>
                                    Standar Mutu
                                </span>

                                <strong>
                                    {{ $namaStandar }}
                                </strong>

                            </div>

                            <div>

                                <span>
                                    Tim Auditor
                                </span>

                                <strong>
                                    {{ $jumlahAuditor }} orang
                                </strong>

                            </div>

                            <div>

                                <span>
                                    Temuan Audit
                                </span>

                                <strong>
                                    {{ $jumlahTemuan }} temuan
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="report-card-footer">

                    <span class="report-update-information">

                        <i class="bi bi-database-check"></i>

                        Data PDF diambil langsung dari database

                    </span>

                    <a
                        href="{{ route(
                            'auditor.laporan.show',
                            $item->id
                        ) }}"
                        target="_blank"
                        class="report-pdf-button"
                    >

                        <i class="bi bi-file-earmark-pdf-fill"></i>

                        Buka Laporan PDF

                        <i class="bi bi-box-arrow-up-right"></i>

                    </a>

                </div>

            </article>

        @empty

            <div class="quality-card">

                <div class="quality-empty-state report-empty-state">

                    <i class="bi bi-file-earmark-x"></i>

                    <strong>
                        Belum Ada Laporan AMI
                    </strong>

                    <span>
                        Laporan akan tersedia setelah Anda ditugaskan
                        sebagai anggota tim auditor.
                    </span>

                </div>

            </div>

        @endforelse

    </div>

</div>

@endsection