@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('periode-ami.show',$periode->id) }}"
       class="active">
        Detail Periode AMI
    </a>

    <a href="{{ route('penerapan.index',$periode->id) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('tim-ami.index',$periode->id) }}">
        Tim AMI
    </a>

    <a href="{{ route('jadwal.index',$periode->id) }}">
    Jadwal AMI
    </a>

</div>

<div class="card">

    <!-- HEADER -->
    <div class="card-header periode-header">

        <div class="header-left">

            <h4>Detail Periode AMI</h4>

        </div>

        <div class="header-right">

            <a href="{{ route('periode-ami.create') }}"
               class="btn-add">

                <i class="bi bi-plus-lg"></i>
                Buat Periode AMI

            </a>

        </div>

    </div>

    <!-- TABLE -->
    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>

                    <th>No.</th>
                    <th>Tahun AMI</th>
                    <th>Standar Mutu</th>
                    <th>Unit Kerja</th>
                    <th>Ketua AMI</th>
                    <th>Tujuan Audit</th>
                    <th>Lingkup Audit</th>
                    <th>Tanggal Audit</th>
                    <th>Status</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>1</td>

                    <td>{{ $periode->tahun }}</td>

                    <td>{{ $periode->standarMutu->nama_standar_mutu ?? '-' }}</td>

                    <td>{{ $periode->unitKerja->nama ?? '-' }}</td>

                    <td>{{ $periode->user->nama ?? '-' }}</td>

                    <td>{{ $periode->tujuan_audit }}</td>

                    <td>{{ $periode->lingkup_audit }}</td>

                    <td>
                        {{ $periode->tanggal_buka_ami }}
                        -
                        {{ $periode->tanggal_tutup_ami }}
                    </td>

                    <td>

                        @if($periode->status == 'berjalan')

                            <span class="badge-berjalan">
                                Berjalan
                            </span>

                        @elseif($periode->status == 'draft')

                            <span class="badge-draft">
                                Draft
                            </span>

                        @else

                            <span class="badge-ditutup">
                                Ditutup
                            </span>

                        @endif

                    </td>

                    <td>

                        <div class="action-buttons">

                            <!-- DETAIL -->
                            <a href="{{ route('periode-ami.show',$periode->id) }}"
                               class="btn-icon btn-detail">

                                <i class="bi bi-eye"></i>

                            </a>

                            <!-- EDIT -->
                            <a href="{{ route('periode-ami.edit',$periode->id) }}"
                               class="btn-icon btn-edit">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <!-- DELETE -->
                            <form action="{{ route('periode-ami.destroy',$periode->id) }}"
                                  method="POST"
                                  style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn-icon btn-delete"
                                        onclick="return confirm('Yakin hapus data?')">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection
