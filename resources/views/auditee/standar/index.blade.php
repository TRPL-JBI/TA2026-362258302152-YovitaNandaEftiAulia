@extends('layouts.auditee')

@section('content')

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

{{-- Pesan berhasil --}}
@if(session('success'))

    <div class="quality-alert quality-alert-success">
        <i class="bi bi-check-circle-fill"></i>

        {{ session('success') }}
    </div>

@endif

{{-- Pesan gagal --}}
@if(session('error'))

    <div class="quality-alert quality-alert-danger">
        <i class="bi bi-exclamation-circle-fill"></i>

        {{ session('error') }}
    </div>

@endif

{{-- Peringatan periode AMI --}}
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
                status penerapan, bukti pendukung, dan aksi.
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

                </tr>

            </thead>

            <tbody>

                @forelse($rows as $row)

                    <tr>

                        {{-- Nomor baris --}}
                        <td class="cell-number">
                            {{ $loop->iteration }}
                        </td>

                        {{-- Standar mutu utama --}}
                        <td class="cell-standard">

                            <div class="standard-name-box">

                                <i class="bi bi-journal-check"></i>

                                <span>
                                    {{ $row['standar'] }}
                                </span>

                            </div>

                        </td>

                        {{-- Isi Standar Tingkat 1 --}}
                        <td class="cell-content">

                            <div class="standard-level-box">
                                {{ $row['level'][0] ?? 'â€”' }}
                            </div>

                        </td>

                        {{-- Isi Standar Tingkat 2 --}}
                        <td class="cell-content">

                            <div class="standard-level-box">
                                {{ $row['level'][1] ?? 'â€”' }}
                            </div>

                        </td>

                        {{-- Isi Standar Tingkat 3 --}}
                        <td class="cell-content">

                            <div class="standard-level-box">
                                {{ $row['level'][2] ?? 'â€”' }}
                            </div>

                        </td>

                        {{-- Semua indikator pada jalur yang sama --}}
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

                        {{-- Status per indikator --}}
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
                                        â€”
                                    </span>

                                @endforelse

                            </div>

                        </td>

                        {{-- Aksi per indikator --}}
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

                                                    {{-- Tombol membuka bukti pendukung --}}
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

                                                    {{-- Tombol edit penerapan --}}
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

                                                    {{-- Tombol hapus --}}
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

                                                {{-- Tambah penerapan --}}
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

                                                {{-- Terkunci jika periode tidak berjalan --}}
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
                                        â€”
                                    </span>

                                @endforelse

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
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
