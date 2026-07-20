@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Lampiran Audit /

    Tambah

</h3>

<!-- ===========================================================
    CARD
=========================================================== -->

<div class="card">

    <!-- =======================================================
        HEADER
    ======================================================== -->

    <div class="temuan-header">

        <div>

            <h4>

                Tambah Lampiran Audit

            </h4>

            <small>

                Form Tambah Lampiran Audit

            </small>

        </div>

    </div>

    <!-- =======================================================
        TAB MENU
    ======================================================== -->

    <div class="temuan-tab">

        <a href="{{ route('auditor.temuan.index') }}">

            Temuan Audit

        </a>

        <a href="{{ route('auditor.tanggapan.index') }}">

            Tanggapan Auditee

        </a>

        <a href="{{ route('auditor.akarmasalah.index') }}">

            Akar Masalah

        </a>

        <a href="{{ route('auditor.rekomendasi.index') }}">

            Rekomendasi

        </a>

        <a href="{{ route('auditor.kesimpulan.index') }}">

            Kesimpulan

        </a>

        <a href="{{ route('auditor.lampiran.index') }}"
           class="active">

            Lampiran

        </a>

    </div>

    <!-- =======================================================
        FORM
    ======================================================== -->

    <form
        action="{{ route('auditor.lampiran.store') }}"
        method="POST">

        @csrf

        <!-- PERIODE -->

        <div class="form-group">

            <label>

                Periode AMI

            </label>

            <select
                name="id_periode_ami"
                class="form-control"
                required>

                <option value="">

                    -- Pilih Periode AMI --

                </option>

              @foreach($periode as $item)

    <option
        value="{{ $item->id }}"
        @selected(
            old('id_periode_ami') == $item->id
        )
    >
        {{ $item->tahun }}

        â€”

        {{
            $item->unitKerja->nama
            ?? $item->unitKerja->nama_unit_kerja
            ?? '-'
        }}
    </option>

@endforeach

            </select>

        </div>

        <!-- LINK -->

        <div class="form-group">

            <label>

                Link Lampiran

            </label>

            <input
                type="url"
                name="link_file"
                class="form-control"
                value="{{ old('link_file') }}"
                placeholder="https://drive.google.com/..."
                required
            >

        </div>

        <!-- BUTTON -->

        <div class="form-action">

            <a
                href="{{ route('auditor.lampiran.index') }}"
                class="btn-back">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <button
                type="submit"
                class="btn-save">

                <i class="bi bi-check-lg"></i>

                Simpan

            </button>

        </div>

    </form>

</div>

@endsection
