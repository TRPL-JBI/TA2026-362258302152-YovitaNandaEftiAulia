@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('auditor.periode.show',$periodeAmi->id) }}">
        Detail Periode AMI
    </a>

    <a href="{{ route('auditor.penerapan.index',$periodeAmi->id) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('auditor.pertanyaan.index',$periodeAmi->id) }}"
       class="active">
        Pertanyaan AMI
    </a>

    <a href="{{ route('auditor.tim.index',$periodeAmi->id) }}">
        Tim AMI
    </a>

    <a href="{{ route('auditor.jadwal.index',$periodeAmi->id) }}">
        Jadwal AMI
    </a>

</div>

<div class="card">

    <div class="card-header periode-header">

        <div>

            <h4>Detail Pertanyaan AMI</h4>

        </div>

    </div>

    <div class="table-wrapper">

        <table class="custom-table">

            <tbody>

                <tr>

                    <th width="220">

                        Standar Mutu

                    </th>

                    <td>

                        {{ $data->penerapanStandar->standarMutuPeriodeAmi->standarMutu->nama_standar_mutu }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Indikator

                    </th>

                    <td>

                        {{ $data->indikator }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Referensi

                    </th>

                    <td>

                        {{ $data->referensi }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Pertanyaan

                    </th>

                    <td>

                        {!! nl2br(e($data->pertanyaan)) !!}

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

            </tbody>

        </table>

    </div>

</div>

@endsection