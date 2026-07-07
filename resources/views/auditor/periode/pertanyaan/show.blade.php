@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Audit Mutu Internal
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('auditor.temuan.index') }}">
        Temuan Audit
    </a>

    <a href="{{ route('auditor.pertanyaan.index',
        $data->penerapanStandar->standarMutuPeriodeAmi->id_periode_ami) }}"
       class="active">

        Pertanyaan AMI

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

            <h4>Detail Pertanyaan AMI</h4>

        </div>

        <a href="{{ route('auditor.pertanyaan.index',
            $data->penerapanStandar->standarMutuPeriodeAmi->id_periode_ami) }}"
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

                {{ $data->penerapanStandar->standarMutuPeriodeAmi->periodeAmi->tahun }}

            </td>

        </tr>

        <tr>

            <th>
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
                Pertanyaan AMI
            </th>

            <td>

                {{ $data->pertanyaan }}

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

    </table>

</div>

@endsection