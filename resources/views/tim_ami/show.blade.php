@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Periode AMI / Detail Tim AMI
</h3>

<div class="card">

    <div class="card-header">

        <h4>
            Detail Tim AMI
        </h4>

    </div>

    <table class="detail-table">

        <tr>

            <th width="220">
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

    </table>

    <br>

    <a
        href="{{ route('tim-ami.index', $tim->id_periode_ami) }}"
        class="btn-add">

        Kembali

    </a>

</div>

@endsection