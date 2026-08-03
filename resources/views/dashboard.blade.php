@extends('layouts.app')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/dashboard-role.css') }}"
    >
@endpush

@section('content')

@php
    $sessionUser = $authUser;

    $namaUser = is_array($sessionUser)
        ? ($sessionUser['nama'] ?? 'Administrator')
        : ($sessionUser->nama ?? 'Administrator');

    $totalStandar = $totalStandar ?? 0;
    $totalPeriode = $totalPeriode ?? 0;
    $periodeAktif = $periodeAktif ?? 0;
    $totalUnitKerja = $totalUnitKerja ?? 0;
    $totalPengguna = $totalPengguna ?? 0;
    $totalPenerapan = $totalPenerapan ?? 0;
    $totalTemuan = $totalTemuan ?? 0;
    $temuanOpen = $temuanOpen ?? 0;
    $temuanClosed = $temuanClosed ?? 0;

    $periodeBerjalan = $periodeBerjalan ?? collect();

    $dataRingkasanUnit =
        $ringkasanUnit
        ?? $rekapUnit
        ?? collect();

    $aktivitasTerbaru =
        $aktivitasTerbaru
        ?? collect();
@endphp

<div class="role-dashboard">

    {{-- =====================================================
         HEADER DASHBOARD
    ====================================================== --}}

    <section class="dashboard-welcome">

        <div>

            <span class="dashboard-eyebrow">
                DASHBOARD ADMINISTRATOR
            </span>

            <h2>
                Selamat Datang, {{ $namaUser }}
            </h2>

            <p>
                Pantau seluruh pelaksanaan Audit Mutu Internal,
                periode AMI, penerapan standar, temuan audit,
                unit kerja, dan pengguna sistem.
            </p>

        </div>

        <div class="dashboard-welcome-icon">

            <i class="bi bi-shield-check"></i>

        </div>

    </section>

    {{-- =====================================================
         STATISTIK UTAMA
    ====================================================== --}}

    <section class="dashboard-stat-grid">

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-blue">

                <i class="bi bi-journal-check"></i>

            </div>

            <div>

                <span>
                    Standar Mutu
                </span>

                <strong>
                    {{ $totalStandar }}
                </strong>

                <small>
                    Standar tersedia
                </small>

            </div>

        </div>

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-purple">

                <i class="bi bi-calendar-check"></i>

            </div>

            <div>

                <span>
                    Periode Aktif
                </span>

                <strong>
                    {{ $periodeAktif }}
                </strong>

                <small>
                    Dari {{ $totalPeriode }} periode
                </small>

            </div>

        </div>

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-green">

                <i class="bi bi-building"></i>

            </div>

            <div>

                <span>
                    Unit Kerja
                </span>

                <strong>
                    {{ $totalUnitKerja }}
                </strong>

                <small>
                    Unit terdaftar
                </small>

            </div>

        </div>

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-orange">

                <i class="bi bi-people"></i>

            </div>

            <div>

                <span>
                    Pengguna
                </span>

                <strong>
                    {{ $totalPengguna }}
                </strong>

                <small>
                    Akun sistem
                </small>

            </div>

        </div>

    </section>

    {{-- =====================================================
         RINGKASAN AUDIT
    ====================================================== --}}

    <section class="dashboard-progress-card">

        <div class="dashboard-section-heading">

            <div>

                <span class="section-label">
                    RINGKASAN AUDIT
                </span>

                <h3>
                    Status Pelaksanaan AMI
                </h3>

                <p>
                    Data berikut dihitung langsung dari penerapan
                    standar dan temuan audit yang tersimpan.
                </p>

            </div>

        </div>

        <div class="dashboard-summary-grid">

            <div class="dashboard-summary-item">

                <span>
                    Total Penerapan
                </span>

                <strong>
                    {{ $totalPenerapan }}
                </strong>

                <small>
                    Penerapan standar
                </small>

            </div>

            <div class="dashboard-summary-item">

                <span>
                    Total Temuan
                </span>

                <strong>
                    {{ $totalTemuan }}
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
                    Perlu tindak lanjut
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

        </div>

    </section>

    {{-- =====================================================
         PERIODE AMI BERJALAN
    ====================================================== --}}

    <section class="dashboard-panel">

        <div class="dashboard-panel-header">

            <div>

                <span class="section-label">
                    PERIODE AKTIF
                </span>

                <h3>
                    Periode AMI Berjalan
                </h3>

                <p>
                    Daftar periode Audit Mutu Internal yang saat
                    ini masih berstatus berjalan.
                </p>

            </div>

            <a
                href="{{ route('periode-ami.index') }}"
                class="dashboard-panel-link"
            >

                Lihat Semua

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
                            Tahun
                        </th>

                        <th>
                            Unit Kerja
                        </th>

                        <th>
                            Standar Mutu
                        </th>

                        <th>
                            Jadwal AMI
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

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

                        <tr>

                            <td>

                                <span class="table-number">
                                    {{ $loop->iteration }}
                                </span>

                            </td>

                            <td>

                                <strong>
                                    {{ $item->tahun ?? '-' }}
                                </strong>

                            </td>

                            <td>
                                {{ $namaUnit }}
                            </td>

                            <td>
                                {{ $namaStandar }}
                            </td>

                            <td>

                                <span>
                                    {{ $tanggalBuka }}
                                </span>

                                <br>

                                <small>
                                    sampai {{ $tanggalTutup }}
                                </small>

                            </td>

                            <td>

                                <span class="dashboard-status status-running">

                                    <i class="bi bi-play-circle-fill"></i>

                                    Berjalan

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="dashboard-empty-table"
                            >

                                Belum ada periode AMI yang berjalan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

    {{-- =====================================================
         RINGKASAN UNIT DAN AKTIVITAS
    ====================================================== --}}

    <div class="dashboard-content-grid">

        {{-- =================================================
             RINGKASAN UNIT KERJA
        ================================================== --}}

        <section class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>

                    <span class="section-label">
                        MONITORING UNIT
                    </span>

                    <h3>
                        Ringkasan Per Unit Kerja
                    </h3>

                    <p>
                        Ringkasan periode, penerapan, dan temuan
                        pada setiap unit kerja.
                    </p>

                </div>

            </div>

            <div class="dashboard-unit-list">

                @forelse($dataRingkasanUnit as $item)

                    @php
                        $namaUnit =
                            $item->nama_unit
                            ?? $item->nama
                            ?? $item->nama_unit_kerja
                            ?? 'Unit Kerja';

                        $jumlahPeriode =
                            $item->jumlah_periode
                            ?? 0;

                        $jumlahPenerapan =
                            $item->jumlah_penerapan
                            ?? 0;

                        $jumlahOpen =
                            $item->temuan_open
                            ?? 0;

                        $jumlahClosed =
                            $item->temuan_closed
                            ?? 0;
                    @endphp

                    <div class="dashboard-unit-item">

                        <div class="dashboard-unit-main">

                            <div class="dashboard-unit-icon">

                                <i class="bi bi-building-check"></i>

                            </div>

                            <div>

                                <strong>
                                    {{ $namaUnit }}
                                </strong>

                                <span>
                                    {{ $jumlahPeriode }}
                                    periode AMI
                                </span>

                            </div>

                        </div>

                        <div class="dashboard-unit-metrics">

                            <span>

                                <small>
                                    Penerapan
                                </small>

                                <strong>
                                    {{ $jumlahPenerapan }}
                                </strong>

                            </span>

                            <span class="metric-open">

                                <small>
                                    Open
                                </small>

                                <strong>
                                    {{ $jumlahOpen }}
                                </strong>

                            </span>

                            <span class="metric-closed">

                                <small>
                                    Closed
                                </small>

                                <strong>
                                    {{ $jumlahClosed }}
                                </strong>

                            </span>

                        </div>

                    </div>

                @empty

                    <div class="dashboard-empty-box">

                        <i class="bi bi-building-x"></i>

                        <strong>
                            Belum ada data unit kerja
                        </strong>

                        <span>
                            Ringkasan unit kerja akan tampil setelah
                            data tersedia.
                        </span>

                    </div>

                @endforelse

            </div>

        </section>

        {{-- =================================================
             AKTIVITAS TERBARU
        ================================================== --}}

        <section class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>

                    <span class="section-label">
                        AKTIVITAS SISTEM
                    </span>

                    <h3>
                        Penerapan Terbaru
                    </h3>

                    <p>
                        Data penerapan standar yang terakhir
                        dimasukkan oleh Auditee.
                    </p>

                </div>

            </div>

            <div class="dashboard-task-list">

                @forelse($aktivitasTerbaru as $item)

                    @php
                        $namaAktivitasUser =
                            $item->nama_user
                            ?? 'Pengguna';

                        $deskripsiAktivitas =
                            $item->deskripsi_hasil
                            ?? 'Menambahkan data penerapan standar.';
                    @endphp

                    <div class="dashboard-task-item">

                        <div class="dashboard-task-content">

                            <strong>
                                {{ $namaAktivitasUser }}
                            </strong>

                            <p>
                                {{ $deskripsiAktivitas }}
                            </p>

                            <span>

                                <i class="bi bi-clipboard-check"></i>

                                Penerapan standar

                            </span>

                        </div>

                        <div class="dashboard-task-meta">

                            <span class="dashboard-status status-reviewed">

                                <i class="bi bi-check-circle"></i>

                                Tersimpan

                            </span>

                        </div>

                    </div>

                @empty

                    <div class="dashboard-empty-box">

                        <i class="bi bi-clock-history"></i>

                        <strong>
                            Belum ada aktivitas
                        </strong>

                        <span>
                            Aktivitas penerapan terbaru akan tampil
                            pada bagian ini.
                        </span>

                    </div>

                @endforelse

            </div>

        </section>

    </div>

</div>

@endsection