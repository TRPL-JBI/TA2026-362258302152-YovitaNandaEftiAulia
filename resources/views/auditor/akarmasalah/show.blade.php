@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Akar Masalah /

    Detail

</h3>

<!-- ===========================================================
    TAB MENU
=========================================================== -->

<div class="temuan-tab">

    <a href="{{ route('auditor.temuan.index') }}">

        Temuan Audit

    </a>

    <a href="{{ route('auditor.tanggapan.index') }}">

        Tanggapan Auditee

    </a>

    <a href="{{ route('auditor.akarmasalah.index') }}"
       class="active">

        Akar Masalah

    </a>

    <a href="#">

        Rekomendasi

    </a>

    <a href="#">

        Kesimpulan

    </a>

    <a href="#">

        Lampiran

    </a>

</div>

<!-- ===========================================================
    CARD
=========================================================== -->

<div class="card">

    <div class="card-header">

        <h4>

            Detail Akar Masalah

        </h4>

    </div>

    <table class="detail-table">

        <tr>

            <th width="220">

                Temuan Audit

            </th>

            <td>

                {{ $data->temuan->temuan ?? '-' }}

            </td>

        </tr>

        <tr>

            <th>

                Akar Masalah

            </th>

            <td>

                {{ $data->akar_masalah }}

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

    <div class="form-action">

        <a href="{{ route('auditor.akarmasalah.index') }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

</div>

@endsection
