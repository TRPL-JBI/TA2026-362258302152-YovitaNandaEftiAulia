@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Periode AMI
</h3>

<div class="card">

    <!-- HEADER -->
    <div class="card-header periode-header">

        <div class="header-left">

            <h4>Data Periode AMI</h4>

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
                    <th>Tanggal Audit</th>
                    <th>Status</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                @forelse($data as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->tahun }}</td>

                    <td>
                        {{ $item->standarMutu->nama_standar_mutu ?? '-' }}
                    </td>

                    <td>
    @if ($item->unitKerjas->isNotEmpty())
        <div style="display:flex; flex-wrap:wrap; gap:6px;">
            @foreach ($item->unitKerjas as $unit)
                <span style="
                    display:inline-block;
                    padding:5px 10px;
                    border-radius:20px;
                    background:#eef2ff;
                    color:#4338ca;
                    font-size:12px;
                    font-weight:600;
                ">
                    {{ $unit->nama ?? '-' }}
                </span>
            @endforeach
        </div>
    @elseif ($item->unitKerja)
        {{ $item->unitKerja->nama ?? '-' }}
    @else
        -
    @endif
</td>

                    <td>
                        {{ $item->tanggal_buka_ami ?? '-' }}
                        -
                        {{ $item->tanggal_tutup_ami ?? '-' }}
                    </td>

                    <td>

                        @if($item->status == 'berjalan')

                            <span class="badge-berjalan">
                                Berjalan
                            </span>

                        @elseif($item->status == 'draft')

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
                            <a href="{{ route('periode-ami.show',$item->id) }}"
                               class="btn-icon btn-detail">

                                <i class="bi bi-eye"></i>

                            </a>

                            <!-- EDIT -->
                            <a href="{{ route('periode-ami.edit',$item->id) }}"
                               class="btn-icon btn-edit">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <!-- DELETE -->
                            <a href="{{ route('periode-ami.delete',$item->id) }}"
                               class="btn-icon btn-delete">

                                <i class="bi bi-trash"></i>

                            </a>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7" style="text-align:center;padding:25px;">

                        Data Periode AMI belum tersedia.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
