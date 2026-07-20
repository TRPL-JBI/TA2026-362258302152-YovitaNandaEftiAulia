@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Rekomendasi /

    Detail

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

                Detail Rekomendasi Peningkatan

            </h4>

            <small>

                Informasi Rekomendasi Peningkatan Audit Mutu Internal

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
        DETAIL
    ======================================================== -->

    <table class="detail-table">

        <tr>

            <th width="220">

                Standar Mutu

            </th>

            <td>

                {{ $data->penerapan->standarmutuPeriode->standarMutu->nama_standar_mutu ?? '-' }}

            </td>

        </tr>

        <tr>

            <th>

                Aspek

            </th>

            <td>

                {{ $data->aspek }}

            </td>

        </tr>

        <tr>

            <th>

                Kelebihan

            </th>

            <td>

                {{ $data->kelebihan }}

            </td>

        </tr>

        <tr>

            <th>

                Rekomendasi

            </th>

            <td>

                {{ $data->rekomendasi }}

            </td>

        </tr>

        <tr>

            <th>

                Dibuat Oleh

            </th>

            <td>

                {{ $data->user->nama ?? '-' }}

            </td>

        </tr>

    </table>

    <!-- =======================================================
        BUTTON
    ======================================================== -->

    <div class="form-action">

        <a href="{{ route('auditor.rekomendasi.index') }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

        <a href="{{ route('auditor.rekomendasi.edit',$data->id) }}"
           class="btn-save">

            <i class="bi bi-pencil"></i>

            Edit

        </a>

    </div>

</div>

@endsection
