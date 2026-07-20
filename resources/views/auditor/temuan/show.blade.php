@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/auditor-temuan-penerapan.css') }}"
    >
@endpush

@section('content')

@php
    $penerapan =
        $temuan->penerapanStandar;

    $indikator =
        $penerapan?->indikator;

    $isiStandar =
        $indikator?->isiStandar;

    $standarPeriode =
        $penerapan?->standarmutuPeriode;

    $standarMutu =
        $standarPeriode?->standarMutu;

    $periodeAmi =
        $standarPeriode?->periodeAmi;

    $unitKerja =
        $periodeAmi?->unitKerja;

    $namaStandar =
        $standarMutu?->nama_standar_mutu
        ?? $standarMutu?->nama
        ?? '-';

    $namaUnit =
        $unitKerja?->nama
        ?? $unitKerja?->nama_unit_kerja
        ?? '-';

    $namaAuditee =
        $penerapan?->user?->nama
        ?? 'Auditee';

    $emailAuditee =
        $penerapan?->user?->email
        ?? '-';

    $deskripsiIndikator =
        $indikator?->deskripsi
        ?? $indikator?->indikator
        ?? $indikator?->nama_indikator
        ?? '-';

    $deskripsiHasil =
        trim(
            (string) (
                $penerapan?->deskripsi_hasil
                ?? ''
            )
        );

    $linkBukti =
        trim(
            (string) (
                $penerapan?->link_bukti
                ?? ''
            )
        );

    $statusTemuan = strtolower(
        trim(
            (string) (
                $temuan->status_temuan
                ?? 'open'
            )
        )
    );

    $namaIsi = function ($isi) {
        return $isi?->nama_isi_standar
            ?? $isi?->nama
            ?? $isi?->isi_standar
            ?? null;
    };

    $parent1 = $isiStandar?->parent;
    $parent2 = $parent1?->parent;
    $parent3 = $parent2?->parent;

    $hierarkiIsi = collect([
        $namaIsi($parent3),
        $namaIsi($parent2),
        $namaIsi($parent1),
        $namaIsi($isiStandar),
    ])
        ->filter(
            fn ($value) =>
                !empty(
                    trim(
                        (string) $value
                    )
                )
        )
        ->values();

    $teksHierarki =
        $hierarkiIsi->isNotEmpty()
            ? $hierarkiIsi->implode(' → ')
            : '-';

    $daftarTanggapan =
        $temuan->tanggapan
        ?? collect();
@endphp

<div class="auditor-temuan-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <section class="auditor-temuan-header">

        <div>

            <span class="auditor-temuan-eyebrow">
                DETAIL TEMUAN AUDIT
            </span>

            <h2>
                Detail Temuan
            </h2>

            <p>
                Lihat hasil penerapan, bukti, isi temuan,
                status tindak lanjut, dan tanggapan Auditee.
            </p>

        </div>

        <div class="auditor-temuan-header-icon">

            <i class="bi bi-clipboard2-data"></i>

        </div>

    </section>

    {{-- =====================================================
         PESAN SUKSES
    ====================================================== --}}

    @if(session('success'))

        <div class="auditor-alert auditor-alert-success">

            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif

    {{-- =====================================================
         NAVIGASI AKSI
    ====================================================== --}}

    <section class="auditor-detail-actions">

        <a
            href="{{ route('auditor.temuan.index') }}"
            class="auditor-secondary-button"
        >

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

        <div class="auditor-detail-actions-right">

            <a
                href="{{ route(
                    'auditor.temuan.edit',
                    $temuan->id
                ) }}"
                class="auditor-edit-button"
            >

                <i class="bi bi-pencil-square"></i>

                Edit Temuan

            </a>

            <form
                action="{{ route(
                    'auditor.temuan.destroy',
                    $temuan->id
                ) }}"
                method="POST"
                class="auditor-delete-form"
                onsubmit="
                    return confirm(
                        'Apakah Anda yakin ingin menghapus temuan ini?'
                    );
                "
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="auditor-delete-button"
                >

                    <i class="bi bi-trash3"></i>

                    Hapus Temuan

                </button>

            </form>

        </div>

    </section>

    {{-- =====================================================
         STATUS TEMUAN
    ====================================================== --}}

    <section class="auditor-temuan-status-banner">

        <div>

            <span>
                Status Temuan
            </span>

            @if($statusTemuan === 'closed')

                <strong class="auditor-status status-closed">

                    <i class="bi bi-check-circle-fill"></i>

                    Closed

                </strong>

            @else

                <strong class="auditor-status status-open">

                    <i class="bi bi-exclamation-circle-fill"></i>

                    Open

                </strong>

            @endif

        </div>

        <div>

            <span>
                Jumlah Tanggapan
            </span>

            <strong class="auditor-response-count">

                <i class="bi bi-chat-left-text"></i>

                {{ $daftarTanggapan->count() }}

            </strong>

        </div>

    </section>

    {{-- =====================================================
         DATA PENERAPAN
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    DATA PENERAPAN AUDITEE
                </span>

                <h3>
                    Dasar Pemeriksaan
                </h3>

                <p>
                    Informasi penerapan berikut hanya dapat dilihat
                    oleh Auditor.
                </p>

            </div>

        </div>

        <div class="auditor-detail-grid">

            <div class="auditor-detail-item">

                <span>
                    Standar Mutu
                </span>

                <strong>
                    {{ $namaStandar }}
                </strong>

            </div>

            <div class="auditor-detail-item">

                <span>
                    Periode AMI
                </span>

                <strong>
                    {{ $periodeAmi?->tahun ?? '-' }}
                </strong>

            </div>

            <div class="auditor-detail-item">

                <span>
                    Unit Kerja
                </span>

                <strong>
                    {{ $namaUnit }}
                </strong>

            </div>

            <div class="auditor-detail-item auditor-detail-item-wide">

                <span>
                    Hierarki Isi Standar
                </span>

                <strong>
                    {{ $teksHierarki }}
                </strong>

            </div>

            <div class="auditor-detail-item auditor-detail-item-wide">

                <span>
                    Indikator
                </span>

                <strong>
                    {{ $deskripsiIndikator }}
                </strong>

            </div>

            <div class="auditor-detail-item">

                <span>
                    Nama Auditee
                </span>

                <strong>
                    {{ $namaAuditee }}
                </strong>

            </div>

            <div class="auditor-detail-item">

                <span>
                    Email Auditee
                </span>

                <strong>
                    {{ $emailAuditee }}
                </strong>

            </div>

        </div>

    </section>

    {{-- =====================================================
         HASIL DAN BUKTI PENERAPAN
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    HASIL PENERAPAN
                </span>

                <h3>
                    Hasil dan Bukti Auditee
                </h3>

            </div>

        </div>

        <div class="auditor-evidence-grid">

            <div class="auditor-readonly-box">

                <span>
                    Hasil Penerapan
                </span>

                <p>
                    {{
                        $deskripsiHasil !== ''
                            ? $deskripsiHasil
                            : 'Belum diisi'
                    }}
                </p>

            </div>

            <div class="auditor-readonly-box">

                <span>
                    Bukti Penerapan
                </span>

                @if($linkBukti !== '')

                    <a
                        href="{{ $linkBukti }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="auditor-evidence-button auditor-evidence-button-large"
                    >

                        <i class="bi bi-box-arrow-up-right"></i>

                        Buka Bukti Penerapan

                    </a>

                @else

                    <p>
                        Bukti belum tersedia.
                    </p>

                @endif

            </div>

        </div>

    </section>

    {{-- =====================================================
         ISI TEMUAN
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    TEMUAN AUDITOR
                </span>

                <h3>
                    Isi Temuan Audit
                </h3>

            </div>

        </div>

        <div class="auditor-finding-content">

            <div class="auditor-finding-icon">

                <i class="bi bi-exclamation-diamond-fill"></i>

            </div>

            <div>

                <span>
                    Temuan
                </span>

                <p>
                    {{ $temuan->temuan ?? '-' }}
                </p>

            </div>

        </div>

    </section>

    {{-- =====================================================
         TANGGAPAN AUDITEE
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    TINDAK LANJUT AUDITEE
                </span>

                <h3>
                    Tanggapan Auditee
                </h3>

                <p>
                    Daftar tanggapan yang diberikan oleh Auditee
                    terhadap temuan ini.
                </p>

            </div>

            <span class="auditor-response-badge">

                <i class="bi bi-chat-left-text"></i>

                {{ $daftarTanggapan->count() }}
                tanggapan

            </span>

        </div>

        <div class="auditor-response-list">

            @forelse($daftarTanggapan as $tanggapan)

                @php
                    $namaPenanggap =
                        $tanggapan->user?->nama
                        ?? $namaAuditee;

                    $isiTanggapan =
                        $tanggapan->tanggapan
                        ?? $tanggapan->deskripsi_tanggapan
                        ?? $tanggapan->isi_tanggapan
                        ?? '-';

                    $tanggalTanggapan =
                        $tanggapan->created_at
                            ? $tanggapan->created_at
                                ->format('d-m-Y H:i')
                            : '-';

                    $initialPenanggap = strtoupper(
                        substr(
                            trim(
                                (string) $namaPenanggap
                            ),
                            0,
                            1
                        )
                    );
                @endphp

                <article class="auditor-response-item">

                    <div class="auditor-response-avatar">
                        {{ $initialPenanggap }}
                    </div>

                    <div class="auditor-response-content">

                        <div class="auditor-response-header">

                            <div>

                                <strong>
                                    {{ $namaPenanggap }}
                                </strong>

                                <span>
                                    Auditee
                                </span>

                            </div>

                            <small>
                                {{ $tanggalTanggapan }}
                            </small>

                        </div>

                        <p>
                            {{ $isiTanggapan }}
                        </p>

                    </div>

                </article>

            @empty

                <div class="auditor-empty-response">

                    <i class="bi bi-chat-square-dots"></i>

                    <strong>
                        Belum ada tanggapan
                    </strong>

                    <span>
                        Auditee belum memberikan tanggapan
                        terhadap temuan ini.
                    </span>

                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection