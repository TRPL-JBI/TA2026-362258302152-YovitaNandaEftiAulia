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

    <a href="{{ route('auditor.tim.index',$periodeAmi->id) }}">
        Tim AMI
    </a>

    <a href="{{ route('auditor.jadwal.index',$periodeAmi->id) }}"
       class="active">
        Jadwal AMI
    </a>

</div>

<div class="card">

    <div class="card-header periode-header">

        <div>

            <h4>Detail Jadwal AMI</h4>

        </div>

    </div>

    <div class="table-wrapper">

        <table class="custom-table">

            <tbody>

                <tr>

                    <th width="250">
                        Nama Kegiatan
                    </th>

                    <td>

                        {{ $jadwal->kegiatan }}

                    </td>

                </tr>

                <tr>

                    <th>
                        Waktu
                    </th>

                    <td>

                        {{ $jadwal->waktu }}

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection
