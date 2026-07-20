@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Rekomendasi /

    Tambah

</h3>

<div class="card">

    <!-- =======================================================
        HEADER
    ======================================================== -->

    <div class="temuan-header">

        <div>

            <h4>

                Tambah Rekomendasi Peningkatan

            </h4>

            <small>

                Form Tambah Rekomendasi Peningkatan

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

        <a href="{{ route('auditor.rekomendasi.index') }}"
           class="active">

            Rekomendasi

        </a>

        <a href="#">

            Kesimpulan

        </a>

        <a href="#">

            Lampiran

        </a>

    </div>

    <!-- =======================================================
        FORM
    ======================================================== -->

    <form
        action="{{ route('auditor.rekomendasi.store') }}"
        method="POST">

        @csrf

        <div class="form-group">

            <label>

                Penerapan Standar

            </label>

            <select
                name="id_penerapan_standar"
                class="form-control"
                required>

                <option value="">

                    -- Pilih Penerapan --

                </option>

                @foreach($penerapan as $item)

                    <option value="{{ $item->id }}">

                        {{ $item->standarmutuPeriode->standarMutu->nama_standar_mutu ?? 'Standar Mutu' }}

                    </option>

                @endforeach

            </select>

        </div>

        <div class="form-group">

            <label>

                Aspek

            </label>

            <input
                type="text"
                name="aspek"
                class="form-control"
                required>

        </div>

        <div class="form-group">

            <label>

                Kelebihan

            </label>

            <textarea
                name="kelebihan"
                rows="4"
                class="form-control"
                required></textarea>

        </div>

        <div class="form-group">

            <label>

                Rekomendasi

            </label>

            <textarea
                name="rekomendasi"
                rows="5"
                class="form-control"
                required></textarea>

        </div>

        <div class="form-action">

            <a href="{{ route('auditor.rekomendasi.index') }}"
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
