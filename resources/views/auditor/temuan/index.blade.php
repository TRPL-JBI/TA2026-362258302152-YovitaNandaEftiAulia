@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/app/auditor-temuan-penerapan.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('css/app/14-auditor-temuan-alur-audit.css') }}"
    >

    <style>
        .audit-period-card-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .audit-period-card {
            position: relative;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            min-height: 190px;
            padding: 24px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
        }

        .audit-period-card::after {
            content: '';
            position: absolute;
            right: -35px;
            bottom: -55px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: rgba(79, 70, 229, 0.06);
        }

        .audit-period-card-content {
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .audit-period-card-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 12px;
            font-size: 12px;
            font-weight: 800;
            color: #6366f1;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .audit-period-card h3 {
            margin: 0 0 8px;
            font-size: 20px;
            color: #111827;
        }

        .audit-period-card p {
            max-width: 560px;
            margin: 0 0 18px;
            color: #64748b;
            line-height: 1.65;
        }

        .audit-period-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 18px;
        }

        .audit-period-card-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 11px;
            border-radius: 999px;
            background: #eef2ff;
            color: #4338ca;
            font-size: 12px;
            font-weight: 700;
        }

        .audit-period-card-badge.is-empty {
            background: #f1f5f9;
            color: #64748b;
        }

        .audit-period-card-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 10px 16px;
            border-radius: 12px;
            background: #4f46e5;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .audit-period-card-action:hover {
            background: #4338ca;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .audit-period-card-icon {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 58px;
            width: 58px;
            height: 58px;
            border-radius: 17px;
            background: #eef2ff;
            color: #4f46e5;
            font-size: 26px;
        }

        .audit-period-card.is-attachment .audit-period-card-label,
        .audit-period-card.is-attachment .audit-period-card-icon {
            color: #0891b2;
        }

        .audit-period-card.is-attachment .audit-period-card-icon {
            background: #ecfeff;
        }

        .audit-period-card.is-attachment::after {
            background: rgba(6, 182, 212, 0.06);
        }

        @media (max-width: 900px) {
            .audit-period-card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

@php
    use Illuminate\Support\Str;

    /*
    |--------------------------------------------------------------------------
    | DATA HALAMAN
    |--------------------------------------------------------------------------
    */

    $data = collect($data ?? [])
        ->unique('id')
        ->values();

    $totalPenerapan =
        $totalPenerapan ?? 0;

    $penerapanLengkap =
        $penerapanLengkap ?? 0;

    $penerapanBelumLengkap =
        $penerapanBelumLengkap ?? 0;

    $totalTemuan =
        $totalTemuan ?? 0;

    $temuanOpen =
        $temuanOpen ?? 0;

    $temuanClosed =
        $temuanClosed ?? 0;

    /*
    |--------------------------------------------------------------------------
    | DATA PERIODE UNTUK CARD KESIMPULAN DAN LAMPIRAN
    |--------------------------------------------------------------------------
    */

    $semuaPenerapanHalaman = $data
        ->flatMap(function ($indikator) {
            return collect(
                $indikator->penerapan ?? []
            );
        });

    $periodeAktif = $semuaPenerapanHalaman
        ->map(function ($penerapan) {
            return $penerapan
                ?->standarmutuPeriode
                ?->periodeAmi;
        })
        ->filter()
        ->unique('id')
        ->first();

    $daftarKesimpulanPeriode = collect(
        $periodeAktif?->kesimpulanAudit ?? []
    )
        ->sortByDesc('id')
        ->values();

    $kesimpulanPeriodeTerbaru =
        $daftarKesimpulanPeriode->first();

    $jumlahKesimpulanPeriode =
        $daftarKesimpulanPeriode->count();

    $daftarLampiranPeriode = collect(
        $periodeAktif?->lampiran ?? []
    )
        ->sortByDesc('id')
        ->values();

    $lampiranPeriodeTerbaru =
        $daftarLampiranPeriode->first();

    $jumlahLampiranPeriode =
        $daftarLampiranPeriode->count();
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
                Penilaian Audit
            </h2>

            <p>
                Seluruh data audit ditampilkan sejajar berdasarkan
                indikator. Auditor dapat memberikan penilaian berdasarkan
                hasil dan bukti penerapan yang dikirim oleh Auditee.
            </p>

        </div>

        <div class="auditor-temuan-header-icon">

            <i class="bi bi-clipboard2-check"></i>

        </div>

    </section>

    {{-- =====================================================
         PESAN
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
                <i class="bi bi-clipboard-check"></i>
            </div>

            <div>

                <span>
                    Total Penilaian
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
         INFORMASI
    ====================================================== --}}

    <section class="auditor-temuan-info">

        <div class="auditor-temuan-info-icon">

            <i class="bi bi-info-circle"></i>

        </div>

        <div>

            <strong>
                Alur data audit
            </strong>

            <p>
                Penilaian Audit dibuat berdasarkan hasil dan bukti
                penerapan Auditee. Tanggapan dan Akar Masalah mengikuti
                hasil Penilaian Audit. Rekomendasi tersimpan bersama
                setiap Penilaian Audit.
            </p>

        </div>

    </section>

    {{-- =====================================================
         CARD KESIMPULAN DAN LAMPIRAN PERIODE
    ====================================================== --}}

    <section class="audit-period-card-grid">

        {{-- KESIMPULAN PERIODE --}}

        <article class="audit-period-card">

            <div class="audit-period-card-content">

                <span class="audit-period-card-label">

                    <i class="bi bi-file-earmark-check"></i>

                    Kesimpulan Periode

                </span>

                <h3>
                    Kesimpulan Audit
                </h3>

                <p>
                    Ringkasan hasil pelaksanaan Audit Mutu Internal
                    untuk Periode
                    {{ $periodeAktif?->tahun ?? '-' }}.
                </p>

                <div class="audit-period-card-meta">

                    @if($kesimpulanPeriodeTerbaru)

                        <span class="audit-period-card-badge">

                            <i class="bi bi-check-circle-fill"></i>

                            {{ $jumlahKesimpulanPeriode }}
                            Kesimpulan Tersedia

                        </span>

                    @else

                        <span class="audit-period-card-badge is-empty">

                            <i class="bi bi-dash-circle"></i>

                            Belum Ada Kesimpulan

                        </span>

                    @endif

                </div>

                @if($kesimpulanPeriodeTerbaru)

                    <a
                        href="{{
                            route(
                                'auditor.kesimpulan.show',
                                $kesimpulanPeriodeTerbaru->id
                            )
                        }}"
                        class="audit-period-card-action"
                    >

                        <i class="bi bi-eye"></i>

                        Lihat Kesimpulan

                    </a>

                @elseif($periodeAktif)

                    <a
                        href="{{
                            route(
                                'auditor.kesimpulan.create',
                                [
                                    'id_periode_ami' =>
                                        $periodeAktif->id,
                                ]
                            )
                        }}"
                        class="audit-period-card-action"
                    >

                        <i class="bi bi-plus-circle"></i>

                        Tambah Kesimpulan

                    </a>

                @endif

            </div>

            <div class="audit-period-card-icon">

                <i class="bi bi-file-earmark-text"></i>

            </div>

        </article>

        {{-- LAMPIRAN PERIODE --}}

        <article class="audit-period-card is-attachment">

            <div class="audit-period-card-content">

                <span class="audit-period-card-label">

                    <i class="bi bi-paperclip"></i>

                    Lampiran Periode

                </span>

                <h3>
                    Lampiran Audit
                </h3>

                <p>
                    Dokumen pendukung pelaksanaan Audit Mutu Internal
                    untuk Periode
                    {{ $periodeAktif?->tahun ?? '-' }}.
                </p>

                <div class="audit-period-card-meta">

                    @if($lampiranPeriodeTerbaru)

                        <span class="audit-period-card-badge">

                            <i class="bi bi-check-circle-fill"></i>

                            {{ $jumlahLampiranPeriode }}
                            Lampiran Tersedia

                        </span>

                    @else

                        <span class="audit-period-card-badge is-empty">

                            <i class="bi bi-dash-circle"></i>

                            Belum Ada Lampiran

                        </span>

                    @endif

                </div>

                @if($lampiranPeriodeTerbaru)

                    <a
                        href="{{ route('auditor.lampiran.index') }}"
                        class="audit-period-card-action"
                    >

                        <i class="bi bi-collection"></i>

                        Lihat Lampiran

                    </a>

                @elseif($periodeAktif)

                    <a
                        href="{{
                            route(
                                'auditor.lampiran.create',
                                [
                                    'id_periode_ami' =>
                                        $periodeAktif->id,
                                ]
                            )
                        }}"
                        class="audit-period-card-action"
                    >

                        <i class="bi bi-plus-circle"></i>

                        Tambah Lampiran

                    </a>

                @endif

            </div>

            <div class="audit-period-card-icon">

                <i class="bi bi-folder2-open"></i>

            </div>

        </article>

    </section>

    {{-- =====================================================
         TABEL
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    DATA PEMERIKSAAN
                </span>

                <h3>
                    Penerapan Standar dan Penilaian Audit
                </h3>

                <p>
                    Setiap baris mewakili satu indikator.
                </p>

            </div>

            <div class="audit-scroll-hint">

                <i class="bi bi-arrow-left-right"></i>

                <span>
                    Geser tabel ke kanan
                </span>

            </div>

        </div>

        <div class="auditor-temuan-table-wrap">

            <table class="auditor-temuan-table audit-flow-table">

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

                        <th class="column-indicator">
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

                        <th class="audit-flow-heading heading-finding">
                            Penilaian Audit
                        </th>

                        <th class="audit-flow-heading heading-response">
                            Tanggapan Auditee
                        </th>

                        <th class="audit-flow-heading heading-root">
                            Akar Masalah
                        </th>

                        <th class="audit-flow-heading heading-recommendation">
                            Rekomendasi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $indikator)

                        @php
                            /*
                            |--------------------------------------------------------------------------
                            | PENERAPAN
                            |--------------------------------------------------------------------------
                            */

                            $daftarPenerapan = collect(
                                $indikator->penerapan ?? []
                            );

                            $penerapan = $daftarPenerapan
                                ->sortByDesc('id')
                                ->first();

                            /*
                            |--------------------------------------------------------------------------
                            | STRUKTUR STANDAR
                            |--------------------------------------------------------------------------
                            */

                            $isiStandar =
                                $indikator->isiStandar;

                            $parent1 =
                                $isiStandar?->parent;

                            $parent2 =
                                $parent1?->parent;

                            $parent3 =
                                $parent2?->parent;

                            $standarMutu =
                                $isiStandar?->standarMutu
                                ?? $parent1?->standarMutu
                                ?? $parent2?->standarMutu
                                ?? $parent3?->standarMutu
                                ?? $penerapan
                                    ?->standarmutuPeriode
                                    ?->standarMutu;

                            $namaStandar =
                                $standarMutu?->nama_standar_mutu
                                ?? '-';

                            /*
                            |--------------------------------------------------------------------------
                            | PERIODE AMI
                            |--------------------------------------------------------------------------
                            */

                            $periodeAmi =
                                $penerapan
                                    ?->standarmutuPeriode
                                    ?->periodeAmi;

                            /*
                            |--------------------------------------------------------------------------
                            | HIERARKI ISI STANDAR
                            |--------------------------------------------------------------------------
                            */

                            $ambilNamaIsi = function ($isi) {
                                if (!$isi) {
                                    return null;
                                }

                                $nama = trim(
                                    (string) (
                                        $isi->nama_isi_standar
                                        ?? $isi->nama_standar
                                        ?? $isi->nama
                                        ?? $isi->isi_standar
                                        ?? ''
                                    )
                                );

                                return $nama !== ''
                                    ? $nama
                                    : null;
                            };

                            $hierarkiIsi = collect([
                                $parent3,
                                $parent2,
                                $parent1,
                                $isiStandar,
                            ])
                                ->filter()
                                ->unique('id')
                                ->map(function ($isi) use ($ambilNamaIsi) {
                                    return [
                                        'id' =>
                                            $isi->id ?? null,

                                        'nama' =>
                                            $ambilNamaIsi($isi),
                                    ];
                                })
                                ->filter(function ($item) {
                                    return !empty($item['nama']);
                                })
                                ->values();

                            $isiUtama = data_get(
                                $hierarkiIsi->first(),
                                'nama',
                                '-'
                            );

                            $subIsi = $hierarkiIsi
                                ->slice(1)
                                ->pluck('nama')
                                ->filter()
                                ->implode(' → ');

                            if (trim((string) $subIsi) === '') {
                                $namaParentLangsung =
                                    $ambilNamaIsi($parent1);

                                $namaIsiLangsung =
                                    $ambilNamaIsi($isiStandar);

                                if (
                                    $namaParentLangsung !== null
                                    && $namaIsiLangsung !== null
                                    && $namaParentLangsung
                                        !== $namaIsiLangsung
                                ) {
                                    $isiUtama =
                                        $namaParentLangsung;

                                    $subIsi =
                                        $namaIsiLangsung;
                                }
                            }

                            if (trim((string) $subIsi) === '') {
                                $subIsi = '-';
                            }

                            /*
                            |--------------------------------------------------------------------------
                            | INDIKATOR
                            |--------------------------------------------------------------------------
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
                            | PENERAPAN AUDITEE
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
                            | PENILAIAN AUDIT
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

                            $jenisPenilaian =
                                $temuanPertama?->jenis_temuan;

                            $labelJenisPenilaian = match (
                                $jenisPenilaian
                            ) {
                                'sesuai_standar' =>
                                    'Sesuai Standar',

                                'kts' =>
                                    'KTS',

                                'ob' =>
                                    'OB',

                                default =>
                                    '-',
                            };

                            $skalaSkor =
                                $penerapan
                                    ?->skor
                                    ?->skalaSkor;

                            $nilaiSkor =
                                $skalaSkor?->nilai_skor;

                            $labelSkor =
                                $skalaSkor?->label_skor;

                            /*
                            |--------------------------------------------------------------------------
                            | TANGGAPAN AUDITEE
                            |--------------------------------------------------------------------------
                            */

                            $daftarTanggapan =
                                $daftarTemuan
                                    ->flatMap(
                                        function ($temuan) {
                                            return collect(
                                                $temuan->tanggapan
                                                ?? []
                                            );
                                        }
                                    )
                                    ->sortByDesc('id')
                                    ->values();

                            $tanggapanTerbaru =
                                $daftarTanggapan->first();

                            $jumlahTanggapan =
                                $daftarTanggapan->count();

                            /*
                            |--------------------------------------------------------------------------
                            | AKAR MASALAH
                            |--------------------------------------------------------------------------
                            */

                            $daftarAkarMasalah =
                                $daftarTemuan
                                    ->flatMap(
                                        function ($temuan) {
                                            return collect(
                                                $temuan->akarMasalah
                                                ?? []
                                            );
                                        }
                                    )
                                    ->sortByDesc('id')
                                    ->values();

                            $akarMasalahTerbaru =
                                $daftarAkarMasalah->first();

                            $jumlahAkarMasalah =
                                $daftarAkarMasalah->count();

                            /*
                            |--------------------------------------------------------------------------
                            | REKOMENDASI PENINGKATAN
                            |--------------------------------------------------------------------------
                            */

                            $daftarRekomendasi = collect(
                                $penerapan?->rekomendasi
                                ?? []
                            )
                                ->sortByDesc('id')
                                ->values();

                            $rekomendasiTerbaru =
                                $daftarRekomendasi->first();

                            $jumlahRekomendasi =
                                $daftarRekomendasi->count();
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
                                            Periode
                                            {{ $periodeAmi->tahun ?? '-' }}
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

                            <td class="column-indicator">

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

                                    <div
                                        class="auditor-result-text"
                                        title="{{ $deskripsiHasil }}"
                                    >
                                        {{
                                            Str::limit(
                                                $deskripsiHasil,
                                                150
                                            )
                                        }}
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

                            {{-- PENILAIAN AUDIT --}}

                            <td class="audit-flow-column">

                                @if($temuanPertama)

                                    <div class="audit-flow-cell">

                                        <div class="audit-flow-cell-top">

                                            <span
                                                class="
                                                    audit-flow-label
                                                    label-finding
                                                "
                                            >

                                                <i class="bi bi-clipboard-check"></i>

                                                {{ $jumlahTemuan }}
                                                Penilaian

                                            </span>

                                        </div>

                                        <strong class="audit-flow-subtitle">
                                            {{ $labelJenisPenilaian }}
                                        </strong>

                                        @if($nilaiSkor !== null)

                                            <p>
                                                Skor:
                                                {{ $nilaiSkor }}
                                                -
                                                {{ $labelSkor ?? '-' }}
                                            </p>

                                        @endif

                                        @if(
                                            in_array(
                                                $jenisPenilaian,
                                                ['kts', 'ob'],
                                                true
                                            )
                                            && trim(
                                                (string) (
                                                    $temuanPertama->temuan
                                                    ?? ''
                                                )
                                            ) !== ''
                                        )

                                            <p
                                                title="{{
                                                    $temuanPertama->temuan
                                                }}"
                                            >
                                                {{
                                                    Str::limit(
                                                        $temuanPertama->temuan,
                                                        130
                                                    )
                                                }}
                                            </p>

                                        @elseif(
                                            $jenisPenilaian
                                            === 'sesuai_standar'
                                        )

                                            <p>
                                                Penerapan telah dinilai
                                                sesuai dengan standar
                                                yang ditetapkan.
                                            </p>

                                        @endif

                                        <div class="audit-flow-buttons">

                                            <a
                                                href="{{
                                                    route(
                                                        'auditor.temuan.show',
                                                        $temuanPertama->id
                                                    )
                                                }}"
                                                class="
                                                    audit-flow-button
                                                    button-view
                                                "
                                            >

                                                <i class="bi bi-eye"></i>

                                                Lihat

                                            </a>

                                            <a
                                                href="{{
                                                    route(
                                                        'auditor.temuan.edit',
                                                        $temuanPertama->id
                                                    )
                                                }}"
                                                class="
                                                    audit-flow-button
                                                    button-edit
                                                "
                                            >

                                                <i class="bi bi-pencil-square"></i>

                                                Edit

                                            </a>

                                        </div>

                                    </div>

                                @else

                                    <div class="audit-flow-empty">

                                        <i class="bi bi-clipboard-check"></i>

                                        <span>
                                            Belum ada Penilaian
                                        </span>

                                        @if($penerapanLengkapItem)

                                            <a
                                                href="{{
                                                    route(
                                                        'auditor.temuan.create',
                                                        $penerapan->id
                                                    )
                                                }}"
                                                class="
                                                    audit-flow-button
                                                    button-create
                                                "
                                            >

                                                <i class="bi bi-plus-circle"></i>

                                                Tambah Penilaian

                                            </a>

                                        @else

                                            <small>
                                                Menunggu penerapan lengkap
                                            </small>

                                        @endif

                                    </div>

                                @endif

                            </td>

                            {{-- TANGGAPAN AUDITEE --}}

                            <td class="audit-flow-column">

                                @if($tanggapanTerbaru)

                                    <div class="audit-flow-cell">

                                        <div class="audit-flow-cell-top">

                                            <span
                                                class="
                                                    audit-flow-label
                                                    label-response
                                                "
                                            >

                                                <i class="bi bi-chat-left-text"></i>

                                                {{ $jumlahTanggapan }}
                                                Tanggapan

                                            </span>

                                        </div>

                                        <p
                                            title="{{
                                                $tanggapanTerbaru
                                                    ->tanggapan
                                            }}"
                                        >
                                            {{
                                                Str::limit(
                                                    $tanggapanTerbaru
                                                        ->tanggapan,
                                                    130
                                                )
                                            }}
                                        </p>

                                        <div class="audit-flow-buttons">

                                            <a
                                                href="{{
                                                    route(
                                                        'auditor.tanggapan.show',
                                                        $tanggapanTerbaru->id
                                                    )
                                                }}"
                                                class="
                                                    audit-flow-button
                                                    button-view
                                                "
                                            >

                                                <i class="bi bi-eye"></i>

                                                Lihat

                                            </a>

                                        </div>

                                    </div>

                                @else

                                    <div class="audit-flow-empty">

                                        <i class="bi bi-chat-square-dots"></i>

                                        <span>
                                            Belum ada Tanggapan
                                        </span>

                                        <small>
                                            Diisi oleh Auditee
                                        </small>

                                    </div>

                                @endif

                            </td>

                            {{-- AKAR MASALAH --}}

                            <td class="audit-flow-column">

                                @if($akarMasalahTerbaru)

                                    <div class="audit-flow-cell">

                                        <div class="audit-flow-cell-top">

                                            <span
                                                class="
                                                    audit-flow-label
                                                    label-root
                                                "
                                            >

                                                <i class="bi bi-diagram-3"></i>

                                                {{ $jumlahAkarMasalah }}
                                                Data

                                            </span>

                                        </div>

                                        <p
                                            title="{{
                                                $akarMasalahTerbaru
                                                    ->akar_masalah
                                            }}"
                                        >
                                            {{
                                                Str::limit(
                                                    $akarMasalahTerbaru
                                                        ->akar_masalah,
                                                    130
                                                )
                                            }}
                                        </p>

                                        <div class="audit-flow-buttons">

                                            <a
                                                href="{{
                                                    route(
                                                        'auditor.akarmasalah.show',
                                                        $akarMasalahTerbaru->id
                                                    )
                                                }}"
                                                class="
                                                    audit-flow-button
                                                    button-view
                                                "
                                            >

                                                <i class="bi bi-eye"></i>

                                                Lihat

                                            </a>

                                            <a
                                                href="{{
                                                    route(
                                                        'auditor.akarmasalah.edit',
                                                        $akarMasalahTerbaru->id
                                                    )
                                                }}"
                                                class="
                                                    audit-flow-button
                                                    button-edit
                                                "
                                            >

                                                <i class="bi bi-pencil-square"></i>

                                                Edit

                                            </a>

                                        </div>

                                    </div>

                                @else

                                    <div class="audit-flow-empty">

                                        <i class="bi bi-diagram-3"></i>

                                        <span>
                                            Belum ada Akar Masalah
                                        </span>

                                        @if($temuanPertama)

                                            <a
                                                href="{{
                                                    route(
                                                        'auditor.akarmasalah.create',
                                                        [
                                                            'id_temuan' =>
                                                                $temuanPertama
                                                                    ->id,
                                                        ]
                                                    )
                                                }}"
                                                class="
                                                    audit-flow-button
                                                    button-create
                                                "
                                            >

                                                <i class="bi bi-plus-circle"></i>

                                                Tambah

                                            </a>

                                        @else

                                            <small>
                                                Menunggu Penilaian
                                            </small>

                                        @endif

                                    </div>

                                @endif

                            </td>

                            {{-- REKOMENDASI PENINGKATAN --}}

                            <td class="audit-flow-column">

                                @if($rekomendasiTerbaru)

                                    <div class="audit-flow-cell">

                                        <div class="audit-flow-cell-top">

                                            <span
                                                class="
                                                    audit-flow-label
                                                    label-recommendation
                                                "
                                            >

                                                <i class="bi bi-lightbulb"></i>

                                                {{ $jumlahRekomendasi }}
                                                Rekomendasi

                                            </span>

                                        </div>

                                        @if(
                                            trim(
                                                (string) (
                                                    $rekomendasiTerbaru
                                                        ->aspek
                                                    ?? ''
                                                )
                                            ) !== ''
                                        )

                                            <strong class="audit-flow-subtitle">
                                                {{
                                                    Str::limit(
                                                        $rekomendasiTerbaru
                                                            ->aspek,
                                                        60
                                                    )
                                                }}
                                            </strong>

                                        @endif

                                        <p
                                            title="{{
                                                $rekomendasiTerbaru
                                                    ->rekomendasi
                                            }}"
                                        >
                                            {{
                                                Str::limit(
                                                    $rekomendasiTerbaru
                                                        ->rekomendasi,
                                                    130
                                                )
                                            }}
                                        </p>

                                        <div class="audit-flow-buttons">

    @php
        $temuanRekomendasi = $penerapan->temuan;

        if (
            $temuanRekomendasi
            instanceof \Illuminate\Support\Collection
        ) {
            $temuanRekomendasi = $temuanRekomendasi
                ->sortByDesc('id')
                ->first();
        }
    @endphp

    @if($temuanRekomendasi)

        <a
            href="{{
                route(
                    'auditor.temuan.show',
                    $temuanRekomendasi->id
                )
            }}"
            class="
                audit-flow-button
                button-view
            "
        >
            <i class="bi bi-eye"></i>

            Lihat

        </a>

        <a
            href="{{
                route(
                    'auditor.temuan.show',
                    $temuanRekomendasi->id
                )
            }}"
            class="
                audit-flow-button
                button-edit
            "
        >
            <i class="bi bi-pencil-square"></i>

            Edit

        </a>

    @endif

</div>

                                    </div>

                                @else

                                    <div class="audit-flow-empty">

                                        <i class="bi bi-lightbulb"></i>

                                        <span>
                                            Belum ada Rekomendasi
                                        </span>

                                        @if($penerapan)

                                            @if($penerapan)

    @php
        $temuanRekomendasi = $penerapan->temuan;

        if ($temuanRekomendasi instanceof \Illuminate\Support\Collection) {
            $temuanRekomendasi = $temuanRekomendasi
                ->sortByDesc('id')
                ->first();
        }
    @endphp

    @if($temuanRekomendasi)

        <a
            href="{{ route('auditor.temuan.show', $temuanRekomendasi->id) }}"
            class="audit-flow-button button-create"
        >
            <i class="bi bi-lightbulb"></i>
            Kelola Rekomendasi
        </a>

    @else

        <span class="audit-flow-empty">
            Menunggu Temuan
        </span>

    @endif

@endif

                                                <i class="bi bi-plus-circle"></i>

                                                Tambah

                                            </a>

                                        @else

                                            <small>
                                                Menunggu Penerapan
                                            </small>

                                        @endif

                                    </div>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="12"
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