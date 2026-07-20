@extends('layouts.app')

@push('styles')

    <link
        rel="stylesheet"
        href="{{ asset('css/laporan-admin.css') }}"
    >

@endpush

@section('content')

<div class="admin-report-page">

    {{-- Breadcrumb --}}
    <div class="admin-report-breadcrumb">

        Dashboard / Laporan Audit Mutu Internal

    </div>

    {{-- Judul halaman --}}
    <div class="admin-report-heading">

        <div>

            <span class="admin-report-heading-label">
                SISTEM PENJAMINAN MUTU INTERNAL
            </span>

            <h2>
                Laporan Audit Mutu Internal
            </h2>

            <p>
                Daftar laporan resmi Audit Mutu Internal yang
                dibuat secara otomatis berdasarkan seluruh data
                terbaru yang tersimpan di dalam sistem.
            </p>

        </div>

        <div class="admin-report-heading-icon">

            <i class="bi bi-file-earmark-pdf-fill"></i>

        </div>

    </div>

    {{-- Informasi view only --}}
    <div class="admin-report-information">

        <div class="admin-report-information-icon">

            <i class="bi bi-shield-check"></i>

        </div>

        <div>

            <strong>
                Laporan Resmi dan Hanya-Baca
            </strong>

            <p>
                Admin hanya dapat melihat laporan dan membuka PDF.
                Isi laporan diambil langsung dari data periode,
                penerapan, temuan, tanggapan, akar masalah,
                rekomendasi, kesimpulan, dan lampiran audit.
            </p>

        </div>

    </div>

    {{-- Daftar laporan --}}
    <div class="admin-report-list">

        @forelse($data as $item)

            @php
                $penerapanList = $item
                    ->standarMutuPeriode
                    ->flatMap(function ($standarPeriode) {
                        return $standarPeriode
                            ->penerapanStandar;
                    });

                $temuanList = $penerapanList
                    ->flatMap(function ($penerapan) {
                        return $penerapan->temuan;
                    });

                $jumlahPenerapan =
                    $penerapanList->count();

                $jumlahTemuan =
                    $temuanList->count();

                $jumlahTemuanOpen = $temuanList
                    ->filter(function ($temuan) {
                        return strtolower(
                            trim(
                                (string)
                                $temuan->status_temuan
                            )
                        ) === 'open';
                    })
                    ->count();

                $jumlahTemuanClosed = $temuanList
                    ->filter(function ($temuan) {
                        return strtolower(
                            trim(
                                (string)
                                $temuan->status_temuan
                            )
                        ) === 'closed';
                    })
                    ->count();

                $jumlahAuditor = $item
                    ->tim
                    ->pluck('id_user')
                    ->filter()
                    ->unique()
                    ->count();

                $namaUnit =
                    $item->unitKerja->nama
                    ?? $item->unitKerja->nama_unit_kerja
                    ?? '-';

                $namaStandar =
                    $item->standarMutu->nama_standar_mutu
                    ?? $item->standarMutu->nama
                    ?? '-';

                $statusPeriode = strtolower(
                    trim(
                        (string) $item->status
                    )
                );
            @endphp

            <article class="admin-report-card">

                <div class="admin-report-card-accent"></div>

                <div class="admin-report-card-body">

                    <div class="admin-report-document-icon">

                        <i class="bi bi-file-earmark-text"></i>

                    </div>

                    <div class="admin-report-card-content">

                        <div class="admin-report-title-row">

                            <div>

                                <span class="admin-report-document-label">
                                    LAPORAN HASIL AMI
                                </span>

                                <h3>
                                    {{ $namaUnit }}
                                </h3>

                                <p>
                                    {{ $namaStandar }}
                                </p>

                            </div>

                            @if(
                                in_array(
                                    $statusPeriode,
                                    [
                                        'ditutup',
                                        'selesai',
                                        'closed'
                                    ]
                                )
                            )

                                <span class="admin-report-status admin-report-status-finished">

                                    <i class="bi bi-check-circle-fill"></i>

                                    Audit Selesai

                                </span>

                            @elseif(
                                in_array(
                                    $statusPeriode,
                                    [
                                        'berjalan',
                                        'aktif',
                                        'open'
                                    ]
                                )
                            )

                                <span class="admin-report-status admin-report-status-running">

                                    <i class="bi bi-clock-fill"></i>

                                    Sedang Berjalan

                                </span>

                            @else

                                <span class="admin-report-status admin-report-status-draft">

                                    <i class="bi bi-file-earmark"></i>

                                    {{
                                        ucfirst(
                                            $item->status
                                            ?? 'Draft'
                                        )
                                    }}

                                </span>

                            @endif

                        </div>

                        <div class="admin-report-information-grid">

                            <div class="admin-report-information-item">

                                <span>
                                    Periode AMI
                                </span>

                                <strong>
                                    {{ $item->tahun ?? '-' }}
                                </strong>

                            </div>

                            <div class="admin-report-information-item">

                                <span>
                                    Tim Auditor
                                </span>

                                <strong>
                                    {{ $jumlahAuditor }} orang
                                </strong>

                            </div>

                            <div class="admin-report-information-item">

                                <span>
                                    Penerapan
                                </span>

                                <strong>
                                    {{ $jumlahPenerapan }} data
                                </strong>

                            </div>

                            <div class="admin-report-information-item">

                                <span>
                                    Total Temuan
                                </span>

                                <strong>
                                    {{ $jumlahTemuan }} temuan
                                </strong>

                            </div>

                        </div>

                        <div class="admin-report-finding-summary">

                            <div class="admin-report-finding-item finding-open">

                                <i class="bi bi-exclamation-circle-fill"></i>

                                <span>
                                    Temuan Open
                                </span>

                                <strong>
                                    {{ $jumlahTemuanOpen }}
                                </strong>

                            </div>

                            <div class="admin-report-finding-item finding-closed">

                                <i class="bi bi-check-circle-fill"></i>

                                <span>
                                    Temuan Closed
                                </span>

                                <strong>
                                    {{ $jumlahTemuanClosed }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="admin-report-card-footer">

                    <span class="admin-report-database-info">

                        <i class="bi bi-database-check"></i>

                        PDF dibuat dari data terbaru di database

                    </span>

                    <a
                        href="{{ route(
                            'laporan.pdf',
                            $item->id
                        ) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="admin-report-pdf-button"
                    >

                        <i class="bi bi-file-earmark-pdf-fill"></i>

                        Buka Laporan PDF

                        <i class="bi bi-box-arrow-up-right"></i>

                    </a>

                </div>

            </article>

        @empty

            <div class="admin-report-empty-card">

                <i class="bi bi-file-earmark-x"></i>

                <strong>
                    Belum Ada Laporan AMI
                </strong>

                <span>
                    Laporan akan tersedia setelah data periode
                    Audit Mutu Internal dibuat.
                </span>

            </div>

        @endforelse

    </div>

</div>

@endsection