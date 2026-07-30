@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/auditor-temuan-penerapan.css') }}"
    >

    <style>
        .auditor-form-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .auditor-dynamic-section {
            display: none;
            padding: 22px;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: #f8fafc;
        }

        .auditor-dynamic-section.is-visible {
            display: block;
        }

        .auditor-dynamic-header {
            margin-bottom: 20px;
        }

        .auditor-dynamic-header h4 {
            margin: 0 0 6px;
            font-size: 18px;
            color: #111827;
        }

        .auditor-dynamic-header p {
            margin: 0;
            color: #64748b;
            line-height: 1.6;
        }

        .auditor-form-type-info {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            margin-bottom: 20px;
            border-radius: 14px;
            background: #eef2ff;
            color: #3730a3;
        }

        .auditor-form-type-info[hidden] {
        display: none !important;
        }

        .auditor-form-type-info i {
            margin-top: 2px;
            font-size: 18px;
        }

        .auditor-form-type-info p {
            margin: 0;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .auditor-form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')

@php
    $indikator = $penerapan->indikator;

    $isiStandar = $indikator?->isiStandar;

    $standarPeriode = $penerapan->standarmutuPeriode;

    $standarMutu = $standarPeriode?->standarMutu;

    $periodeAmi = $standarPeriode?->periodeAmi;

    $unitKerja = $periodeAmi?->unitKerja;

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

    $jenisTemuanTerpilih =
        old('jenis_temuan', '');
@endphp

<div class="auditor-temuan-page">

    {{-- HEADER --}}

    <section class="auditor-temuan-header">

        <div>

            <span class="auditor-temuan-eyebrow">
                TAMBAH PENILAIAN AUDIT
            </span>

            <h2>
                Tambah Penilaian
            </h2>

            <p>
                Berikan skor, tentukan jenis temuan, dan masukkan
                rekomendasi berdasarkan hasil serta bukti penerapan Auditee.
            </p>

        </div>

        <div class="auditor-temuan-header-icon">

            <i class="bi bi-clipboard-check"></i>

        </div>

    </section>

    {{-- VALIDATION ERROR --}}

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

    {{-- DATA PENERAPAN --}}

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
                    Data berikut hanya dapat dilihat dan tidak dapat
                    diubah oleh Auditor.
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

    <label
        for="status_penerapan"
        style="
            display: block;
            margin-bottom: 10px;
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .04em;
        "
    >
        Status Penerapan

        <span class="required-mark">
            *
        </span>
    </label>

    <select
        name="status_penerapan"
        id="status_penerapan"
        form="formPenilaianAudit"
        required
        style="
            width: 100%;
            height: 44px;
            padding: 0 14px;
            border: 1px solid #d7deea;
            border-radius: 12px;
            background: #ffffff;
            color: #111827;
            font-size: 14px;
            font-weight: 600;
            outline: none;
        "
    >

        <option value="">
            Pilih status penerapan
        </option>

        <option
            value="sesuai"
            {{
                old('status_penerapan') === 'sesuai'
                    ? 'selected'
                    : ''
            }}
        >
            Sesuai
        </option>

        <option
            value="belum_sesuai"
            {{
                old('status_penerapan') === 'belum_sesuai'
                    ? 'selected'
                    : ''
            }}
        >
            Tidak Sesuai
        </option>

    </select>

    @error('status_penerapan')

        <span class="auditor-field-error">
            {{ $message }}
        </span>

    @enderror

</div>
        </div>

    </section>

    {{-- HASIL DAN BUKTI --}}

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

    {{-- FORM PENILAIAN --}}

    <section class="auditor-temuan-card">

        <div class="auditor-temuan-card-header">

            <div>

                <span class="auditor-temuan-section-label">
                    FORM PENILAIAN
                </span>

                <h3>
                    Penilaian Audit
                </h3>

                <p>
                    Isi seluruh penilaian berdasarkan kondisi penerapan
                    yang telah diperiksa.
                </p>

            </div>

        </div>

        <form
            action="{{ route('auditor.temuan.store') }}"
            method="POST"
            class="auditor-temuan-form"
            id="formPenilaianAudit"
        >

            @csrf

            <input
                type="hidden"
                name="id_penerapan_standar"
                value="{{ $penerapan->id }}"
            >

            {{-- SKOR DAN JENIS TEMUAN --}}

            <div class="auditor-form-row">

                <div class="auditor-form-group">

                    <label for="id_skala_skor">

                        Skor Penilaian

                        <span class="required-mark">
                            *
                        </span>

                    </label>

                    <select
                        name="id_skala_skor"
                        id="id_skala_skor"
                        required
                    >

                        <option value="">
                            Pilih skor penilaian
                        </option>

                        @foreach($skalaSkor as $skor)

                            <option
                                value="{{ $skor->id }}"
                                {{
                                    (string) old('id_skala_skor')
                                    === (string) $skor->id
                                        ? 'selected'
                                        : ''
                                }}
                            >
                                {{ $skor->nilai_skor }}
                                -
                                {{ $skor->label_skor }}
                            </option>

                        @endforeach

                    </select>

                    <small>
                        Pilih skor berdasarkan tingkat pemenuhan standar.
                    </small>

                    @error('id_skala_skor')

                        <span class="auditor-field-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

                <div class="auditor-form-group">

                    <label for="jenis_temuan">

                        Jenis Temuan

                        <span class="required-mark">
                            *
                        </span>

                    </label>

                    <select
                        name="jenis_temuan"
                        id="jenis_temuan"
                        required
                    >

                        <option value="">
                            Pilih jenis temuan
                        </option>

                        <option
                            value="sesuai_standar"
                            {{
                                $jenisTemuanTerpilih === 'sesuai_standar'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            Sudah Sesuai Standar
                        </option>

                        <option
                            value="kts"
                            {{
                                $jenisTemuanTerpilih === 'kts'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            KTS
                        </option>

                        <option
                            value="ob"
                            {{
                                $jenisTemuanTerpilih === 'ob'
                                    ? 'selected'
                                    : ''
                            }}
                        >
                            OB
                        </option>

                    </select>

                    <small>
                        Pilihan jenis temuan menentukan jenis rekomendasi.
                    </small>

                    @error('jenis_temuan')

                        <span class="auditor-field-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

            </div>

            {{-- INFORMASI DINAMIS --}}

            <div
                class="auditor-form-type-info"
                id="informasiJenis"
                hidden
            >

                <i class="bi bi-info-circle-fill"></i>

                <p id="teksInformasiJenis"></p>

            </div>

            {{-- FORM KTS / OB --}}

            <div
                class="auditor-dynamic-section"
                id="bagianTemuan"
            >

                <div class="auditor-dynamic-header">

                    <h4>
                        Data Temuan Audit
                    </h4>

                    <p>
                        Bagian ini digunakan untuk jenis temuan KTS atau OB.
                    </p>

                </div>

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
                        rows="6"
                        maxlength="5000"
                        placeholder="Tuliskan kondisi ketidaksesuaian atau hasil observasi..."
                    >{{ old('temuan') }}</textarea>

                    <small>
                        Jelaskan kondisi yang ditemukan berdasarkan hasil
                        dan bukti penerapan.
                    </small>

                    @error('temuan')

                        <span class="auditor-field-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

            </div>

            {{-- DATA REKOMENDASI GLOBAL --}}

            <div
                class="auditor-dynamic-section"
                id="bagianRekomendasi"
            >

                <div class="auditor-dynamic-header">

                    <h4 id="judulRekomendasi">
                        Data Rekomendasi
                    </h4>

                    <p id="keteranganRekomendasi">
                        Masukkan deskripsi dan rekomendasi berdasarkan jenis temuan.
                    </p>

                </div>

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
                        value="{{ old('aspek') }}"
                        placeholder="Contoh: Dokumen kurikulum, proses pembelajaran, sarana..."
                    >

                    <small>
                        Tuliskan aspek yang menjadi fokus penilaian.
                    </small>

                    @error('aspek')

                        <span class="auditor-field-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

                <div class="auditor-form-group">

                    <label
                        for="deskripsi"
                        id="labelDeskripsi"
                    >

                        Deskripsi

                        <span class="required-mark">
                            *
                        </span>

                    </label>

                    <textarea
                        name="deskripsi"
                        id="deskripsi"
                        rows="5"
                        maxlength="5000"
                        placeholder="Tuliskan deskripsi..."
                    >{{ old('deskripsi') }}</textarea>

                    <small id="bantuanDeskripsi">
                        Tuliskan deskripsi kondisi yang dinilai.
                    </small>

                    @error('deskripsi')

                        <span class="auditor-field-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

                <div class="auditor-form-group">

                    <label
                        for="rekomendasi"
                        id="labelRekomendasi"
                    >

                        Rekomendasi

                        <span class="required-mark">
                            *
                        </span>

                    </label>

                    <textarea
                        name="rekomendasi"
                        id="rekomendasi"
                        rows="6"
                        maxlength="5000"
                        placeholder="Tuliskan rekomendasi..."
                    >{{ old('rekomendasi') }}</textarea>

                    <small id="bantuanRekomendasi">
                        Tuliskan saran yang perlu dilakukan berdasarkan penilaian.
                    </small>

                    @error('rekomendasi')

                        <span class="auditor-field-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

                <div class="auditor-form-group">

                    <label for="status_temuan">

                        Status

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
                            Pilih status
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

                    <small id="bantuanStatus">
                        Open berarti masih membutuhkan tindak lanjut.
                    </small>

                    @error('status_temuan')

                        <span class="auditor-field-error">
                            {{ $message }}
                        </span>

                    @enderror

                </div>

            </div>

            <div class="auditor-form-information">

                <i class="bi bi-info-circle-fill"></i>

                <p>
                    Skor disimpan pada penerapan standar, sedangkan
                    rekomendasi disimpan berdasarkan setiap temuan.
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

                    Simpan Penilaian

                </button>

            </div>

        </form>

    </section>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const jenisTemuan = document.getElementById('jenis_temuan');
    const bagianTemuan = document.getElementById('bagianTemuan');
    const bagianRekomendasi = document.getElementById('bagianRekomendasi');
    const inputTemuan = document.getElementById('temuan');

    const informasiJenis = document.getElementById('informasiJenis');
    const teksInformasiJenis = document.getElementById('teksInformasiJenis');

    const judulRekomendasi = document.getElementById('judulRekomendasi');
    const keteranganRekomendasi = document.getElementById('keteranganRekomendasi');

    const labelDeskripsi = document.getElementById('labelDeskripsi');
    const bantuanDeskripsi = document.getElementById('bantuanDeskripsi');

    const labelRekomendasi = document.getElementById('labelRekomendasi');
    const bantuanRekomendasi = document.getElementById('bantuanRekomendasi');

    const bantuanStatus = document.getElementById('bantuanStatus');

    const aspek = document.getElementById('aspek');
    const deskripsi = document.getElementById('deskripsi');
    const rekomendasi = document.getElementById('rekomendasi');

    function tampilkanForm() {
        const nilai = jenisTemuan.value;

        bagianTemuan.classList.remove('is-visible');
        bagianRekomendasi.classList.remove('is-visible');

        informasiJenis.hidden = true;
        informasiJenis.style.display = 'none';

        inputTemuan.required = false;
        aspek.required = false;
        deskripsi.required = false;
        rekomendasi.required = false;

        if (!nilai) {
            return;
        }

        informasiJenis.hidden = false;
        informasiJenis.style.display = 'flex';
        bagianRekomendasi.classList.add('is-visible');

        aspek.required = true;
        deskripsi.required = true;
        rekomendasi.required = true;

        if (nilai === 'sesuai_standar') {
            teksInformasiJenis.textContent =
                'Penerapan dinilai sudah sesuai standar. Isi deskripsi kondisi positif dan rekomendasi peningkatan.';

            judulRekomendasi.textContent =
                'Data Rekomendasi Peningkatan';

            keteranganRekomendasi.textContent =
                'Tuliskan kondisi yang sudah baik dan saran peningkatan agar penerapan terus berkembang.';

            labelDeskripsi.innerHTML =
                'Deskripsi <span class="required-mark">*</span>';

            bantuanDeskripsi.textContent =
                'Jelaskan kondisi, keunggulan, atau bagian yang telah memenuhi standar.';

            labelRekomendasi.innerHTML =
                'Rekomendasi Peningkatan <span class="required-mark">*</span>';

            bantuanRekomendasi.textContent =
                'Tuliskan saran peningkatan walaupun penerapan sudah memenuhi standar.';

            bantuanStatus.textContent =
                'Open berarti rekomendasi peningkatan masih perlu ditindaklanjuti.';
        }

        if (nilai === 'kts' || nilai === 'ob') {
            bagianTemuan.classList.add('is-visible');
            inputTemuan.required = true;

            teksInformasiJenis.textContent =
                nilai === 'kts'
                    ? 'Penerapan memiliki ketidaksesuaian. Isi temuan dan rekomendasi perbaikan.'
                    : 'Penerapan memiliki hasil observasi. Isi temuan dan rekomendasi perbaikan.';

            judulRekomendasi.textContent =
                'Data Rekomendasi Perbaikan';

            keteranganRekomendasi.textContent =
                'Tuliskan deskripsi kondisi dan tindakan perbaikan yang disarankan.';

            labelDeskripsi.innerHTML =
                'Deskripsi <span class="required-mark">*</span>';

            bantuanDeskripsi.textContent =
                'Jelaskan kondisi atau latar belakang temuan secara lebih rinci.';

            labelRekomendasi.innerHTML =
                'Rekomendasi Perbaikan <span class="required-mark">*</span>';

            bantuanRekomendasi.textContent =
                'Tuliskan tindakan perbaikan yang perlu dilakukan oleh Auditee.';

            bantuanStatus.textContent =
                'Open berarti temuan masih membutuhkan tindak lanjut Auditee.';
        }
    }

    jenisTemuan.addEventListener('change', tampilkanForm);

    tampilkanForm();
});
</script>
@endpush