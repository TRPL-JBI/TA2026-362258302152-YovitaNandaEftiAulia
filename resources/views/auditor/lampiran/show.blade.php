@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Lampiran Audit /

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

                Detail Lampiran Audit

            </h4>

            <small>

                Informasi Lampiran Audit Mutu Internal

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

                Nama File

            </th>

            <td>

                {{ $data->nama_file }}

            </td>

        </tr>

        <tr>

            <th>

                File Lampiran

            </th>

            <td>

                @if($data->file)

                    <a
                        href="{{ asset('uploads/lampiran/'.$data->file) }}"
                        target="_blank"
                        class="btn-detail">

                        <i class="bi bi-download"></i>

                        Lihat / Download File

                    </a>

                @else

                    -

                @endif

            </td>

        </tr>

        <tr>

            <th>

                Keterangan

            </th>

            <td>

                {{ $data->keterangan ?? '-' }}

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

        <a
            href="{{ route('auditor.lampiran.index') }}"
            class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

        <a
            href="{{ route('auditor.lampiran.edit',$data->id) }}"
            class="btn-save">

            <i class="bi bi-pencil"></i>

            Edit

        </a>

    </div>

</div>

@endsection