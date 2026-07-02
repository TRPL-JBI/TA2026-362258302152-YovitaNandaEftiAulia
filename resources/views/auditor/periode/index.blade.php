@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Periode AMI
</h3>

<div class="card">

    <div class="card-header">

        <h4>Data Periode AMI</h4>

    </div>

    <table>

        <thead>

            <tr>

                <th width="60">No.</th>

                <th>Tahun</th>

                <th>Standar Mutu</th>

                <th>Unit Kerja</th>

                <th>Status</th>

                <th width="300">Aksi</th>

            </tr>

        </thead>

        <tbody>

        @forelse($data as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $item->tahun }}</td>

                <td>{{ $item->standarMutu->nama_standar_mutu }}</td>

                <td>{{ $item->unitKerja->nama }}</td>

                <td>{{ $item->status }}</td>

                <td>

                    <div class="action-buttons">

                        <!-- Detail -->

                        <a href="{{ route('auditor.periode.show',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        <!-- Jadwal -->

                        <a href="{{ route('auditor.jadwal.index',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-calendar-event"></i>

                        </a>

                        <!-- Tim -->

                        <a href="{{ route('auditor.tim.index',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-people"></i>

                        </a>

                        <!-- Pertanyaan -->

                        <a href="{{ route('auditor.pertanyaan.index',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-patch-question"></i>

                        </a>

                        <!-- Penerapan -->

                        <a href="{{ route('auditor.penerapan.index',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-clipboard-check"></i>

                        </a>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="6"
                    style="text-align:center;padding:20px;">

                    Belum ada Periode AMI

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection