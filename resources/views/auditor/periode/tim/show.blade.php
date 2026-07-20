@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
</h3>

<div class="tab-menu">

    <a href="{{ route('auditor.periode.show',$periodeAmi->id) }}">
        Detail Periode AMI
    </a>

    <a href="{{ route('auditor.penerapan.index',$periodeAmi->id) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('auditor.tim.index',$periodeAmi->id) }}"
       class="active">
        Tim AMI
    </a>

    <a href="{{ route('auditor.jadwal.index',$periodeAmi->id) }}">
        Jadwal AMI
    </a>

</div>

<div class="card">

    <div class="card-header periode-header">

        <h4>Detail Tim AMI</h4>

    </div>

    <div class="table-wrapper">

        <table class="custom-table">

            <tbody>

                <tr>

                    <th width="250">

                        Nama Auditor

                    </th>

                    <td>

                        {{ $tim->user->nama }}

                    </td>

                </tr>

                <tr>

                    <th>

                        Role

                    </th>

                    <td>

                        @switch($tim->role)

                            @case('ketua auditor')

                                Ketua Auditor

                                @break

                            @case('auditor')

                                Auditor

                                @break

                            @default

                                Auditee

                        @endswitch

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection
