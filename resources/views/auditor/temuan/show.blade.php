@extends('layouts.auditor')

@push('styles')

    {{-- Gunakan CSS yang tersedia pada project --}}

    @if(file_exists(public_path('css/app/13-auditor-temuan-penerapan.css')))

        <link
            rel="stylesheet"
            href="{{ asset('css/app/13-auditor-temuan-penerapan.css') }}"
        >

    @elseif(file_exists(public_path('css/app/auditor-temuan-penerapan.css')))

        <link
            rel="stylesheet"
            href="{{ asset('css/app/auditor-temuan-penerapan.css') }}"
        >

    @elseif(file_exists(public_path('css/auditor-temuan-penerapan.css')))

        <link
            rel="stylesheet"
            href="{{ asset('css/auditor-temuan-penerapan.css') }}"
        >

    @endif

@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | DATA UTAMA
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | NAMA DATA
    |--------------------------------------------------------------------------
    */

    $namaStandar =
        $standarMutu?->nama_standar_mutu
        ?? $standarMutu?->nama
        ?? '-';

    $namaUnit =
        $unitKerja?->nama_unit_kerja
        ?? $unitKerja?->nama
        ?? '-';

    $namaAuditee =
        $penerapan?->user?->nama
        ?? 'Auditee';

    $deskripsiIndikator =
        $indikator?->deskripsi
        ?? $indikator?->indikator
        ?? $indikator?->nama_indikator
        ?? '-';

    /*
    |--------------------------------------------------------------------------
    | HIERARKI ISI STANDAR
    |--------------------------------------------------------------------------
    */

    $namaIsi = function ($isi) {
        return $isi?->nama_standar
            ?? $isi?->nama_isi_standar
            ?? $isi?->nama
            ?? $isi?->isi_standar
            ?? null;
    };

    $parent1 =
        $isiStandar?->parent;

    $parent2 =
        $parent1?->parent;

    $parent3 =
        $parent2?->parent;

    $hierarkiIsi = collect([
        $namaIsi($parent3),
        $namaIsi($parent2),
        $namaIsi($parent1),
        $namaIsi($isiStandar),
    ])
        ->filter(function ($value) {
            return trim(
                (string) $value
            ) !== '';
        })
        ->unique()
        ->values();

    $teksHierarki =
        $hierarkiIsi->isNotEmpty()
            ? $hierarkiIsi->implode(' → ')
            : '-';

    /*
    |--------------------------------------------------------------------------
    | PENERAPAN
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | STATUS TEMUAN
    |--------------------------------------------------------------------------
    */

    $statusTemuan = strtolower(
        trim(
            (string) (
                $temuan->status_temuan
                ?? 'open'
            )
        )
    );

    $temuanClosed =
        $statusTemuan === 'closed';

    /*
    |--------------------------------------------------------------------------
    | TANGGAPAN
    |--------------------------------------------------------------------------
    */

    $relasiTanggapan =
        $temuan->tanggapan;

    if (
        $relasiTanggapan
        instanceof \Illuminate\Support\Collection
    ) {
        $daftarTanggapan =
            $relasiTanggapan
                ->sortByDesc('id')
                ->values();
    } else {
        $daftarTanggapan = collect(
            array_filter([
                $relasiTanggapan,
            ])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AKAR MASALAH
    |--------------------------------------------------------------------------
    */

    $relasiAkarMasalah =
        $temuan->akarMasalah;

    if (
        $relasiAkarMasalah
        instanceof \Illuminate\Support\Collection
    ) {
        $daftarAkarMasalah =
            $relasiAkarMasalah
                ->sortByDesc('id')
                ->values();
    } else {
        $daftarAkarMasalah = collect(
            array_filter([
                $relasiAkarMasalah,
            ])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REKOMENDASI
    |--------------------------------------------------------------------------
    */

    $rekomendasiData =
        $temuan->rekomendasi;

    if (
        $rekomendasiData
        instanceof \Illuminate\Support\Collection
    ) {
        $rekomendasiData =
            $rekomendasiData
                ->sortByDesc('id')
                ->first();
    }
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
                Informasi penerapan, temuan, tanggapan,
                akar masalah, dan rekomendasi audit.
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

    @if($errors->any())

        <div class="auditor-alert auditor-alert-danger">

            <i class="bi bi-exclamation-triangle-fill"></i>

            <div>

                <strong>
                    Data belum dapat disimpan
                </strong>

                <ul class="auditor-error-list">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        </div>

    @endif

    {{-- =====================================================
         TOMBOL AKSI
    ====================================================== --}}

    <section class="auditor-detail-actions">

        <a
            href="{{ route('auditor.temuan.index') }}"
            class="auditor-secondary-button"
        >

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

        @if(!$temuanClosed)

            <a
                href="{{ route(
                    'auditor.temuan.edit',
                    $temuan->id
                ) }}"
                class="auditor-primary-button"
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
                onsubmit="return confirm(
                    'Apakah temuan ini yakin akan dihapus?'
                )"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="auditor-danger-button"
                >

                    <i class="bi bi-trash"></i>

                    Hapus Temuan

                </button>

            </form>

        @endif

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
                    Data penerapan yang menjadi dasar pembuatan
                    temuan audit.
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

            <div class="auditor-detail-item">

                <span>
                    Auditee
                </span>

                <strong>
                    {{ $namaAuditee }}
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
                            : 'Belum tersedia'
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
         DETAIL TEMUAN
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    TEMUAN AUDIT
                </span>

                <h3>
                    Hasil Pemeriksaan Auditor
                </h3>

            </div>

            <span
                class="auditor-inline-status {{
                    $temuanClosed
                        ? 'status-closed'
                        : 'status-open'
                }}"
            >

                <i class="bi bi-circle-fill"></i>

                {{ ucfirst($statusTemuan) }}

            </span>

        </div>

        <div class="auditor-readonly-box">

            <span>
                Jenis Temuan
            </span>

            <p>
                {{
                    !empty($temuan->jenis_temuan)
                        ? strtoupper(
                            str_replace(
                                '_',
                                ' ',
                                $temuan->jenis_temuan
                            )
                        )
                        : '-'
                }}
            </p>

        </div>

        <div
            class="auditor-readonly-box"
            style="margin-top: 16px;"
        >

            <span>
                Isi Temuan
            </span>

            <p>
                {{ $temuan->temuan ?? '-' }}
            </p>

        </div>

    </section>

    {{-- =====================================================
         TANGGAPAN AUDITEE
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    TANGGAPAN AUDITEE
                </span>

                <h3>
                    Tanggapan terhadap Temuan
                </h3>

            </div>

        </div>

        @forelse($daftarTanggapan as $tanggapan)

            <div
                class="auditor-readonly-box"
                style="margin-bottom: 14px;"
            >

                <span>
                    Tanggapan
                    @if($tanggapan->user?->nama)
                        oleh {{ $tanggapan->user->nama }}
                    @endif
                </span>

                <p>
                    {{
                        $tanggapan->tanggapan
                        ?? 'Tanggapan belum tersedia.'
                    }}
                </p>

            </div>

        @empty

            <div class="auditor-readonly-box">

                <span>
                    Status
                </span>

                <p>
                    Auditee belum memberikan tanggapan.
                </p>

            </div>

        @endforelse

    </section>

    {{-- =====================================================
         AKAR MASALAH
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    AKAR MASALAH
                </span>

                <h3>
                    Hasil Analisis Akar Masalah
                </h3>

            </div>

        </div>

        @forelse($daftarAkarMasalah as $akar)

            <div
                class="auditor-readonly-box"
                style="margin-bottom: 14px;"
            >

                <span>
                    Akar Masalah
                    @if($akar->user?->nama)
                        oleh {{ $akar->user->nama }}
                    @endif
                </span>

                <p>
                    {{
                        $akar->akar_masalah
                        ?? 'Akar masalah belum tersedia.'
                    }}
                </p>

            </div>

        @empty

            <div class="auditor-readonly-box">

                <span>
                    Status
                </span>

                <p>
                    Akar masalah belum diisi.
                </p>

            </div>

        @endforelse

    </section>

    {{-- =====================================================
         REKOMENDASI AUDIT
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    REKOMENDASI AUDIT
                </span>

                <h3>
                    Rekomendasi Perbaikan
                </h3>

                <p>
                    Rekomendasi disimpan berdasarkan temuan
                    melalui kolom id_temuan.
                </p>

            </div>

        </div>

        @if(!$temuanClosed)

            <form
                action="{{ route(
                    'auditor.temuan.rekomendasi.simpan',
                    $temuan->id
                ) }}"
                method="POST"
                class="auditor-temuan-form"
            >

                @csrf
                @method('PUT')

                <div class="auditor-form-group">

                    <label for="aspek">

                        Aspek

                        <span class="required-mark">
                            *
                        </span>

                    </label>

                    <input
                        type="text"
                        name="aspek"
                        id="aspek"
                        maxlength="255"
                        value="{{ old(
                            'aspek',
                            $rekomendasiData?->aspek
                        ) }}"
                        placeholder="Contoh: Kelengkapan dokumen"
                        required
                    >

                    @error('aspek')

                        <span class="auditor-field-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

                <div class="auditor-form-group">

                    <label for="deskripsi">

                        Deskripsi

                        <span class="required-mark">
                            *
                        </span>

                    </label>

                    <textarea
                        name="deskripsi"
                        id="deskripsi"
                        rows="5"
                        maxlength="10000"
                        placeholder="Tuliskan kondisi yang menjadi dasar rekomendasi."
                        required
                    >{{ old(
                        'deskripsi',
                        $rekomendasiData?->deskripsi
                    ) }}</textarea>

                    @error('deskripsi')

                        <span class="auditor-field-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

                <div class="auditor-form-group">

                    <label for="rekomendasi">

                        Rekomendasi

                        <span class="required-mark">
                            *
                        </span>

                    </label>

                    <textarea
                        name="rekomendasi"
                        id="rekomendasi"
                        rows="5"
                        maxlength="10000"
                        placeholder="Tuliskan saran perbaikan yang perlu dilakukan."
                        required
                    >{{ old(
                        'rekomendasi',
                        $rekomendasiData?->rekomendasi
                    ) }}</textarea>

                    @error('rekomendasi')

                        <span class="auditor-field-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

                <div class="auditor-form-actions">

                    <button
                        type="submit"
                        class="auditor-primary-button"
                    >

                        <i class="bi bi-save"></i>

                        {{
                            $rekomendasiData
                                ? 'Perbarui Rekomendasi'
                                : 'Simpan Rekomendasi'
                        }}

                    </button>

                </div>

            </form>

            @if($rekomendasiData)

                <form
                    action="{{ route(
                        'auditor.temuan.rekomendasi.hapus',
                        $temuan->id
                    ) }}"
                    method="POST"
                    style="margin-top: 14px;"
                    onsubmit="return confirm(
                        'Apakah rekomendasi ini yakin akan dihapus?'
                    )"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="auditor-danger-button"
                    >

                        <i class="bi bi-trash"></i>

                        Hapus Rekomendasi

                    </button>

                </form>

            @endif

        @else

            <div class="auditor-detail-grid">

                <div class="auditor-detail-item">

                    <span>
                        Aspek
                    </span>

                    <strong>
                        {{ $rekomendasiData?->aspek ?? '-' }}
                    </strong>

                </div>

                <div class="auditor-detail-item auditor-detail-item-wide">

                    <span>
                        Deskripsi
                    </span>

                    <strong>
                        {{ $rekomendasiData?->deskripsi ?? '-' }}
                    </strong>

                </div>

                <div class="auditor-detail-item auditor-detail-item-wide">

                    <span>
                        Rekomendasi
                    </span>

                    <strong>
                        {{ $rekomendasiData?->rekomendasi ?? '-' }}
                    </strong>

                </div>

            </div>

            <div class="auditor-form-information">

                <i class="bi bi-lock-fill"></i>

                <p>
                    Rekomendasi tidak dapat diubah karena
                    temuan sudah ditutup.
                </p>

            </div>

        @endif

    </section>

    {{-- =====================================================
         VERIFIKASI DAN TUTUP TEMUAN
    ====================================================== --}}

    @if(!$temuanClosed)

        <section class="auditor-temuan-card">

            <div class="auditor-temuan-card-header">

                <div>

                    <span class="auditor-temuan-section-label">
                        VERIFIKASI TEMUAN
                    </span>

                    <h3>
                        Tutup Temuan
                    </h3>

                    <p>
                        Temuan hanya dapat ditutup setelah
                        tanggapan, akar masalah, rekomendasi,
                        dan tindak lanjut sudah lengkap.
                    </p>

                </div>

            </div>

            <form
                action="{{ route(
                    'auditor.temuan.close',
                    $temuan->id
                ) }}"
                method="POST"
                onsubmit="return confirm(
                    'Pastikan seluruh tindak lanjut sudah sesuai. Tutup temuan ini?'
                )"
            >

                @csrf
                @method('PATCH')

                <button
                    type="submit"
                    class="auditor-primary-button"
                >

                    <i class="bi bi-check-circle"></i>

                    Verifikasi dan Tutup Temuan

                </button>

            </form>

        </section>

    @else

        <section class="auditor-temuan-card">

            <div class="auditor-form-information">

                <i class="bi bi-check-circle-fill"></i>

                <p>
                    Temuan ini sudah selesai diverifikasi
                    dan berstatus Closed.
                </p>

            </div>

        </section>

    @endif

</div>

@endsection