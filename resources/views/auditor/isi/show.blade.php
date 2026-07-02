@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Isi Standar / Detail
</h3>

<div class="card">

    <div class="card-header"
         style="display:flex;justify-content:space-between;align-items:center;">

        <h4>Detail Isi Standar</h4>

        <a href="{{ route('auditor.isi.index',$isi->id_standar_mutu) }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <table class="table">

        <tr>

            <th width="220">Standar Mutu</th>

            <td>

                {{ $isi->standarMutu->nama_standar_mutu }}

            </td>

        </tr>

        <tr>

            <th>Nama Isi Standar</th>

            <td>

                {{ $isi->nama_standar }}

            </td>

        </tr>

        <tr>

            <th>Parent Standar</th>

            <td>

                {{ optional($isi->parent)->nama_standar ?? '-' }}

            </td>

        </tr>

    </table>

    @if($isi->indikator->count())

    <br>

    <h4>Indikator Standar</h4>

    <table>

        <thead>

            <tr>

                <th width="70">No.</th>

                <th>Nama Indikator</th>

            </tr>

        </thead>

        <tbody>

        @foreach($isi->indikator as $indikator)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $indikator->nama_indikator }}</td>

            </tr>

        @endforeach

        </tbody>

    </table>

    @endif

</div>

@endsection