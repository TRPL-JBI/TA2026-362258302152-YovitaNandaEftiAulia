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

    <a href="{{ route('auditor.pertanyaan.index',$periodeAmi->id) }}">
        Pertanyaan AMI
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

    <!-- HEADER -->
    <div class="card-header periode-header">

        <div>

            <h4>Data Jadwal AMI</h4>

            <small>

                Periode :

                <b>{{ $periodeAmi->tahun }}</b>

            </small>

        </div>

    </div>

    <!-- TABLE -->
    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>

                    <th width="70">No.</th>
                    <th>Nama Kegiatan</th>
                    <th width="220">Waktu</th>
                    <th width="90">Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($data as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->kegiatan }}</td>

                    <td>{{ $item->waktu }}</td>

                    <td>

                        <div class="action-buttons">

                            <a href="{{ route('auditor.jadwal.show',$item->id) }}"
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

                        Belum ada Jadwal AMI

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection