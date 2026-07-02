@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Audit Mutu Internal / Detail Temuan Audit
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('auditor.temuan.index') }}"
       class="active">
        Temuan Audit
    </a>

    <a href="#">
        Tanggapan Auditee
    </a>

    <a href="#">
        Akar Masalah
    </a>

    <a href="#">
        Rekomendasi
    </a>

    <a href="#">
        Kesimpulan
    </a>

    <a href="#">
        Lampiran
    </a>

</div>

<div class="card">

    <div class="card-header periode-header">

        <div>

            <h4>Detail Temuan Audit</h4>

        </div>

        <a href="{{ route('auditor.temuan.index') }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <table class="table-detail">

        <tr>

            <th width="250">
                Periode AMI
            </th>

            <td>

                {{ $data->pertanyaan->penerapanStandar->standarMutuPeriodeAmi->periodeAmi->tahun }}

            </td>

        </tr>

        <tr>

            <th>

                Unit Kerja

            </th>

            <td>

                {{ $data->pertanyaan->penerapanStandar->standarMutuPeriodeAmi->periodeAmi->unitKerja->nama }}

            </td>

        </tr>

        <tr>

            <th>

                Standar Mutu

            </th>

            <td>

                {{ $data->pertanyaan->penerapanStandar->standarMutuPeriodeAmi->standarMutu->nama_standar_mutu }}

            </td>

        </tr>

        <tr>

            <th>

                Pertanyaan AMI

            </th>

            <td>

                {{ $data->pertanyaan->pertanyaan }}

            </td>

        </tr>

        <tr>

            <th>

                Temuan Audit

            </th>

            <td>

                {{ $data->temuan }}

            </td>

        </tr>

        <tr>

            <th>

                Status

            </th>

            <td>

                @if($data->status_temuan=='open')

                    <span class="badge-draft">

                        Open

                    </span>

                @else

                    <span class="badge-berjalan">

                        Closed

                    </span>

                @endif

            </td>

        </tr>

    </table>

</div>

@endsection