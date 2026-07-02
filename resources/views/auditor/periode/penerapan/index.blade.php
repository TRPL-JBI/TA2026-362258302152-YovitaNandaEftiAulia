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

    <a href="{{ route('auditor.pertanyaan.index',$periodeAmi->id) }}">
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

            <h4>Data Penerapan Standar</h4>

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

                    <th>Standar Mutu</th>

                    <th>Deskripsi Hasil</th>

                    <th>Bukti</th>

                    <th>Dibuat Oleh</th>

                    <th width="90">Aksi</th>

                </tr>

            </thead>

            <tbody>

            @forelse($data as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>

                        {{ $item->standarMutuPeriodeAmi->standarMutu->nama_standar_mutu }}

                    </td>

                    <td>

                        {{ Str::limit($item->deskripsi_hasil,80) }}

                    </td>

                    <td>

                        @if($item->link_bukti)

                            <a href="{{ $item->link_bukti }}"
                               target="_blank">

                                Lihat Bukti

                            </a>

                        @else

                            -

                        @endif

                    </td>

                    <td>

                        {{ $item->user->nama ?? '-' }}

                    </td>

                    <td>

                        <div class="action-buttons">

                            <a href="{{ route('auditor.penerapan.show',[$periodeAmi->id,$item->id]) }}"
                               class="btn-icon btn-detail">

                                <i class="bi bi-eye"></i>

                            </a>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6"
                        style="text-align:center;padding:20px;">

                        Belum ada Data

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection