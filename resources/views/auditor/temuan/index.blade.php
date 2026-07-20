@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/auditor-temuan-penerapan.css') }}"
    >
@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | PASTIKAN DATA BERBENTUK COLLECTION DAN TIDAK DUPLIKAT
    |--------------------------------------------------------------------------
    |
    | Data dari controller harus berupa collection IndikatorStandar.
    | Setiap indikator dibatasi berdasarkan ID agar tidak tampil dua kali.
    |
    */

    $data = collect($data ?? [])
        ->unique('id')
        ->values();

    $totalPenerapan = $totalPenerapan ?? 0;
    $penerapanLengkap = $penerapanLengkap ?? 0;
    $penerapanBelumLengkap = $penerapanBelumLengkap ?? 0;
    $totalTemuan = $totalTemuan ?? 0;
    $temuanOpen = $temuanOpen ?? 0;
    $temuanClosed = $temuanClosed ?? 0;
@endphp

<div class="auditor-temuan-page">

    {{-- =====================================================
         HEADER HALAMAN
    ====================================================== --}}

    <section class="auditor-temuan-header">

        <div>
            <span class="auditor-temuan-eyebrow">
                AUDIT MUTU INTERNAL
            </span>

            <h2>
                Temuan Audit
            </h2>

            <p>
                Auditor memeriksa hasil dan bukti penerapan yang
                dimasukkan Auditee. Temuan hanya dapat ditambahkan
                jika hasil dan bukti penerapan sudah lengkap.
            </p>
        </div>

        <div class="auditor-temuan-header-icon">
            <i class="bi bi-clipboard2-check"></i>
        </div>

    </section>

    {{-- =====================================================
         PESAN SUKSES DAN ERROR
    ====================================================== --}}

    @if(session('success'))

        <div class="auditor-alert auditor-alert-success">
            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>
        </div>

    @endif

    @if(session('error'))

        <div class="auditor-alert auditor-alert-danger">
            <i class="bi bi-exclamation-circle-fill"></i>

            <span>
                {{ session('error') }}
            </span>
        </div>

    @endif

    {{-- =====================================================
         STATISTIK
    ====================================================== --}}

    <section class="auditor-temuan-stat-grid">

        <div class="auditor-temuan-stat-card">

            <div class="auditor-temuan-stat-icon stat-blue">
                <i class="bi bi-list-check"></i>
            </div>

            <div>
                <span>
                    Total Indikator
                </span>

                <strong>
                    {{ $data->count() }}
                </strong>

                <small>
                    Indikator pada standar yang diaudit
                </small>
            </div>

        </div>

        <div class="auditor-temuan-stat-card">

            <div class="auditor-temuan-stat-icon stat-green">
                <i class="bi bi-file-earmark-check"></i>
            </div>

            <div>
                <span>
                    Penerapan Lengkap
                </span>

                <strong>
                    {{ $penerapanLengkap }}
                </strong>

                <small>
                    Hasil dan bukti sudah tersedia
                </small>
            </div>

        </div>

        <div class="auditor-temuan-stat-card">

            <div class="auditor-temuan-stat-icon stat-orange">
                <i class="bi bi-hourglass-split"></i>
            </div>

            <div>
                <span>
                    Belum Lengkap
                </span>

                <strong>
                    {{ $penerapanBelumLengkap }}
                </strong>

                <small>
                    Menunggu kelengkapan Auditee
                </small>
            </div>

        </div>

        <div class="auditor-temuan-stat-card">

            <div class="auditor-temuan-stat-icon stat-purple">
                <i class="bi bi-exclamation-diamond"></i>
            </div>

            <div>
                <span>
                    Total Temuan
                </span>

                <strong>
                    {{ $totalTemuan }}
                </strong>

                <small>
                    {{ $temuanOpen }} open dan
                    {{ $temuanClosed }} closed
                </small>
            </div>

        </div>

    </section>

    {{-- =====================================================
         INFORMASI HAK AKSES
    ====================================================== --}}

    <section class="auditor-temuan-info">

        <div class="auditor-temuan-info-icon">
            <i class="bi bi-info-circle"></i>
        </div>

        <div>
            <strong>
                Hak akses Auditor
            </strong>

            <p>
                Auditor hanya dapat melihat data penerapan milik
                Auditee. Auditor tidak dapat mengubah hasil maupun
                bukti penerapan. Auditor hanya dapat mengelola
                Temuan Audit.
            </p>
        </div>

    </section>

    {{-- =====================================================
         TABEL TEMUAN AUDIT
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>
                <span class="auditor-temuan-section-label">
                    DATA PEMERIKSAAN
                </span>

                <h3>
                    Penerapan Standar dan Temuan Audit
                </h3>

                <p>
                    Setiap baris mewakili satu indikator standar.
                </p>
            </div>

        </div>

        <div class="auditor-temuan-table-wrap">

            <table class="auditor-temuan-table">

                <thead>
                    <tr>
                        <th class="column-number">
                            No.
                        </th>

                        <th>
                            Standar Mutu
                        </th>

                        <th>
                            Isi Standar
                        </th>

                        <th>
                            Sub Isi Standar
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
                            Status Penerapan
                        </th>

                        <th>
                            Status Temuan
                        </th>

                        <th class="column-action">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($data as $indikator)

                        @php
                            /*
                            |--------------------------------------------------------------------------
                            | PENERAPAN UNTUK INDIKATOR
                            |--------------------------------------------------------------------------
                            |
                            | Relasi penerapan berupa hasMany.
                            | Controller sudah memfilter penerapan berdasarkan
                            | periode penugasan Auditor.
                            |
                            */

                            $daftarPenerapan = collect(
                                $indikator->penerapan ?? []
                            );

                            $penerapan = $daftarPenerapan
                                ->sortByDesc('id')
                                ->first();

                            /*
                            |--------------------------------------------------------------------------
                            | ISI STANDAR
                            |--------------------------------------------------------------------------
                            */

                            $isiStandar = $indikator->isiStandar;

                            $parent1 = $isiStandar?->parent;
                            $parent2 = $parent1?->parent;
                            $parent3 = $parent2?->parent;

                            /*
                            |--------------------------------------------------------------------------
                            | STANDAR MUTU
                            |--------------------------------------------------------------------------
                            |
                            | Diambil langsung dari isi_standar_mutu agar nama
                            | standar tetap tampil meskipun penerapan belum ada.
                            |
                            */

                            $standarMutu =
                                $isiStandar?->standarMutu
                                ?? $parent1?->standarMutu
                                ?? $parent2?->standarMutu
                                ?? $parent3?->standarMutu
                                ?? $penerapan?->standarmutuPeriode?->standarMutu;

                            $namaStandar =
                                $standarMutu?->nama_standar_mutu
                                ?? '-';

                            $periodeAmi =
                                $penerapan
                                    ?->standarmutuPeriode
                                    ?->periodeAmi;

                            /*
                            |--------------------------------------------------------------------------
                            | HIERARKI ISI STANDAR
                            |--------------------------------------------------------------------------
                            |
                            | Kolom nama yang benar pada database adalah:
                            | isi_standar_mutu.nama_standar
                            |
                            */

                            $hierarkiIsi = collect([
                                $parent3,
                                $parent2,
                                $parent1,
                                $isiStandar,
                            ])
                                ->filter()
                                ->unique('id')
                                ->map(function ($isi) {
                                    return trim(
                                        (string) (
                                            $isi->nama_standar
                                            ?? ''
                                        )
                                    );
                                })
                                ->filter(function ($nama) {
                                    return $nama !== '';
                                })
                                ->values();

                            $isiUtama =
                                $hierarkiIsi->first()
                                ?? '-';

                            $subIsi =
                                $hierarkiIsi
                                    ->slice(1)
                                    ->implode(' → ');

                            if ($subIsi === '') {
                                $subIsi = '-';
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | INDIKATOR
                            |--------------------------------------------------------------------------
                            |
                            | Kolom yang benar adalah indikator_standar.deskripsi.
                            |
                            */

                            $deskripsiIndikator = trim(
                                (string) (
                                    $indikator->deskripsi
                                    ?? ''
                                )
                            );

                            if ($deskripsiIndikator === '') {
                                $deskripsiIndikator = '-';
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | DATA PENERAPAN AUDITEE
                            |--------------------------------------------------------------------------
                            */

                            $penerapanAda =
                                $penerapan !== null;

                            $namaAuditee = trim(
                                (string) (
                                    $penerapan?->user?->nama
                                    ?? ''
                                )
                            );

                            $deskripsiHasil = trim(
                                (string) (
                                    $penerapan?->deskripsi_hasil
                                    ?? ''
                                )
                            );

                            $linkBukti = trim(
                                (string) (
                                    $penerapan?->link_bukti
                                    ?? ''
                                )
                            );

                            $hasilAda =
                                $deskripsiHasil !== '';

                            $buktiAda =
                                $linkBukti !== '';

                            $penerapanLengkapItem =
                                $penerapanAda
                                && $hasilAda
                                && $buktiAda;

                            /*
                            |--------------------------------------------------------------------------
                            | DATA TEMUAN
                            |--------------------------------------------------------------------------
                            */

                            $daftarTemuan = collect(
                                $penerapan?->temuan
                                ?? []
                            )
                                ->sortByDesc('id')
                                ->values();

                            $temuanPertama =
                                $daftarTemuan->first();

                            $jumlahTemuan =
                                $daftarTemuan->count();

                            $temuanOpenAda =
                                $daftarTemuan->contains(
                                    function ($temuan) {
                                        return strtolower(
                                            trim(
                                                (string) (
                                                    $temuan->status_temuan
                                                    ?? ''
                                                )
                                            )
                                        ) === 'open';
                                    }
                                );

                            $semuaTemuanClosed =
                                $jumlahTemuan > 0
                                && !$temuanOpenAda;
                        @endphp

                        <tr>

                            {{-- NOMOR --}}
                            <td class="column-number">

                                <span class="auditor-table-number">
                                    {{ $loop->iteration }}
                                </span>

                            </td>

                            {{-- STANDAR MUTU --}}
                            <td>

                                <div class="auditor-table-main-text">

                                    <strong>
                                        {{ $namaStandar }}
                                    </strong>

                                    @if($periodeAmi)

                                        <span>
                                            Periode {{ $periodeAmi->tahun ?? '-' }}
                                        </span>

                                    @endif

                                </div>

                            </td>

                            {{-- ISI STANDAR --}}
                            <td>

                                <span class="auditor-hierarchy-badge">
                                    {{ $isiUtama }}
                                </span>

                            </td>

                            {{-- SUB ISI STANDAR --}}
                            <td>

                                <span class="auditor-sub-hierarchy">
                                    {{ $subIsi }}
                                </span>

                            </td>

                            {{-- INDIKATOR --}}
                            <td>

                                <div class="auditor-indikator-text">
                                    {{ $deskripsiIndikator }}
                                </div>

                            </td>

                            {{-- AUDITEE --}}
                            <td>

                                @if($namaAuditee !== '')

                                    <div class="auditor-user-cell">

                                        <div class="auditor-user-avatar">
                                            {{
                                                strtoupper(
                                                    substr(
                                                        $namaAuditee,
                                                        0,
                                                        1
                                                    )
                                                )
                                            }}
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

                                @else

                                    <span class="auditor-empty-value">
                                        <i class="bi bi-person-dash"></i>

                                        Belum tersedia
                                    </span>

                                @endif

                            </td>

                            {{-- HASIL PENERAPAN --}}
                            <td>

                                @if($hasilAda)

                                    <div class="auditor-result-text">
                                        {{ $deskripsiHasil }}
                                    </div>

                                @else

                                    <span class="auditor-empty-value">
                                        <i class="bi bi-dash-circle"></i>

                                        Belum diisi
                                    </span>

                                @endif

                            </td>

                            {{-- BUKTI --}}
                            <td>

                                @if($buktiAda)

                                    <a
                                        href="{{ $linkBukti }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="auditor-evidence-button"
                                    >
                                        <i class="bi bi-box-arrow-up-right"></i>

                                        Lihat Bukti
                                    </a>

                                @else

                                    <span class="auditor-empty-value">
                                        <i class="bi bi-file-earmark-x"></i>

                                        Belum tersedia
                                    </span>

                                @endif

                            </td>

                            {{-- STATUS PENERAPAN --}}
                            <td>

                                @if(!$penerapanAda)

                                    <span class="auditor-status status-waiting">
                                        <i class="bi bi-hourglass-split"></i>

                                        Menunggu Auditee
                                    </span>

                                @elseif($penerapanLengkapItem)

                                    <span class="auditor-status status-complete">
                                        <i class="bi bi-check-circle-fill"></i>

                                        Lengkap
                                    </span>

                                @else

                                    <span class="auditor-status status-partial">
                                        <i class="bi bi-exclamation-circle-fill"></i>

                                        Belum Lengkap
                                    </span>

                                @endif

                            </td>

                            {{-- STATUS TEMUAN --}}
                            <td>

                                @if($jumlahTemuan === 0)

                                    <span class="auditor-status status-none">
                                        <i class="bi bi-dash-circle"></i>

                                        Belum Ada
                                    </span>

                                @elseif($temuanOpenAda)

                                    <span class="auditor-status status-open">
                                        <i class="bi bi-exclamation-circle-fill"></i>

                                        Open

                                        @if($jumlahTemuan > 1)
                                            <small>
                                                {{ $jumlahTemuan }}
                                            </small>
                                        @endif
                                    </span>

                                @elseif($semuaTemuanClosed)

                                    <span class="auditor-status status-closed">
                                        <i class="bi bi-check-circle-fill"></i>

                                        Closed

                                        @if($jumlahTemuan > 1)
                                            <small>
                                                {{ $jumlahTemuan }}
                                            </small>
                                        @endif
                                    </span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td>

                                <div class="auditor-action-group">

                                    {{-- Belum ada penerapan sama sekali --}}
                                    @if(!$penerapanAda)

                                        <button
                                            type="button"
                                            class="auditor-action-button action-disabled"
                                            disabled
                                            title="Auditee belum mengisi penerapan standar"
                                        >
                                            <i class="bi bi-lock-fill"></i>

                                            Menunggu Auditee
                                        </button>

                                    {{-- Penerapan ada tetapi belum lengkap --}}
                                    @elseif(!$penerapanLengkapItem)

                                        <button
                                            type="button"
                                            class="auditor-action-button action-disabled"
                                            disabled
                                            title="Hasil dan bukti penerapan belum lengkap"
                                        >
                                            <i class="bi bi-lock-fill"></i>

                                            Belum Lengkap
                                        </button>

                                    {{-- Penerapan lengkap dan belum ada temuan --}}
                                    @elseif($jumlahTemuan === 0)

                                        <a
                                            href="{{ route(
                                                'auditor.temuan.create',
                                                $penerapan->id
                                            ) }}"
                                            class="auditor-action-button action-create"
                                        >
                                            <i class="bi bi-plus-circle"></i>

                                            Tambah Temuan
                                        </a>

                                    {{-- Penerapan lengkap dan temuan sudah ada --}}
                                    @else

                                        @if($temuanPertama)

                                            <a
                                                href="{{ route(
                                                    'auditor.temuan.show',
                                                    $temuanPertama->id
                                                ) }}"
                                                class="auditor-action-button action-view"
                                            >
                                                <i class="bi bi-eye"></i>

                                                Lihat Temuan
                                            </a>

                                        @endif

                                        <a
                                            href="{{ route(
                                                'auditor.temuan.create',
                                                $penerapan->id
                                            ) }}"
                                            class="auditor-action-button action-create"
                                        >
                                            <i class="bi bi-plus-circle"></i>

                                            Tambah Temuan
                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="11"
                                class="auditor-empty-table"
                            >

                                <div class="auditor-empty-state">

                                    <i class="bi bi-clipboard-x"></i>

                                    <strong>
                                        Belum ada indikator
                                    </strong>

                                    <span>
                                        Belum ditemukan indikator pada
                                        Standar Mutu yang ditugaskan kepada
                                        Auditor.
                                    </span>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection