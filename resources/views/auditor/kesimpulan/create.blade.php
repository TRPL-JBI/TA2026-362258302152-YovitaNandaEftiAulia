@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Kesimpulan Audit /

    Tambah

</h3>

<div class="card">

    <!-- =======================================================
        HEADER
    ======================================================== -->

    <div class="temuan-header">

        <div>

            <h4>

                Tambah Kesimpulan Audit

            </h4>

            <small>

                Form Tambah Kesimpulan Audit

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

        <a href="{{ route('auditor.kesimpulan.index') }}"
           class="active">

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
        action="{{ route('auditor.kesimpulan.store') }}"
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

                    <option value="{{ $item->id }}">

                        {{ $item->tahun }}

                    </option>

                @endforeach

            </select>

        </div>

        <!-- KESIMPULAN -->

        <div class="form-group">

            <label>

                Kesimpulan Audit

            </label>

            <textarea
                name="kesimpulan"
                rows="8"
                class="form-control"
                required></textarea>

        </div>

        <!-- BUTTON -->

        <div class="form-action">

            <a href="{{ route('auditor.kesimpulan.index') }}"
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