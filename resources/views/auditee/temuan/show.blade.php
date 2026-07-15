@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">

    Dashboard / Detail Temuan

</div>

<div class="card">

    <div class="card-header">

        <h2 class="card-title">

            Detail Temuan Audit

        </h2>

    </div>

    <table class="detail-table">

        <tbody>

            <tr>

                <th width="220">

                    Pertanyaan AMI

                </th>

                <td>

                    {{ $temuan->pertanyaan->pertanyaan }}

                </td>

            </tr>

            <tr>

                <th>

                    Temuan Auditor

                </th>

                <td>

                    {{ $temuan->temuan }}

                </td>

            </tr>

            <tr>

                <th>

                    Status

                </th>

                <td>

                    {{ $temuan->status_temuan }}

                </td>

            </tr>

            <tr>

                <th>

                    Tanggapan Auditee

                </th>

                <td>

                    @if($temuan->tanggapan->count())

                        {!! nl2br(e($temuan->tanggapan->first()->tanggapan)) !!}

                    @else

                        -

                    @endif

                </td>

            </tr>

        </tbody>

    </table>

    <div class="form-footer">

        <a href="{{ route('auditee.temuan.index') }}"
           class="btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

</div>

@endsection