@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
</h3>

<div class="tab-menu">

    <a href="{{ route('auditor.periode.show',$periodeAmi->id) }}">
        Detail Periode AMI
    </a>

    <a href="{{ route('auditor.penerapan.index',$periodeAmi->id) }}"
       class="active">
        Penerapan Standar
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

        <h4>Detail Penerapan Standar</h4>

    </div>

    <div class="table-wrapper">

        <table class="custom-table">

            <tbody>

                <tr>

                    <th width="250">
                        Standar Mutu
                    </th>

                    <td>

                        {{ $data->standarMutuPeriodeAmi->standarMutu->nama_standar_mutu }}

                    </td>

                </tr>

                <tr>

                    <th>
                        Deskripsi Hasil
                    </th>

                    <td>

                        {!! nl2br(e($data->deskripsi_hasil)) !!}

                    </td>

                </tr>

                <tr>

                    <th>
                        Link Bukti
                    </th>

                    <td>

                        @if($data->link_bukti)

                            <a href="{{ $data->link_bukti }}"
                               target="_blank">

                                {{ $data->link_bukti }}

                            </a>

                        @else

                            -

                        @endif

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
