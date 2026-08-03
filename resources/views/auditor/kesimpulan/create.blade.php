@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/app/18-auditor-kesimpulan.css') }}"
    >
@endpush

@section('content')

<div class="audit-form-page">

    {{-- =====================================================
         BREADCRUMB
    ====================================================== --}}

    <div class="audit-form-breadcrumb">

        <span>
            Dashboard
        </span>

        <i class="bi bi-chevron-right"></i>

        <span>
            Audit Mutu Internal
        </span>

        <i class="bi bi-chevron-right"></i>

        <span>
            Kesimpulan Audit
        </span>

        <i class="bi bi-chevron-right"></i>

        <strong>
            Tambah
        </strong>

    </div>

    {{-- =====================================================
         CARD FORM
    ====================================================== --}}

    <section class="audit-form-card">

        <div class="audit-form-header">

            <div>

                <span class="audit-form-label">
                    KESIMPULAN AUDIT
                </span>

                <h2>
                    Tambah Kesimpulan Audit
                </h2>

                <p>
                    Isi kesimpulan hasil pelaksanaan Audit Mutu Internal
                    berdasarkan periode yang dipilih.
                </p>

            </div>

            <div class="audit-form-header-icon">

                <i class="bi bi-file-earmark-check"></i>

            </div>

        </div>

        {{-- =================================================
             ERROR VALIDASI
        ================================================== --}}

        @if($errors->any())

            <div class="audit-form-alert">

                <i class="bi bi-exclamation-circle-fill"></i>

                <div>

                    <strong>
                        Data belum dapat disimpan.
                    </strong>

                    <ul>

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        @endif

        {{-- =================================================
             FORM
        ================================================== --}}

        <form
            action="{{ route('auditor.kesimpulan.store') }}"
            method="POST"
        >

            @csrf

            {{-- PERIODE AMI --}}

            <div class="audit-form-group">

                <label for="id_periode_ami">

                    Periode AMI

                    <span class="required-mark">
                        *
                    </span>

                </label>

                <select
                    name="id_periode_ami"
                    id="id_periode_ami"
                    class="audit-form-control"
                    required
                >

                    <option value="">
                        -- Pilih Periode AMI --
                    </option>

                    @forelse($periodeAmi as $periode)

                        @php
                            $nilaiPeriodeTerpilih = old(
                                'id_periode_ami',
                                $periodeTerpilih
                                    ?? request('id_periode_ami')
                            );
                        @endphp

                        <option
                            value="{{ $periode->id }}"
                            {{
                                (string) $nilaiPeriodeTerpilih
                                === (string) $periode->id
                                    ? 'selected'
                                    : ''
                            }}
                        >

                            Periode {{ $periode->tahun }}

                            @if(
                                trim(
                                    (string) (
                                        $periode->status
                                        ?? ''
                                    )
                                ) !== ''
                            )

                                -
                                {{ ucfirst($periode->status) }}

                            @endif

                        </option>

                    @empty

                        <option
                            value=""
                            disabled
                        >
                            Data Periode AMI belum tersedia
                        </option>

                    @endforelse

                </select>

                @error('id_periode_ami')

                    <small class="audit-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>

            {{-- KESIMPULAN AUDIT --}}

            <div class="audit-form-group">

                <label for="kesimpulan">

                    Kesimpulan Audit

                    <span class="required-mark">
                        *
                    </span>

                </label>

                <textarea
                    name="kesimpulan"
                    id="kesimpulan"
                    rows="7"
                    class="audit-form-control"
                    placeholder="Tuliskan kesimpulan hasil Audit Mutu Internal..."
                    required
                >{{ old('kesimpulan') }}</textarea>

                @error('kesimpulan')

                    <small class="audit-form-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>

            {{-- TOMBOL --}}

            <div class="audit-form-actions">

                <a
                    href="{{ route('auditor.temuan.index') }}"
                    class="audit-form-button button-back"
                >

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>

                <button
                    type="submit"
                    class="audit-form-button button-save"
                >

                    <i class="bi bi-check-lg"></i>

                    Simpan

                </button>

            </div>

        </form>

    </section>

</div>

@endsection