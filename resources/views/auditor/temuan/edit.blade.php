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

    $statusTemuanSaatIni = old(
        'status_temuan',
        strtolower(
            trim(
                (string) (
                    $temuan->status_temuan
                    ?? 'open'
                )
            )
        )
    );
@endphp

<div class="auditor-temuan-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <section class="auditor-temuan-header">

        <div>

            <span class="auditor-temuan-eyebrow">
                EDIT TEMUAN AUDIT
            </span>

            <h2>
                Edit Temuan
            </h2>

            <p>
                Perbarui isi atau status temuan berdasarkan
                hasil pemeriksaan dan tindak lanjut Auditee.
            </p>

        </div>

        <div class="auditor-temuan-header-icon">

            <i class="bi bi-pencil-square"></i>

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
                    Data belum dapat diperbarui
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
         NAVIGASI
    ====================================================== --}}

    <section class="auditor-detail-actions">

        <a
            href="{{ route(
                'auditor.temuan.show',
                $temuan->id
            ) }}"
            class="auditor-secondary-button"
        >

            <i class="bi bi-arrow-left"></i>

            Kembali ke Detail

        </a>

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
                    Data penerapan di bawah hanya dapat dilihat
                    dan tidak dapat diubah oleh Auditor.
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
                    Auditee
                </span>

                <strong>
                    {{ $namaAuditee }}
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
         FORM EDIT TEMUAN
    ====================================================== --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    FORM EDIT TEMUAN
                </span>

                <h3>
                    Perbarui Temuan Audit
                </h3>

                <p>
                    Ubah isi temuan atau status sesuai hasil
                    verifikasi tindak lanjut.
                </p>

            </div>

        </div>

        <form
            action="{{ route(
                'auditor.temuan.update',
                $temuan->id
            ) }}"
            method="POST"
            class="auditor-temuan-form"
        >

            @csrf
            @method('PUT')

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
                >{{ old('temuan', $temuan->temuan) }}</textarea>

                <small>
                    Jelaskan kondisi yang ditemukan secara objektif
                    berdasarkan hasil dan bukti penerapan.
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
                            $statusTemuanSaatIni === 'open'
                                ? 'selected'
                                : ''
                        }}
                    >
                        Open
                    </option>

                    <option
                        value="closed"
                        {{
                            $statusTemuanSaatIni === 'closed'
                                ? 'selected'
                                : ''
                        }}
                    >
                        Closed
                    </option>

                </select>

                <small>
                    Open berarti masih membutuhkan tindak lanjut.
                    Closed berarti temuan telah diselesaikan.
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
                    Pastikan status Closed hanya dipilih setelah
                    bukti tindak lanjut Auditee dinilai sesuai.
                </p>

            </div>

            <div class="auditor-form-actions">

                <a
                    href="{{ route(
                        'auditor.temuan.show',
                        $temuan->id
                    ) }}"
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

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </section>

</div>

@endsection