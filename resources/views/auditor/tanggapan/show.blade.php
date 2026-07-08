@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Tanggapan Auditee /

    Detail

</h3>

<div class="card">

    <div class="card-header">

        <h4>

            Detail Tanggapan Auditee

        </h4>

        <a
            href="{{ route('auditor.tanggapan.index') }}"
            class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <table class="table-detail">

        <tr>

            <th width="220">

                Temuan Audit

            </th>

            <td>

                {{ $tanggapan->temuan->temuan ?? '-' }}

            </td>

        </tr>

        <tr>

            <th>

                Tanggapan Auditee

            </th>

            <td>

                {{ $tanggapan->tanggapan }}

            </td>

        </tr>

        <tr>

            <th>

                Nama Auditee

            </th>

            <td>

                {{ $tanggapan->user->nama ?? '-' }}

            </td>

        </tr>

    </table>

</div>

@endsection