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

            <h4>Data Pertanyaan AMI</h4>

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

                    <th>No</th>

                    <th>Indikator</th>

                    <th>Pertanyaan</th>

                    <th>Dibuat Oleh</th>

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

                    {{ $item->indikator }}

                </td>

                <td>

                    {{ $item->pertanyaan }}

                </td>

                <td>

                    {{ $item->user->nama ?? '-' }}

                </td>

                <td>

                    <div class="action-buttons">

                        <a href="{{ route('auditor.pertanyaan.show',[$periodeAmi->id,$item->id]) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="5"
                    style="text-align:center;padding:20px;">

                    Belum ada Pertanyaan AMI

                </td>

            </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection