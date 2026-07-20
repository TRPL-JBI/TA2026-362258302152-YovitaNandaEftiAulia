@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/dashboard-role.css') }}"
    >
@endpush

@section('content')

@php
    $sessionUser = session('user');

    $namaUser = is_array($sessionUser)
        ? ($sessionUser['nama'] ?? 'Auditor')
        : ($sessionUser->nama ?? 'Auditor');

    $jumlahPeriodeDitugaskan =
        $jumlahPeriodeDitugaskan
        ?? $periodeDitugaskan
        ?? 0;

    $periodeAktif =
        $periodeAktif
        ?? 0;

    $jumlahPenerapan =
        $jumlahPenerapan
        ?? $penerapanTersedia
        ?? 0;

    $temuanOpen =
        $temuanOpen
        ?? 0;

    $temuanClosed =
        $temuanClosed
        ?? 0;

    $jumlahTemuan =
        $jumlahTemuan
        ?? ($temuanOpen + $temuanClosed);

    $penerapanSiapDiperiksa =
        $penerapanSiapDiperiksa
        ?? collect();

    $temuanTerbaru =
        $temuanTerbaru
        ?? $temuanTerbukaTerbaru
        ?? collect();

    $periodeBerjalan =
        $periodeBerjalan
        ?? collect();
@endphp

<div class="role-dashboard">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <section class="dashboard-welcome">

        <div>

            <span class="dashboard-eyebrow">
                DASHBOARD AUDITOR
            </span>

            <h2>
                Selamat Datang, {{ $namaUser }}
            </h2>

            <p>
                Pantau periode AMI yang ditugaskan, periksa
                penerapan standar dari Auditee, dan kelola
                tindak lanjut temuan audit.
            </p>

        </div>

        <div class="dashboard-welcome-icon">

            <i class="bi bi-clipboard-check"></i>

        </div>

    </section>

    {{-- =====================================================
         STATISTIK
    ====================================================== --}}

    <section class="dashboard-stat-grid">

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-blue">

                <i class="bi bi-calendar2-check"></i>

            </div>

            <div>

                <span>
                    Periode Ditugaskan
                </span>

                <strong>
                    {{ $jumlahPeriodeDitugaskan }}
                </strong>

                <small>
                    Seluruh penugasan audit
                </small>

            </div>

        </div>

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-purple">

                <i class="bi bi-play-circle"></i>

            </div>

            <div>

                <span>
                    Periode Aktif
                </span>

                <strong>
                    {{ $periodeAktif }}
                </strong>

                <small>
                    Periode berstatus berjalan
                </small>

            </div>

        </div>

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-green">

                <i class="bi bi-file-earmark-check"></i>

            </div>

            <div>

                <span>
                    Penerapan Tersedia
                </span>

                <strong>
                    {{ $jumlahPenerapan }}
                </strong>

                <small>
                    Siap diperiksa
                </small>

            </div>

        </div>

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-orange">

                <i class="bi bi-exclamation-circle"></i>

            </div>

            <div>

                <span>
                    Temuan Open
                </span>

                <strong>
                    {{ $temuanOpen }}
                </strong>

                <small>
                    Masih perlu tindak lanjut
                </small>

            </div>

        </div>

    </section>

    {{-- =====================================================
         RINGKASAN TEMUAN
    ====================================================== --}}

    <section class="dashboard-progress-card">

        <div class="dashboard-section-heading">

            <div>

                <span class="section-label">
                    RINGKASAN TEMUAN
                </span>

                <h3>
                    Status Temuan Audit
                </h3>

                <p>
                    Ringkasan temuan berdasarkan periode AMI
                    yang ditugaskan kepada Auditor.
                </p>

            </div>

        </div>

        <div class="dashboard-summary-grid">

            <div class="dashboard-summary-item">

                <span>
                    Total Temuan
                </span>

                <strong>
                    {{ $jumlahTemuan }}
                </strong>

                <small>
                    Seluruh temuan audit
                </small>

            </div>

            <div class="dashboard-summary-item summary-open">

                <span>
                    Temuan Open
                </span>

                <strong>
                    {{ $temuanOpen }}
                </strong>

                <small>
                    Belum diselesaikan
                </small>

            </div>

            <div class="dashboard-summary-item summary-closed">

                <span>
                    Temuan Closed
                </span>

                <strong>
                    {{ $temuanClosed }}
                </strong>

                <small>
                    Sudah diselesaikan
                </small>

            </div>

            <div class="dashboard-summary-item">

                <span>
                    Penerapan
                </span>

                <strong>
                    {{ $jumlahPenerapan }}
                </strong>

                <small>
                    Data dari Auditee
                </small>

            </div>

        </div>

    </section>

    {{-- =====================================================
         PENERAPAN SIAP DIPERIKSA
    ====================================================== --}}

    <section class="dashboard-panel">

        <div class="dashboard-panel-header">

            <div>

                <span class="section-label">
                    PEMERIKSAAN AUDIT
                </span>

                <h3>
                    Penerapan Siap Diperiksa
                </h3>

                <p>
                    Daftar penerapan standar yang telah dikirim
                    oleh Auditee pada periode penugasan Anda.
                </p>

            </div>

            <a
                href="{{ route('auditor.temuan.index') }}"
                class="dashboard-panel-link"
            >

                Buka Audit

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

        <div class="dashboard-table-scroll">

            <table class="dashboard-modern-table">

                <thead>

                    <tr>

                        <th>
                            No.
                        </th>

                        <th>
                            Indikator
                        </th>

                        <th>
                            Auditee
                        </th>

                        <th>
                            Hasil Penerapan
                        </th>

                        <th>
                            Bukti
                        </th>

                        <th>
                            Status Pemeriksaan
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($penerapanSiapDiperiksa as $item)

                        @php
                            $indikator =
                                $item->indikator
                                ?? '-';

                            $namaAuditee =
                                $item->nama_auditee
                                ?? '-';

                            $deskripsiHasil =
                                $item->deskripsi_hasil
                                ?? '-';

                            $linkBukti =
                                $item->link_bukti
                                ?? null;

                            $idTemuan =
                                $item->id_temuan
                                ?? null;

                            $statusTemuan = strtolower(
                                trim(
                                    (string) (
                                        $item->status_temuan
                                        ?? ''
                                    )
                                )
                            );
                        @endphp

                        <tr>

                            <td>

                                <span class="table-number">
                                    {{ $loop->iteration }}
                                </span>

                            </td>

                            <td>

                                <strong>
                                    {{ $indikator }}
                                </strong>

                            </td>

                            <td>
                                {{ $namaAuditee }}
                            </td>

                            <td>
                                {{ $deskripsiHasil }}
                            </td>

                            <td>

                                @if(!empty($linkBukti))

                                    <a
                                        href="{{ $linkBukti }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="dashboard-evidence-link"
                                    >

                                        <i class="bi bi-box-arrow-up-right"></i>

                                        Lihat Bukti

                                    </a>

                                @else

                                    <span class="dashboard-muted">
                                        Belum tersedia
                                    </span>

                                @endif

                            </td>

                            <td>

                                @if($idTemuan)

                                    @if($statusTemuan === 'closed')

                                        <span class="dashboard-status status-closed">

                                            <i class="bi bi-check-circle-fill"></i>

                                            Closed

                                        </span>

                                    @else

                                        <span class="dashboard-status status-open">

                                            <i class="bi bi-exclamation-circle-fill"></i>

                                            Open

                                        </span>

                                    @endif

                                @else

                                    <span class="dashboard-status status-waiting">

                                        <i class="bi bi-hourglass-split"></i>

                                        Belum Diperiksa

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="dashboard-empty-table"
                            >

                                Belum ada penerapan yang siap diperiksa.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

    {{-- =====================================================
         TEMUAN DAN PERIODE
    ====================================================== --}}

    <div class="dashboard-content-grid">

        {{-- =================================================
             TEMUAN TERBARU
        ================================================== --}}

        <section class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>

                    <span class="section-label">
                        TINDAK LANJUT
                    </span>

                    <h3>
                        Temuan Audit Terbaru
                    </h3>

                    <p>
                        Temuan terbaru dari penerapan standar
                        yang telah diperiksa.
                    </p>

                </div>

                <a
                    href="{{ route('auditor.temuan.index') }}"
                    class="dashboard-panel-link"
                >

                    Lihat Semua

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

            <div class="dashboard-task-list">

                @forelse($temuanTerbaru as $item)

                    @php
                        $statusTemuan = strtolower(
                            trim(
                                (string) (
                                    $item->status_temuan
                                    ?? ''
                                )
                            )
                        );

                        $jumlahTanggapanItem =
                            (int) (
                                $item->jumlah_tanggapan
                                ?? 0
                            );
                    @endphp

                    <div class="dashboard-task-item">

                        <div class="dashboard-task-content">

                            <strong>
                                {{ $item->indikator ?? 'Temuan Audit' }}
                            </strong>

                            <p>
                                {{ $item->temuan ?? '-' }}
                            </p>

                            <span>

                                <i class="bi bi-chat-left-text"></i>

                                {{ $jumlahTanggapanItem }}
                                tanggapan

                            </span>

                        </div>

                        <div class="dashboard-task-meta">

                            @if($statusTemuan === 'closed')

                                <span class="dashboard-status status-closed">

                                    <i class="bi bi-check-circle-fill"></i>

                                    Closed

                                </span>

                            @else

                                <span class="dashboard-status status-open">

                                    <i class="bi bi-exclamation-circle-fill"></i>

                                    Open

                                </span>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="dashboard-empty-box">

                        <i class="bi bi-clipboard-check"></i>

                        <strong>
                            Belum ada temuan audit
                        </strong>

                        <span>
                            Temuan yang dibuat akan tampil pada bagian ini.
                        </span>

                    </div>

                @endforelse

            </div>

        </section>

        {{-- =================================================
             PERIODE AMI AKTIF
        ================================================== --}}

        <section class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>

                    <span class="section-label">
                        PENUGASAN AKTIF
                    </span>

                    <h3>
                        Periode AMI Berjalan
                    </h3>

                    <p>
                        Periode aktif yang sedang ditugaskan
                        kepada Anda.
                    </p>

                </div>

                <a
                    href="{{ route('auditor.periode.index') }}"
                    class="dashboard-panel-link"
                >

                    Lihat Periode

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

            <div class="dashboard-period-list">

                @forelse($periodeBerjalan as $item)

                    @php
                        $namaUnit =
                            $item->unitKerja->nama
                            ?? $item->unitKerja->nama_unit_kerja
                            ?? '-';

                        $namaStandar =
                            $item->standarMutu->nama_standar_mutu
                            ?? $item->standarMutu->nama
                            ?? '-';

                        $tanggalBuka =
                            $item->tanggal_buka_ami
                            ?? '-';

                        $tanggalTutup =
                            $item->tanggal_tutup_ami
                            ?? '-';
                    @endphp

                    <div class="dashboard-period-item">

                        <div class="dashboard-period-icon">

                            <i class="bi bi-calendar-check"></i>

                        </div>

                        <div class="dashboard-period-content">

                            <strong>
                                {{ $namaUnit }}
                            </strong>

                            <span>
                                {{ $namaStandar }}
                            </span>

                            <small>
                                {{ $tanggalBuka }}
                                sampai
                                {{ $tanggalTutup }}
                            </small>

                        </div>

                        <span class="dashboard-status status-running">

                            <i class="bi bi-play-circle-fill"></i>

                            Berjalan

                        </span>

                    </div>

                @empty

                    <div class="dashboard-empty-box">

                        <i class="bi bi-calendar-x"></i>

                        <strong>
                            Tidak ada periode aktif
                        </strong>

                        <span>
                            Belum ada periode AMI berjalan yang
                            ditugaskan kepada Anda.
                        </span>

                    </div>

                @endforelse

            </div>

        </section>

    </div>

</div>

@endsection