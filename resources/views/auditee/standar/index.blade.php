@extends('layouts.auditee')

@section('content')

<style>
    /*
    |--------------------------------------------------------------------------
    | KOLOM TEMUAN AUDIT
    |--------------------------------------------------------------------------
    */

    .col-finding {
        min-width: 290px;
        width: 290px;
    }

    .cell-finding {
        min-width: 290px;
        vertical-align: top;
    }

    .grouped-finding-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .grouped-finding-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        min-height: 104px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e9edf3;
        box-sizing: border-box;
    }

    .grouped-finding-item:last-child {
        padding-bottom: 0;
        border-bottom: none;
    }

    .finding-content {
        width: 100%;
        min-width: 0;
    }

    /*
    |--------------------------------------------------------------------------
    | KARTU TEMUAN
    |--------------------------------------------------------------------------
    */

    .finding-card {
        width: 100%;
        padding: 13px;
        border: 1px solid #dce3ec;
        border-radius: 10px;
        background: #ffffff;
        box-sizing: border-box;
    }

    .finding-card + .finding-card {
        margin-top: 9px;
    }

    .finding-card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 9px;
    }

    .finding-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        max-width: 170px;
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.2;
        text-transform: uppercase;
    }

    .finding-type-kts {
        background: #fee2e2;
        color: #b91c1c;
    }

    .finding-type-ob {
        background: #ffedd5;
        color: #c2410c;
    }

    .finding-type-sesuai {
        background: #dcfce7;
        color: #15803d;
    }

    .finding-type-default {
        background: #eef2ff;
        color: #4338ca;
    }

    .finding-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .finding-status-open {
        background: #fef3c7;
        color: #a16207;
    }

    .finding-status-closed {
        background: #dcfce7;
        color: #15803d;
    }

    .finding-status-default {
        background: #f1f5f9;
        color: #475569;
    }

    .finding-description {
        margin: 0 0 11px;
        color: #475569;
        font-size: 12px;
        line-height: 1.55;
        overflow-wrap: anywhere;
    }

    .finding-detail-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 34px;
        padding: 7px 11px;
        border: 1px solid #4f46e5;
        border-radius: 7px;
        background: #4f46e5;
        color: #ffffff;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.2;
        text-decoration: none;
        box-sizing: border-box;
        transition: 0.2s ease;
    }

    .finding-detail-button:hover {
        border-color: #4338ca;
        background: #4338ca;
        color: #ffffff;
    }

    /*
    |--------------------------------------------------------------------------
    | BELUM ADA TEMUAN
    |--------------------------------------------------------------------------
    */

    .finding-empty-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: 100%;
        min-height: 84px;
        padding: 12px;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
        background: #f8fafc;
        color: #64748b;
        text-align: center;
        box-sizing: border-box;
    }

    .finding-empty-card i {
        font-size: 19px;
        color: #94a3b8;
    }

    .finding-empty-card strong {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
    }

    .finding-empty-card span {
        color: #94a3b8;
        font-size: 11px;
        line-height: 1.4;
    }

    /*
    |--------------------------------------------------------------------------
    | JUMLAH TEMUAN
    |--------------------------------------------------------------------------
    */

    .finding-count {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
    }

    .finding-count i {
        color: #4f46e5;
    }
</style>

<div class="quality-page-heading">

    <div>

        <div class="breadcrumb quality-breadcrumb">
            Dashboard / Standar Mutu
        </div>

        <h2>
            Struktur Standar Mutu
        </h2>

        <p>
            Setiap baris menampilkan satu jalur standar beserta
            seluruh indikator yang berada pada jalur tersebut.
        </p>

    </div>

    <div class="horizontal-hint">

        <i class="bi bi-arrow-left-right"></i>

        Dapat digulir horizontal

    </div>

</div>

{{-- PESAN BERHASIL --}}
@if(session('success'))

    <div class="quality-alert quality-alert-success">

        <i class="bi bi-check-circle-fill"></i>

        {{ session('success') }}

    </div>

@endif

{{-- PESAN GAGAL --}}
@if(session('error'))

    <div class="quality-alert quality-alert-danger">

        <i class="bi bi-exclamation-circle-fill"></i>

        {{ session('error') }}

    </div>

@endif

{{-- PERINGATAN PERIODE AMI --}}
@if(!$standarPeriode)

    <div class="quality-alert quality-alert-warning">

        <i class="bi bi-exclamation-triangle-fill"></i>

        Periode AMI belum berstatus berjalan.
        Penerapan standar hanya dapat diisi ketika periode AMI sedang berjalan.

    </div>

@endif

<div class="quality-card">

    <div class="quality-card-header">

        <div>

            <h3>
                Struktur Standar Mutu
            </h3>

            <p>
                Geser tabel ke kanan untuk melihat indikator,
                status penerapan, bukti pendukung, aksi, dan temuan audit.
            </p>

        </div>

        @if($standarPeriode)

            <span class="quality-period-badge">

                <i class="bi bi-calendar-check"></i>

                Periode
                {{ $standarPeriode->periodeAmi->tahun ?? '-' }}

            </span>

        @endif

    </div>

    <div class="quality-table-scroll">

        <table class="quality-table quality-table-grouped">

            <thead>

                <tr>

                    <th class="col-number">
                        No.
                    </th>

                    <th class="col-standard">
                        Standar Mutu
                    </th>

                    <th class="col-content">
                        Isi Standar 1
                    </th>

                    <th class="col-content">
                        Isi Standar 2
                    </th>

                    <th class="col-content">
                        Isi Standar 3
                    </th>

                    <th class="col-indicator">
                        Indikator
                    </th>

                    <th class="col-status">
                        Status
                    </th>

                    <th class="col-action">
                        Aksi
                    </th>

                    {{-- KOLOM BARU --}}
                    <th class="col-finding">
                        Temuan Audit
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($rows as $row)

                    <tr>

                        {{-- NOMOR BARIS --}}
                        <td class="cell-number">
                            {{ $loop->iteration }}
                        </td>

                        {{-- STANDAR MUTU UTAMA --}}
                        <td class="cell-standard">

                            <div class="standard-name-box">

                                <i class="bi bi-journal-check"></i>

                                <span>
                                    {{ $row['standar'] }}
                                </span>

                            </div>

                        </td>

                        {{-- ISI STANDAR TINGKAT 1 --}}
                        <td class="cell-content">

                            <div class="standard-level-box">
                                {{ $row['level'][0] ?? '—' }}
                            </div>

                        </td>

                        {{-- ISI STANDAR TINGKAT 2 --}}
                        <td class="cell-content">

                            <div class="standard-level-box">
                                {{ $row['level'][1] ?? '—' }}
                            </div>

                        </td>

                        {{-- ISI STANDAR TINGKAT 3 --}}
                        <td class="cell-content">

                            <div class="standard-level-box">
                                {{ $row['level'][2] ?? '—' }}
                            </div>

                        </td>

                        {{-- SEMUA INDIKATOR PADA JALUR YANG SAMA --}}
                        <td class="cell-indicator">

                            <div class="grouped-indicator-list">

                                @forelse($row['indikator'] as $index => $indikator)

                                    <div class="grouped-indicator-item">

                                        <div class="grouped-item-number">
                                            {{ $index + 1 }}
                                        </div>

                                        <div class="grouped-indicator-text">

                                            {{
                                                $indikator->deskripsi
                                                ?? $indikator->indikator
                                                ?? '-'
                                            }}

                                        </div>

                                    </div>

                                @empty

                                    <span class="quality-empty-value">
                                        Belum ada indikator.
                                    </span>

                                @endforelse

                            </div>

                        </td>

                        {{-- STATUS PER INDIKATOR --}}
                        <td class="cell-status">

                            <div class="grouped-status-list">

                                @forelse($row['indikator'] as $index => $indikator)

                                    @php
                                        $penerapan = $penerapanByIndikator->get(
                                            $indikator->id
                                        );
                                    @endphp

                                    <div class="grouped-status-item">

                                        <span class="grouped-small-number">
                                            {{ $index + 1 }}
                                        </span>

                                        @if($penerapan)

                                            <span class="quality-status quality-status-filled">

                                                <i class="bi bi-check-circle-fill"></i>

                                                Sudah diterapkan

                                            </span>

                                        @else

                                            <span class="quality-status quality-status-empty">

                                                <i class="bi bi-clock-fill"></i>

                                                Belum diterapkan

                                            </span>

                                        @endif

                                    </div>

                                @empty

                                    <span class="quality-empty-value">
                                        —
                                    </span>

                                @endforelse

                            </div>

                        </td>

                        {{-- AKSI PER INDIKATOR --}}
                        <td class="cell-action">

                            <div class="grouped-action-list">

                                @forelse($row['indikator'] as $index => $indikator)

                                    @php
                                        $penerapan = $penerapanByIndikator->get(
                                            $indikator->id
                                        );
                                    @endphp

                                    <div class="grouped-action-item">

                                        <span class="grouped-small-number">
                                            {{ $index + 1 }}
                                        </span>

                                        <div class="quality-actions">

                                            @if($penerapan)

                                                <div class="quality-action-stack">

                                                    {{-- LIHAT BUKTI --}}
                                                    @if(!empty($penerapan->link_bukti))

                                                        <a
                                                            href="{{ $penerapan->link_bukti }}"
                                                            target="_blank"
                                                            rel="noopener noreferrer"
                                                            class="quality-action-button quality-action-view"
                                                            title="Buka bukti pendukung di tab baru"
                                                        >

                                                            <i class="bi bi-box-arrow-up-right"></i>

                                                            Lihat Bukti

                                                        </a>

                                                    @else

                                                        <span class="quality-no-evidence">

                                                            <i class="bi bi-link-45deg"></i>

                                                            Belum ada tautan bukti

                                                        </span>

                                                    @endif

                                                    {{-- KELOLA PENERAPAN --}}
                                                    <a
                                                        href="{{ route(
                                                            'auditee.penerapan.edit',
                                                            $penerapan->id
                                                        ) }}"
                                                        class="quality-action-button quality-action-edit"
                                                    >

                                                        <i class="bi bi-pencil-square"></i>

                                                        Kelola Penerapan

                                                    </a>

                                                    {{-- HAPUS PENERAPAN --}}
                                                    <form
                                                        action="{{ route(
                                                            'auditee.penerapan.destroy',
                                                            $penerapan->id
                                                        ) }}"
                                                        method="POST"
                                                        class="quality-delete-form"
                                                        onsubmit="return confirm(
                                                            'Apakah Anda yakin ingin menghapus data penerapan standar ini?'
                                                        )"
                                                    >

                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="quality-action-button quality-action-danger"
                                                            title="Hapus penerapan"
                                                        >

                                                            <i class="bi bi-trash3"></i>

                                                            Hapus

                                                        </button>

                                                    </form>

                                                </div>

                                            @elseif($standarPeriode)

                                                {{-- TAMBAH PENERAPAN --}}
                                                <a
                                                    href="{{ route(
                                                        'auditee.penerapan.create',
                                                        [
                                                            'standar' =>
                                                                $standarPeriode->id,

                                                            'indikator' =>
                                                                $indikator->id
                                                        ]
                                                    ) }}"
                                                    class="quality-action-button quality-action-create"
                                                >

                                                    <i class="bi bi-plus-circle"></i>

                                                    Penerapan Standar

                                                </a>

                                            @else

                                                {{-- TERKUNCI JIKA PERIODE BELUM BERJALAN --}}
                                                <button
                                                    type="button"
                                                    class="quality-action-button is-disabled"
                                                    disabled
                                                    title="Periode AMI belum berjalan"
                                                >

                                                    <i class="bi bi-lock"></i>

                                                    Penerapan Standar

                                                </button>

                                            @endif

                                        </div>

                                    </div>

                                @empty

                                    <span class="quality-empty-value">
                                        —
                                    </span>

                                @endforelse

                            </div>

                        </td>

                        {{-- TEMUAN AUDIT PER INDIKATOR --}}
                        <td class="cell-finding">

                            <div class="grouped-finding-list">

                                @forelse($row['indikator'] as $index => $indikator)

                                    @php
                                        $penerapan = $penerapanByIndikator->get(
                                            $indikator->id
                                        );

                                        /*
                                        |--------------------------------------------------------------------------
                                        | AMBIL TEMUAN AUDITOR
                                        |--------------------------------------------------------------------------
                                        |
                                        | Relasi pada model PenerapanStandar:
                                        | public function temuan(): HasMany
                                        |
                                        */

                                        $daftarTemuan = $penerapan
                                            ? $penerapan->temuan
                                            : collect();
                                    @endphp

                                    <div class="grouped-finding-item">

                                        <span class="grouped-small-number">
                                            {{ $index + 1 }}
                                        </span>

                                        <div class="finding-content">

                                            @if(!$penerapan)

                                                <div class="finding-empty-card">

                                                    <i class="bi bi-hourglass-split"></i>

                                                    <strong>
                                                        Belum ada penerapan
                                                    </strong>

                                                    <span>
                                                        Temuan audit belum dapat ditampilkan.
                                                    </span>

                                                </div>

                                            @elseif($daftarTemuan->isEmpty())

                                                <div class="finding-empty-card">

                                                    <i class="bi bi-clipboard-check"></i>

                                                    <strong>
                                                        Belum ada temuan audit
                                                    </strong>

                                                    <span>
                                                        Auditor belum memberikan temuan
                                                        pada penerapan ini.
                                                    </span>

                                                </div>

                                            @else

                                                <div class="finding-count">

                                                    <i class="bi bi-clipboard-data"></i>

                                                    {{ $daftarTemuan->count() }}
                                                    Temuan Audit

                                                </div>

                                                @foreach($daftarTemuan as $temuan)

                                                    @php
                                                        $jenisTemuan = strtolower(
                                                            trim(
                                                                (string)
                                                                $temuan->jenis_temuan
                                                            )
                                                        );

                                                        $statusTemuan = strtolower(
                                                            trim(
                                                                (string)
                                                                $temuan->status_temuan
                                                            )
                                                        );

                                                        $classJenis = match(
                                                            $jenisTemuan
                                                        ) {
                                                            'kts' =>
                                                                'finding-type-kts',

                                                            'ob' =>
                                                                'finding-type-ob',

                                                            'sesuai_standar' =>
                                                                'finding-type-sesuai',

                                                            default =>
                                                                'finding-type-default',
                                                        };

                                                        $classStatus = match(
                                                            $statusTemuan
                                                        ) {
                                                            'open' =>
                                                                'finding-status-open',

                                                            'closed',
                                                            'close',
                                                            'ditutup',
                                                            'selesai' =>
                                                                'finding-status-closed',

                                                            default =>
                                                                'finding-status-default',
                                                        };
                                                    @endphp

                                                    <div class="finding-card">

                                                        <div class="finding-card-header">

                                                            <span
                                                                class="finding-type-badge {{ $classJenis }}"
                                                            >

                                                                <i class="bi bi-exclamation-diamond"></i>

                                                                {{
                                                                    $temuan->label_jenis_temuan
                                                                    ?? strtoupper(
                                                                        str_replace(
                                                                            '_',
                                                                            ' ',
                                                                            $temuan->jenis_temuan
                                                                        )
                                                                    )
                                                                }}

                                                            </span>

                                                            <span
                                                                class="finding-status {{ $classStatus }}"
                                                            >

                                                                <i class="bi bi-circle-fill"></i>

                                                                {{
                                                                    ucfirst(
                                                                        $temuan->status_temuan
                                                                        ?? '-'
                                                                    )
                                                                }}

                                                            </span>

                                                        </div>

                                                        <p class="finding-description">

                                                            {{
                                                                \Illuminate\Support\Str::limit(
                                                                    $temuan->temuan
                                                                    ?? '-',
                                                                    120
                                                                )
                                                            }}

                                                        </p>

                                                        {{--
                                                        |--------------------------------------------------------------------------
                                                        | HANYA DETAIL TEMUAN
                                                        |--------------------------------------------------------------------------
                                                        |
                                                        | Auditee tidak diberi tombol edit atau hapus.
                                                        | Tombol tanggapan berada di halaman detail temuan.
                                                        |
                                                        --}}

                                                        <a
                                                            href="{{ route(
                                                                'auditee.temuan.show',
                                                                $temuan->id
                                                            ) }}"
                                                            class="finding-detail-button"
                                                        >

                                                            <i class="bi bi-eye"></i>

                                                            Detail Temuan

                                                        </a>

                                                    </div>

                                                @endforeach

                                            @endif

                                        </div>

                                    </div>

                                @empty

                                    <span class="quality-empty-value">
                                        —
                                    </span>

                                @endforelse

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="9"
                            class="quality-empty-state"
                        >

                            <i class="bi bi-inbox"></i>

                            <strong>
                                Belum ada data standar
                            </strong>

                            <span>
                                Silakan tambahkan struktur standar mutu
                                melalui halaman administrator.
                            </span>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection