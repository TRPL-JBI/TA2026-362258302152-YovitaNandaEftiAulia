@extends('layouts.auditee')

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
        ? ($sessionUser['nama'] ?? 'Auditee')
        : ($sessionUser->nama ?? 'Auditee');
@endphp

<div class="role-dashboard">

    {{-- =====================================================
         SELAMAT DATANG
    ====================================================== --}}

    <div class="dashboard-welcome">

        <div>

            <span class="dashboard-eyebrow">
                DASHBOARD AUDITEE
            </span>

            <h2>
                Selamat Datang, {{ $namaUser }}
            </h2>

            <p>
                Pantau hasil penerapan standar, bukti pendukung,
                temuan audit, dan tanggapan yang masih perlu
                diselesaikan.
            </p>

        </div>

        <div class="dashboard-welcome-icon">

            <i class="bi bi-person-check"></i>

        </div>

    </div>

    {{-- =====================================================
         KARTU STATISTIK
    ====================================================== --}}

    <div class="dashboard-stat-grid">

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-blue">

                <i class="bi bi-clipboard-check"></i>

            </div>

            <div>

                <span>
                    Penerapan Saya
                </span>

                <strong>
                    {{ $penerapanSaya ?? 0 }}
                </strong>

                <small>
                    Data yang telah dikirim
                </small>

            </div>

        </div>

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-purple">

                <i class="bi bi-file-earmark-check"></i>

            </div>

            <div>

                <span>
                    Bukti Tersedia
                </span>

                <strong>
                    {{ $buktiSaya ?? 0 }}
                </strong>

                <small>
                    Bukti pendukung
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
                    {{ $temuanOpen ?? 0 }}
                </strong>

                <small>
                    Perlu ditanggapi
                </small>

            </div>

        </div>

        <div class="dashboard-stat-card">

            <div class="dashboard-stat-icon stat-green">

                <i class="bi bi-chat-square-check"></i>

            </div>

            <div>

                <span>
                    Tanggapan Saya
                </span>

                <strong>
                    {{ $jumlahTanggapan ?? 0 }}
                </strong>

                <small>
                    Tanggapan tersimpan
                </small>

            </div>

        </div>

    </div>

    {{-- =====================================================
         PROGRES PENYELESAIAN TEMUAN
    ====================================================== --}}

    <section class="dashboard-progress-card">

        <div class="dashboard-section-heading">

            <div>

                <span class="section-label">
                    PROGRES TINDAK LANJUT
                </span>

                <h3>
                    Penyelesaian Temuan Audit
                </h3>

                <p>
                    Persentase dihitung dari jumlah temuan yang
                    sudah berstatus closed.
                </p>

            </div>

            <strong class="dashboard-progress-value">

                {{
                    number_format(
                        $persentasePenyelesaian ?? 0,
                        2,
                        ',',
                        '.'
                    )
                }}%

            </strong>

        </div>

        <div class="dashboard-progress-track">

            <div
                class="dashboard-progress-fill"
                style="width: {{
                    min(
                        100,
                        max(
                            0,
                            $persentasePenyelesaian ?? 0
                        )
                    )
                }}%;"
            ></div>

        </div>

        <div class="dashboard-progress-detail">

            <span>

                <i class="bi bi-exclamation-circle"></i>

                {{ $temuanOpen ?? 0 }} Open

            </span>

            <span>

                <i class="bi bi-check-circle"></i>

                {{ $temuanClosed ?? 0 }} Closed

            </span>

            <span>

                <i class="bi bi-clock-history"></i>

                {{ $temuanBelumDitanggapi ?? 0 }}
                belum ditanggapi

            </span>

        </div>

    </section>

    {{-- =====================================================
         KONTEN UTAMA
    ====================================================== --}}

    <div class="dashboard-content-grid">

        {{-- =================================================
             TEMUAN AUDIT SAYA
        ================================================== --}}

        <section class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>

                    <span class="section-label">
                        PRIORITAS TINDAK LANJUT
                    </span>

                    <h3>
                        Temuan Audit Saya
                    </h3>

                </div>

                <a
                    href="{{ route('auditee.temuan.index') }}"
                    class="dashboard-panel-link"
                >

                    Lihat Temuan

                    <i class="bi bi-arrow-right"></i>

                </a>

            </div>

            <div class="dashboard-task-list">

                @forelse($daftarTemuan ?? collect() as $item)

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
                                {{ $item->indikator ?? '-' }}
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

                            @if(
                                $statusTemuan === 'open'
                                && $jumlahTanggapanItem === 0
                            )

                                <a
                                    href="{{ route(
                                        'auditee.tanggapan.create',
                                        $item->id
                                    ) }}"
                                    class="dashboard-small-button"
                                >

                                    Beri Tanggapan

                                </a>

                            @else

                                <a
                                    href="{{ route(
                                        'auditee.temuan.show',
                                        $item->id
                                    ) }}"
                                    class="dashboard-small-button secondary"
                                >

                                    Lihat

                                </a>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="dashboard-empty-box">

                        <i class="bi bi-check-circle"></i>

                        <strong>
                            Belum ada temuan audit
                        </strong>

                        <span>
                            Tidak ada temuan yang perlu ditindaklanjuti.
                        </span>

                    </div>

                @endforelse

            </div>

        </section>

        {{-- =================================================
             PERIODE AMI BERJALAN
        ================================================== --}}

        <section class="dashboard-panel">

            <div class="dashboard-panel-header">

                <div>

                    <span class="section-label">
                        PERIODE AKTIF
                    </span>

                    <h3>
                        Periode AMI Berjalan
                    </h3>

                </div>

            </div>

            <div class="dashboard-period-list">

                @forelse($periodeBerjalan ?? collect() as $item)

                    <div class="dashboard-period-item">

                        <div class="dashboard-period-icon">

                            <i class="bi bi-calendar-check"></i>

                        </div>

                        <div class="dashboard-period-content">

                            <strong>

                                {{
                                    $item->standarMutu->nama_standar_mutu
                                    ?? $item->standarMutu->nama
                                    ?? '-'
                                }}

                            </strong>

                            <span>

                                {{
                                    $item->unitKerja->nama
                                    ?? $item->unitKerja->nama_unit_kerja
                                    ?? '-'
                                }}

                            </span>

                            <small>

                                {{ $item->tanggal_buka_ami ?? '-' }}

                                sampai

                                {{ $item->tanggal_tutup_ami ?? '-' }}

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
                            Saat ini belum ada periode AMI yang berjalan.
                        </span>

                    </div>

                @endforelse

            </div>

        </section>

    </div>

</div>

@endsection