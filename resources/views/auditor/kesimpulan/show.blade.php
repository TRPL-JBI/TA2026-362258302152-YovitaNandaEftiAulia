@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Kesimpulan Audit /

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

                Detail Kesimpulan Audit

            </h4>

            <small>

                Informasi Kesimpulan Audit Mutu Internal

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
        DETAIL
    ======================================================== -->

    <table class="detail-table">

        <tr>

            <th width="220">

                Periode AMI

            </th>

            <td>

                {{ $data->periodeAmi->tahun ?? '-' }}

            </td>

        </tr>

        <tr>

            <th>

                Kesimpulan Audit

            </th>

            <td>

                {!! nl2br(e($data->kesimpulan)) !!}

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

        <a href="{{ route('auditor.kesimpulan.index') }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

        <a href="{{ route('auditor.kesimpulan.edit',$data->id) }}"
           class="btn-save">

            <i class="bi bi-pencil"></i>

            Edit

        </a>

    </div>

</div>

@endsection
