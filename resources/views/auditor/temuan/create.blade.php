@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/auditor-temuan-penerapan.css') }}"
    >
@endpush

@section('content')

@php
    $indikator =
        $penerapan->indikator;

    $isiStandar =
        $indikator?->isiStandar;

    $standarPeriode =
        $penerapan->standarmutuPeriode;

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
        $penerapan->user?->nama
        ?? 'Auditee';

    $deskripsiIndikator =
        $indikator?->deskripsi
        ?? $indikator?->indikator
        ?? $indikator?->nama_indikator
        ?? '-';

    $deskripsiHasil =
        trim(
            (string) (
                $penerapan->deskripsi_hasil
                ?? ''
            )
        );

    $linkBukti =
        trim(
            (string) (
                $penerapan->link_bukti
                ?? ''
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
@endphp

<div class="auditor-temuan-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <section class="auditor-temuan-header">

        <div>

            <span class="auditor-temuan-eyebrow">
                TAMBAH TEMUAN AUDIT
            </span>

            <h2>
                Tambah Temuan
            </h2>

            <p>
                Tambahkan temuan berdasarkan hasil penerapan
                dan bukti yang telah dikirim oleh Auditee.
            </p>

        </div>

        <div class="auditor-temuan-header-icon">

            <i class="bi bi-plus-circle"></i>

        </div>

    </section>

    {{-- =====================================================
         VALIDATION ERROR
    ====================================================== --}}

    @if($errors->any())

        <div class="auditor-alert auditor-alert-danger">

            <i class="bi bi-exclamation-circle-fill"></i>

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

    @if(session('error'))

        <div class="auditor-alert auditor-alert-danger">

            <i class="bi bi-exclamation-circle-fill"></i>

            <span>
                {{ session('error') }}
            </span>

        </div>

    @endif

    {{-- =====================================================
         RINGKASAN PENERAPAN
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
                    Data berikut hanya dapat dilihat dan tidak
                    dapat diubah oleh Auditor.
                </p>

            </div>

            <a
                href="{{ route('auditor.temuan.index') }}"
                class="auditor-back-button"
            >

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

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
                    Auditee
                </span>

                <strong>
                    {{ $namaAuditee }}
                </strong>

            </div>

            <div class="auditor-detail-item">

                <span>
                    Status Penerapan
                </span>

                <strong class="auditor-inline-status status-complete">

                    <i class="bi bi-check-circle-fill"></i>

                    Lengkap

                </strong>

            </div>

        </div>

    </section>

    {{-- =====================================================
         HASIL DAN BUKTI
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    HASIL PEMERIKSAAN AWAL
                </span>

                <h3>
                    Hasil dan Bukti Penerapan
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
                        Belum tersedia
                    </p>

                @endif

            </div>

        </div>

    </section>

    {{-- =====================================================
         FORM TEMUAN
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    FORM TEMUAN
                </span>

                <h3>
                    Isi Temuan Audit
                </h3>

                <p>
                    Tuliskan kondisi yang ditemukan berdasarkan
                    pemeriksaan hasil dan bukti penerapan.
                </p>

            </div>

        </div>

        <form
            action="{{ route('auditor.temuan.store') }}"
            method="POST"
            class="auditor-temuan-form"
        >

            @csrf

            <input
                type="hidden"
                name="id_penerapan_standar"
                value="{{ $penerapan->id }}"
            >

            <div class="auditor-form-group">

                <label for="temuan">

                    Isi Temuan

                    <span class="required-mark">
                        *
                    </span>

                </label>

                <textarea
                    name="temuan"
                    id="temuan"
                    rows="7"
                    maxlength="5000"
                    placeholder="Tuliskan temuan audit secara jelas dan objektif..."
                    required
                >{{ old('temuan') }}</textarea>

                <small>
                    Jelaskan kondisi yang ditemukan, bukti pendukung,
                    dan ketidaksesuaian apabila ada.
                </small>

                @error('temuan')

                    <span class="auditor-field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>

            <div class="auditor-form-group">

                <label for="status_temuan">

                    Status Temuan

                    <span class="required-mark">
                        *
                    </span>

                </label>

                <select
                    name="status_temuan"
                    id="status_temuan"
                    required
                >

                    <option value="">
                        Pilih status temuan
                    </option>

                    <option
                        value="open"
                        {{
                            old('status_temuan', 'open') === 'open'
                                ? 'selected'
                                : ''
                        }}
                    >
                        Open
                    </option>

                    <option
                        value="closed"
                        {{
                            old('status_temuan') === 'closed'
                                ? 'selected'
                                : ''
                        }}
                    >
                        Closed
                    </option>

                </select>

                <small>
                    Gunakan Open untuk temuan yang masih membutuhkan
                    tindak lanjut Auditee.
                </small>

                @error('status_temuan')

                    <span class="auditor-field-error">
                        {{ $message }}
                    </span>

                @enderror

            </div>

            <div class="auditor-form-information">

                <i class="bi bi-info-circle-fill"></i>

                <p>
                    Setelah temuan disimpan, Auditee dapat melihat
                    temuan tersebut dan memberikan tanggapan.
                </p>

            </div>

            <div class="auditor-form-actions">

                <a
                    href="{{ route('auditor.temuan.index') }}"
                    class="auditor-secondary-button"
                >

                    <i class="bi bi-x-circle"></i>

                    Batal

                </a>

                <button
                    type="submit"
                    class="auditor-primary-button"
                >

                    <i class="bi bi-save"></i>

                    Simpan Temuan

                </button>

            </div>

        </form>

    </section>

</div>

@endsection