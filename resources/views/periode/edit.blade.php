@extends('layouts.app')

@section('content')

@php
    $unitDipilih = old(
        'unit_kerja',
        $unitKerjaTerpilih ?? []
    );

    $unitDipilih = array_map(
        'strval',
        (array) $unitDipilih
    );

    $statusDipilih = old(
        'status',
        $data->status
    );
@endphp

<style>
    .periode-page {
        width: 100%;
        padding: 42px;
        box-sizing: border-box;
    }

    .periode-card {
        width: 100%;
        max-width: 1080px;
        margin: 0 auto;
        padding: 36px 44px 42px;
        background: #ffffff;
        border-radius: 14px;
        box-sizing: border-box;
        box-shadow: 0 8px 28px rgba(15, 23, 42, 0.05);
    }

    .periode-title {
        margin: 0 0 34px;
        text-align: center;
        color: #111827;
        font-size: 30px;
        font-weight: 700;
    }

    .periode-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 28px 54px;
    }

    .periode-column {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    .periode-form-group {
        width: 100%;
    }

    .periode-label {
        display: block;
        margin-bottom: 8px;
        color: #24324a;
        font-size: 14px;
        font-weight: 600;
    }

    .periode-required {
        color: #dc2626;
    }

    .periode-control {
        width: 100%;
        min-height: 50px;
        padding: 11px 14px;
        border: 1px solid #d0d5dd;
        border-radius: 10px;
        background: #ffffff;
        color: #111827;
        font-size: 15px;
        outline: none;
        box-sizing: border-box;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .periode-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    .periode-control[readonly] {
        background: #f5f6f8;
        color: #475467;
        cursor: not-allowed;
    }

    textarea.periode-control {
        min-height: 148px;
        resize: vertical;
    }

    .periode-control.is-invalid,
    .unit-list.is-invalid {
        border-color: #dc2626;
    }

    .periode-error {
        display: block;
        margin-top: 6px;
        color: #dc2626;
        font-size: 13px;
    }

    .unit-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 8px;
    }

    .unit-header .periode-label {
        margin-bottom: 0;
    }

    .unit-toolbar {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .unit-toolbar-button {
        padding: 6px 10px;
        border: 1px solid #d0d5dd;
        border-radius: 7px;
        background: #ffffff;
        color: #344054;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.2;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .unit-toolbar-button:hover {
        border-color: #98a2b3;
        background: #f8fafc;
    }

    .unit-list {
        max-height: 190px;
        padding: 8px 12px;
        overflow-y: auto;
        border: 1px solid #d0d5dd;
        border-radius: 10px;
        background: #ffffff;
        box-sizing: border-box;
    }

    .unit-item {
        display: flex;
        align-items: center;
        gap: 9px;
        width: 100%;
        min-height: 34px;
        margin: 0;
        padding: 5px 2px;
        border: none;
        border-bottom: 1px solid #f1f3f5;
        background: transparent;
        box-sizing: border-box;
        cursor: pointer;
    }

    .unit-item:last-child {
        border-bottom: none;
    }

    .unit-item:hover .unit-name {
        color: #4338ca;
    }

    .unit-checkbox {
        appearance: auto !important;
        -webkit-appearance: checkbox !important;
        -moz-appearance: checkbox !important;

        display: inline-block !important;
        width: 14px !important;
        height: 14px !important;
        min-width: 14px !important;
        min-height: 14px !important;
        max-width: 14px !important;
        max-height: 14px !important;

        margin: 0 !important;
        padding: 0 !important;
        border: initial !important;
        border-radius: 2px !important;
        background: initial !important;
        box-shadow: none !important;

        flex: 0 0 14px;
        vertical-align: middle;
        accent-color: #4f46e5;
        cursor: pointer;
    }

    .unit-name {
        display: inline-block;
        margin: 0;
        padding: 0;
        color: #344054;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.4;
    }

    .date-row,
    .time-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 14px;
    }

    .time-input {
        width: 100%;
        min-height: 50px;
        padding: 11px 14px;
        border: 1px solid #d0d5dd;
        border-radius: 10px;
        background: #ffffff;
        color: #111827;
        font-size: 15px;
        outline: none;
        box-sizing: border-box;
        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .time-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    .time-input.is-invalid {
        border-color: #dc2626;
    }

    .time-description {
        margin: 7px 0 0;
        color: #667085;
        font-size: 12px;
        line-height: 1.4;
    }

    .status-list {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .status-item {
        display: flex;
        align-items: center;
        min-height: 48px;
        padding: 10px 13px;
        border: 1px solid #d0d5dd;
        border-radius: 10px;
        background: #ffffff;
        box-sizing: border-box;
        cursor: pointer;
        transition:
            border-color 0.2s ease,
            background 0.2s ease,
            box-shadow 0.2s ease;
    }

    .status-item:hover {
        border-color: #818cf8;
        background: #fafaff;
    }

    .status-item:has(.status-radio:checked) {
        border-color: #4f46e5;
        background: #eef2ff;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.08);
    }

    .status-radio {
        appearance: auto !important;
        width: 17px !important;
        height: 17px !important;
        min-width: 17px !important;
        min-height: 17px !important;
        margin: 0 8px 0 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        flex-shrink: 0;
        accent-color: #4f46e5;
        cursor: pointer;
    }

    .status-name {
        color: #344054;
        font-size: 14px;
        font-weight: 600;
    }

    .periode-form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        margin-top: 34px;
        padding-top: 22px;
        border-top: 1px solid #eaecf0;
    }

    .periode-button {
        min-width: 120px;
        padding: 11px 20px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.2;
        text-align: center;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        transition: 0.2s ease;
    }

    .periode-button-cancel {
        border: 1px solid #d0d5dd;
        background: #ffffff;
        color: #344054;
    }

    .periode-button-cancel:hover {
        background: #f8fafc;
    }

    .periode-button-save {
        border: 1px solid #4f46e5;
        background: #4f46e5;
        color: #ffffff;
    }

    .periode-button-save:hover {
        border-color: #4338ca;
        background: #4338ca;
    }

    @media (max-width: 900px) {
        .periode-page {
            padding: 20px;
        }

        .periode-card {
            padding: 28px 22px 34px;
        }

        .periode-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }
    }

    @media (max-width: 580px) {
        .unit-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .date-row,
        .time-row,
        .status-list {
            grid-template-columns: 1fr;
        }

        .periode-form-actions {
            flex-direction: column-reverse;
        }

        .periode-button {
            width: 100%;
        }
    }
</style>

<div class="periode-page">

    <form
        action="{{ route('periode-ami.update', $data->id) }}"
        method="POST"
        id="periodeForm"
    >
        @csrf
        @method('PUT')

        <div class="periode-card">

            <h1 class="periode-title">
                Edit Periode AMI
            </h1>

            <div class="periode-grid">

                {{-- KOLOM KIRI --}}
                <div class="periode-column">

                    {{-- TAHUN --}}
                    <div class="periode-form-group">

                        <label
                            for="tahun"
                            class="periode-label"
                        >
                            Tahun
                            <span class="periode-required">*</span>
                        </label>

                        <select
                            name="tahun"
                            id="tahun"
                            class="periode-control @error('tahun') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                -- Pilih Tahun --
                            </option>

                            @for ($tahun = 2025; $tahun <= 2035; $tahun++)
                                <option
                                    value="{{ $tahun }}"
                                    @selected(
                                        old('tahun', $data->tahun)
                                        == $tahun
                                    )
                                >
                                    {{ $tahun }}
                                </option>
                            @endfor

                        </select>

                        @error('tahun')
                            <span class="periode-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- STANDAR MUTU --}}
                    <div class="periode-form-group">

                        <label
                            for="id_standar_mutu"
                            class="periode-label"
                        >
                            Standar Mutu
                            <span class="periode-required">*</span>
                        </label>

                        <select
                            name="id_standar_mutu"
                            id="id_standar_mutu"
                            class="periode-control @error('id_standar_mutu') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                -- Pilih Standar Mutu --
                            </option>

                            @foreach ($standarMutu as $standar)
                                <option
                                    value="{{ $standar->id }}"
                                    @selected(
                                        old(
                                            'id_standar_mutu',
                                            $data->id_standar_mutu
                                        ) == $standar->id
                                    )
                                >
                                    {{
                                        $standar->nama_standar_mutu
                                        ?? $standar->nama_standar
                                        ?? $standar->judul
                                        ?? $standar->nama
                                        ?? 'Standar Mutu #' . $standar->id
                                    }}
                                </option>
                            @endforeach

                        </select>

                        @error('id_standar_mutu')
                            <span class="periode-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- UNIT KERJA --}}
                    <div class="periode-form-group">

                        <div class="unit-header">

                            <label class="periode-label">
                                Unit Kerja
                                <span class="periode-required">*</span>
                            </label>

                            <div class="unit-toolbar">

                                <button
                                    type="button"
                                    id="pilihSemua"
                                    class="unit-toolbar-button"
                                >
                                    Pilih Semua
                                </button>

                                <button
                                    type="button"
                                    id="hapusSemua"
                                    class="unit-toolbar-button"
                                >
                                    Hapus Pilihan
                                </button>

                            </div>

                        </div>

                        <div
                            id="unitList"
                            class="unit-list
                            @error('unit_kerja') is-invalid @enderror
                            @error('unit_kerja.*') is-invalid @enderror"
                        >

                            @forelse ($unitKerja as $unit)

                                <label class="unit-item">

                                    <input
                                        type="checkbox"
                                        name="unit_kerja[]"
                                        value="{{ $unit->id }}"
                                        class="unit-checkbox"
                                        @checked(
                                            in_array(
                                                (string) $unit->id,
                                                $unitDipilih,
                                                true
                                            )
                                        )
                                    >

                                    <span class="unit-name">
                                        {{
                                            $unit->nama
                                            ?? $unit->nama_unit
                                            ?? $unit->nama_unit_kerja
                                            ?? 'Unit Kerja #' . $unit->id
                                        }}
                                    </span>

                                </label>

                            @empty

                                <div class="unit-empty">
                                    Belum ada data unit kerja.
                                </div>

                            @endforelse

                        </div>

                        @error('unit_kerja')
                            <span class="periode-error">
                                {{ $message }}
                            </span>
                        @enderror

                        @error('unit_kerja.*')
                            <span class="periode-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- KETUA AMI --}}
                    <div class="periode-form-group">

                        <label class="periode-label">
                            Ketua AMI / Pembuat
                        </label>

                        <input
                            type="text"
                            class="periode-control"
                            value="{{
                                $data->user->nama
                                ?? $userLogin->nama
                                ?? $userLogin->name
                                ?? $userLogin->username
                                ?? '-'
                            }}"
                            readonly
                        >

                    </div>

                    {{-- TANGGAL --}}
                    <div class="date-row">

                        <div class="periode-form-group">

                            <label
                                for="tanggal_buka_ami"
                                class="periode-label"
                            >
                                Tanggal Buka Audit
                                <span class="periode-required">*</span>
                            </label>

                            <input
                                type="date"
                                name="tanggal_buka_ami"
                                id="tanggal_buka_ami"
                                class="periode-control @error('tanggal_buka_ami') is-invalid @enderror"
                                value="{{ old(
                                    'tanggal_buka_ami',
                                    $data->tanggal_buka_ami
                                ) }}"
                                required
                            >

                            @error('tanggal_buka_ami')
                                <span class="periode-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                        <div class="periode-form-group">

                            <label
                                for="tanggal_tutup_ami"
                                class="periode-label"
                            >
                                Tanggal Tutup Audit
                                <span class="periode-required">*</span>
                            </label>

                            <input
                                type="date"
                                name="tanggal_tutup_ami"
                                id="tanggal_tutup_ami"
                                class="periode-control @error('tanggal_tutup_ami') is-invalid @enderror"
                                value="{{ old(
                                    'tanggal_tutup_ami',
                                    $data->tanggal_tutup_ami
                                ) }}"
                                required
                            >

                            @error('tanggal_tutup_ami')
                                <span class="periode-error">
                                    {{ $message }}
                                </span>
                            @enderror

                        </div>

                    </div>

                </div>

                {{-- KOLOM KANAN --}}
                <div class="periode-column">

                    {{-- TUJUAN --}}
                    <div class="periode-form-group">

                        <label
                            for="tujuan_audit"
                            class="periode-label"
                        >
                            Tujuan Audit
                            <span class="periode-required">*</span>
                        </label>

                        <textarea
                            name="tujuan_audit"
                            id="tujuan_audit"
                            class="periode-control @error('tujuan_audit') is-invalid @enderror"
                            required
                        >{{ old(
                            'tujuan_audit',
                            $data->tujuan_audit
                        ) }}</textarea>

                        @error('tujuan_audit')
                            <span class="periode-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- LINGKUP --}}
                    <div class="periode-form-group">

                        <label
                            for="lingkup_audit"
                            class="periode-label"
                        >
                            Lingkup Audit
                            <span class="periode-required">*</span>
                        </label>

                        <textarea
                            name="lingkup_audit"
                            id="lingkup_audit"
                            class="periode-control @error('lingkup_audit') is-invalid @enderror"
                            required
                        >{{ old(
                            'lingkup_audit',
                            $data->lingkup_audit
                        ) }}</textarea>

                        @error('lingkup_audit')
                            <span class="periode-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                    {{-- WAKTU --}}
                    <div class="periode-form-group">

                        <label class="periode-label">
                            Waktu Audit
                            <span class="periode-required">*</span>
                        </label>

                        <div class="time-row">

                            <div>

                                <label
                                    for="waktu_mulai"
                                    class="periode-label"
                                >
                                    Waktu Mulai
                                </label>

                                <input
                                    type="time"
                                    name="waktu_mulai"
                                    id="waktu_mulai"
                                    class="time-input @error('waktu_mulai') is-invalid @enderror"
                                    value="{{ old(
                                        'waktu_mulai',
                                        $waktuMulai
                                    ) }}"
                                    step="60"
                                    required
                                >

                                @error('waktu_mulai')
                                    <span class="periode-error">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                            <div>

                                <label
                                    for="waktu_selesai"
                                    class="periode-label"
                                >
                                    Waktu Selesai
                                </label>

                                <input
                                    type="time"
                                    name="waktu_selesai"
                                    id="waktu_selesai"
                                    class="time-input @error('waktu_selesai') is-invalid @enderror"
                                    value="{{ old(
                                        'waktu_selesai',
                                        $waktuSelesai
                                    ) }}"
                                    step="60"
                                    required
                                >

                                @error('waktu_selesai')
                                    <span class="periode-error">
                                        {{ $message }}
                                    </span>
                                @enderror

                            </div>

                        </div>

                        <p class="time-description">
                            Waktu audit akan disimpan menggunakan zona waktu WIB.
                        </p>

                    </div>

                    {{-- STATUS --}}
                    <div class="periode-form-group">

                        <label class="periode-label">
                            Status
                            <span class="periode-required">*</span>
                        </label>

                        <div class="status-list">

                            <label class="status-item">

                                <input
                                    type="radio"
                                    name="status"
                                    value="draft"
                                    class="status-radio"
                                    @checked($statusDipilih === 'draft')
                                >

                                <span class="status-name">
                                    Draft
                                </span>

                            </label>

                            <label class="status-item">

                                <input
                                    type="radio"
                                    name="status"
                                    value="berjalan"
                                    class="status-radio"
                                    @checked($statusDipilih === 'berjalan')
                                >

                                <span class="status-name">
                                    Berjalan
                                </span>

                            </label>

                            <label class="status-item">

                                <input
                                    type="radio"
                                    name="status"
                                    value="ditutup"
                                    class="status-radio"
                                    @checked($statusDipilih === 'ditutup')
                                >

                                <span class="status-name">
                                    Ditutup
                                </span>

                            </label>

                        </div>

                        @error('status')
                            <span class="periode-error">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>

                </div>

            </div>

            <div class="periode-form-actions">

                <a
                    href="{{ route('periode-ami.index') }}"
                    class="periode-button periode-button-cancel"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="periode-button periode-button-save"
                >
                    Simpan Perubahan
                </button>

            </div>

        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('periodeForm');

        const tombolPilihSemua =
            document.getElementById('pilihSemua');

        const tombolHapusSemua =
            document.getElementById('hapusSemua');

        const checkboxUnit =
            document.querySelectorAll('.unit-checkbox');

        const unitList =
            document.getElementById('unitList');

        const tanggalBuka =
            document.getElementById('tanggal_buka_ami');

        const tanggalTutup =
            document.getElementById('tanggal_tutup_ami');

        const waktuMulai =
            document.getElementById('waktu_mulai');

        const waktuSelesai =
            document.getElementById('waktu_selesai');

        tombolPilihSemua?.addEventListener(
            'click',
            function () {
                checkboxUnit.forEach(function (checkbox) {
                    checkbox.checked = true;
                });
            }
        );

        tombolHapusSemua?.addEventListener(
            'click',
            function () {
                checkboxUnit.forEach(function (checkbox) {
                    checkbox.checked = false;
                });
            }
        );

        const aturTanggalTutup = function () {
            if (!tanggalBuka || !tanggalTutup) {
                return;
            }

            tanggalTutup.min = tanggalBuka.value;

            if (
                tanggalTutup.value
                && tanggalTutup.value < tanggalBuka.value
            ) {
                tanggalTutup.value = tanggalBuka.value;
            }
        };

        tanggalBuka?.addEventListener(
            'change',
            aturTanggalTutup
        );

        if (tanggalBuka?.value) {
            aturTanggalTutup();
        }

        waktuMulai?.addEventListener(
            'change',
            function () {
                if (waktuSelesai) {
                    waktuSelesai.min =
                        waktuMulai.value;
                }
            }
        );

        if (waktuMulai?.value && waktuSelesai) {
            waktuSelesai.min = waktuMulai.value;
        }

        form?.addEventListener(
            'submit',
            function (event) {
                const jumlahUnitDipilih =
                    document.querySelectorAll(
                        '.unit-checkbox:checked'
                    ).length;

                if (jumlahUnitDipilih === 0) {
                    event.preventDefault();

                    alert(
                        'Pilih minimal satu unit kerja.'
                    );

                    unitList?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    return;
                }

                if (
                    waktuMulai?.value
                    && waktuSelesai?.value
                    && waktuSelesai.value <= waktuMulai.value
                ) {
                    event.preventDefault();

                    alert(
                        'Waktu selesai harus lebih akhir daripada waktu mulai.'
                    );

                    waktuSelesai.focus();
                }
            }
        );
    });
</script>

@endsection