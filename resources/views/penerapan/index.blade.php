@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Penerapan Standar
</h3>

<div class="tab-menu">

    <a href="{{ route('periode-ami.show',$periode->id) }}"
       class="active">
        Detail Periode AMI
    </a>

    <a href="{{ route('penerapan.index',$periode->id) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('pertanyaan.index',$periode->id) }}">
        Pertanyaan AMI
    </a>

    <a href="{{ route('tim-ami.index',$periode->id) }}">
        Tim AMI
    </a>

    <a href="{{ route('jadwal.index',$periode->id) }}">
    Jadwal AMI
    </a>

</div>

<div class="card">

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>
                    <th>No</th>
                    <th>Standar Mutu</th>
                    <th>Deskripsi Hasil</th>
                    <th>Bukti</th>
                    <th>Dibuat Oleh</th>
                    <th>Aksi</th>
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

                        <a href="{{ route('penerapan.show',
                            [$periode->id,$item->id]) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6">
                        Belum ada data penerapan standar
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection