@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/app/18-auditor-kesimpulan.css') }}"
    >
@endpush

@section('content')

<div class="audit-detail-page">

    {{-- BREADCRUMB --}}
    <div class="audit-detail-breadcrumb">

        <a href="{{ route('dashboard.auditor') }}">
            Dashboard
        </a>

        <i class="bi bi-chevron-right"></i>

        <a href="{{ route('auditor.temuan.index') }}">
            Audit Mutu Internal
        </a>

        <i class="bi bi-chevron-right"></i>

        <a href="{{ route('auditor.kesimpulan.index') }}">
            Kesimpulan Audit
        </a>

        <i class="bi bi-chevron-right"></i>

        <strong>
            Detail
        </strong>

    </div>

    {{-- HEADER --}}
    <div class="audit-detail-header">

        <div>
            <span class="audit-detail-label">
                KESIMPULAN AUDIT
            </span>

            <h1>
                Detail Kesimpulan Audit
            </h1>

            <p>
                Informasi kesimpulan hasil pelaksanaan Audit Mutu Internal
                pada periode yang dipilih.
            </p>
        </div>

        <div class="audit-detail-header-icon">
            <i class="bi bi-file-earmark-check"></i>
        </div>

    </div>

    {{-- CARD INFORMASI --}}
    <section class="audit-detail-card">

        <div class="audit-detail-card-title">

            <div>
                <h2>
                    Informasi Kesimpulan
                </h2>

                <p>
                    Detail periode dan hasil kesimpulan audit.
                </p>
            </div>

            <span class="audit-detail-badge">
                <i class="bi bi-eye"></i>
                Detail
            </span>

        </div>

        <div class="audit-detail-grid">

            {{-- PERIODE --}}
            <div class="audit-detail-item">

                <div class="audit-detail-item-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>
                    <span class="audit-detail-item-label">
                        Periode AMI
                    </span>

                    <strong>
                        Periode {{ $kesimpulan->periodeAmi->tahun ?? '-' }}
                    </strong>
                </div>

            </div>

            {{-- STATUS --}}
            <div class="audit-detail-item">

                <div class="audit-detail-item-icon">
                    <i class="bi bi-check-circle"></i>
                </div>

                <div>
                    <span class="audit-detail-item-label">
                        Status Periode
                    </span>

                    @php
                        $status = strtolower(
                            trim(
                                (string) (
                                    $kesimpulan->periodeAmi->status
                                    ?? '-'
                                )
                            )
                        );
                    @endphp

                    <span class="audit-status-badge status-{{ $status }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>

            </div>

            {{-- DIBUAT OLEH --}}
            <div class="audit-detail-item">

                <div class="audit-detail-item-icon">
                    <i class="bi bi-person"></i>
                </div>

                <div>
                    <span class="audit-detail-item-label">
                        Dibuat Oleh
                    </span>

                    <strong>
                        {{ $kesimpulan->user->nama ?? 'Auditor' }}
                    </strong>
                </div>

            </div>

            {{-- JENIS DOKUMEN --}}
            <div class="audit-detail-item">

                <div class="audit-detail-item-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                <div>
                    <span class="audit-detail-item-label">
                        Jenis Dokumen
                    </span>

                    <strong>
                        Kesimpulan Audit
                    </strong>
                </div>

            </div>

        </div>

        {{-- HASIL KESIMPULAN --}}
        <div class="audit-conclusion-section">

            <div class="audit-conclusion-title">
                <i class="bi bi-card-text"></i>

                <div>
                    <span>
                        HASIL KESIMPULAN
                    </span>

                    <h2>
                        Kesimpulan Audit
                    </h2>
                </div>
            </div>

            <div class="audit-conclusion-content">
                {{ $kesimpulan->kesimpulan ?? '-' }}
            </div>

        </div>

        {{-- TOMBOL --}}
        <div class="audit-detail-actions">

            <a
                href="{{ route('auditor.kesimpulan.index') }}"
                class="audit-detail-button button-back"
            >
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

            <div class="audit-detail-actions-right">

                <a
                    href="{{ route(
                        'auditor.kesimpulan.edit',
                        $kesimpulan->id
                    ) }}"
                    class="audit-detail-button button-edit"
                >
                    <i class="bi bi-pencil-square"></i>
                    Edit
                </a>

                <form
                    action="{{ route(
                        'auditor.kesimpulan.destroy',
                        $kesimpulan->id
                    ) }}"
                    method="POST"
                    onsubmit="
                        return confirm(
                            'Apakah Anda yakin ingin menghapus kesimpulan ini?'
                        );
                    "
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="audit-detail-button button-delete"
                    >
                        <i class="bi bi-trash"></i>
                        Hapus
                    </button>
                </form>

            </div>

        </div>

    </section>

</div>

@endsection