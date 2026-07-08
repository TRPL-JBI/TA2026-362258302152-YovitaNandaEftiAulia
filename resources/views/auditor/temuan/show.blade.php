@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Detail Temuan Audit

</h3>

<div class="card">

    <div class="card-header">

        <h4>

            Detail Temuan Audit

        </h4>

        <a
            href="{{ route('auditor.temuan.index') }}"
            class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <table class="table-detail">

        <tr>

            <th width="220">

                Pertanyaan Audit

            </th>

            <td>

                {{ $temuan->pertanyaan->pertanyaan }}

            </td>

        </tr>

        <tr>

            <th>

                Temuan Audit

            </th>

            <td>

                {{ $temuan->temuan }}

            </td>

        </tr>

        <tr>

            <th>

                Status Temuan

            </th>

            <td>

                @if($temuan->status_temuan=='Open')

                    <span class="badge-open">

                        Open

                    </span>

                @else

                    <span class="badge-close">

                        Closed

                    </span>

                @endif

            </td>

        </tr>

        <tr>

            <th>

                Dibuat Oleh

            </th>

            <td>

                {{ $temuan->pertanyaan->user->nama ?? '-' }}

            </td>

        </tr>

    </table>

</div>

@endsection