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

        <div class="header-left">

            <h4>Data Tim AMI</h4>

            <small>

                Periode AMI :

                <b>{{ $periodeAmi->tahun }}</b>

            </small>

        </div>

    </div>

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>

                    <th>No.</th>

                    <th>Nama Auditor</th>

                    <th>Role</th>

                    <th width="90">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($data as $item)

                <tr>

                    <td>

                        {{ $loop->iteration }}

                    </td>

                    <td>

                        {{ $item->user->nama }}

                    </td>

                    <td>

                        @switch($item->role)

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

                    <td>

                        <div class="action-buttons">

                            <a href="{{ route('auditor.tim.show',$item->id) }}"
                               class="btn-icon btn-detail">

                                <i class="bi bi-eye"></i>

                            </a>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4"
                        style="text-align:center;padding:20px;">

                        Belum ada Tim AMI

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
