@extends('layouts.app')

@section('content')

<div class="breadcrumb">

    Dashboard / Periode AMI / Detail Tim AMI

</div>

<div class="card">

    <div class="card-header">

        <h2 class="card-title">

            Detail Tim AMI

        </h2>

    </div>

    <div class="table-wrapper">

        <table class="detail-table">

            <tbody>

                <tr>

                    <th width="280">

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

                        @if($tim->role == 'ketua auditor')

                            Ketua Auditor

                        @elseif($tim->role == 'auditor')

                            Auditor

                        @else

                            Auditee

                        @endif

                    </td>

                </tr>

                <tr>

                    <th>

                        Periode AMI

                    </th>

                    <td>

                        {{ $tim->periode->tahun }}

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <div class="form-footer">

        <a href="{{ route('tim-ami.index',$tim->id_periode_ami) }}"
           class="btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

</div>

@endsection