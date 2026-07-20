@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('periode-ami.show',$periodeAmi->id) }}">
        Detail Periode AMI
    </a>

    <a href="{{ route('penerapan.index',$periodeAmi->id) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('tim-ami.index',$periodeAmi->id) }}">
        Tim AMI
    </a>

    <a href="{{ route('jadwal.index',$periodeAmi->id) }}"
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

        <a href="{{ route('jadwal.create',$periodeAmi->id) }}"
           class="btn-add">

            <i class="bi bi-plus-lg"></i>
            Tambah Jadwal

        </a>

    </div>

    <!-- TABLE -->
    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>

                    <th width="70">No.</th>
                    <th>Nama Kegiatan</th>
                    <th width="220">Waktu</th>
                    <th width="220">Aksi</th>

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

                            <!-- DETAIL -->
                            <a href="{{ route('jadwal.show',$item->id) }}"
                               class="btn-icon btn-detail">

                                <i class="bi bi-eye"></i>

                            </a>

                            <!-- EDIT -->
                            <a href="{{ route('jadwal.edit',$item->id) }}"
                               class="btn-icon btn-edit">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <!-- DELETE -->
                            <a href="{{ route('jadwal.delete',$item->id) }}"
                               class="btn-icon btn-delete">

                                <i class="bi bi-trash"></i>

                            </a>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4" style="text-align:center;padding:20px;">

                        Belum ada Jadwal AMI

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
