@extends('layouts.app')

@section('content')

<link
    rel="stylesheet"
    href="{{ asset('css/19-admin-unit-kerja.css') }}"
>

<div class="unit-page">

    <h3 class="unit-breadcrumb">
        Dashboard / Detail Unit Kerja
    </h3>

    <div class="unit-card unit-detail-card">

        <h1 class="unit-form-title">
            Detail Unit Kerja
        </h1>

        <div class="unit-detail-grid">

            <div class="unit-detail-item">

                <span class="unit-detail-label">
                    Nama Unit Kerja
                </span>

                <span class="unit-detail-value">
                    {{ $unitKerja->nama }}
                </span>

            </div>

            <div class="unit-detail-item">

                <span class="unit-detail-label">
                    Kategori Unit Kerja
                </span>

                <span class="unit-detail-value">
                    {{ ucwords($unitKerja->kategori_unit_kerja) }}
                </span>

            </div>

            <div class="unit-detail-item">

                <span class="unit-detail-label">
                    Dibuat Oleh
                </span>

                <span class="unit-detail-value">
                    {{ $unitKerja->user->nama ?? '-' }}
                </span>

            </div>

        </div>

        <div class="unit-form-actions unit-detail-actions">

            <a
                href="{{ route('unit-kerja.edit', $unitKerja->id) }}"
                class="unit-save-button"
            >
                Edit
            </a>

            <a
                href="{{ route('unit-kerja.index') }}"
                class="unit-cancel-button"
            >
                Kembali
            </a>

        </div>

    </div>

</div>

@endsection