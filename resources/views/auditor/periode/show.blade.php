@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('auditor.periode.show',$periode->id) }}"
       class="active">

        Detail Periode AMI

    </a>

    <a href="{{ route('auditor.penerapan.index',$periode->id) }}">

        Penerapan Standar

    </a>

    <a href="{{ route('auditor.pertanyaan.index',$periode->id) }}">

        Pertanyaan AMI

    </a>

    <a href="{{ route('auditor.tim.index',$periode->id) }}">

        Tim AMI

    </a>

    <a href="{{ route('auditor.jadwal.index',$periode->id) }}">

        Jadwal AMI

    </a>


</div>

<div class="card">

    <!-- HEADER -->
    <div class="card-header periode-header">

        <div class="header-left">

            <h4>Detail Periode AMI</h4>

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
                    <th width="80">Aksi</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>1</td>

                    <td>

                        {{ $periode->tahun }}

                    </td>

                    <td>

                        {{ $periode->standarMutu->nama_standar_mutu ?? '-' }}

                    </td>

                    <td>

                        {{ $periode->unitKerja->nama ?? '-' }}

                    </td>

                    <td>

                        {{ $periode->user->nama ?? '-' }}

                    </td>

                    <td>

                        {{ $periode->tujuan_audit }}

                    </td>

                    <td>

                        {{ $periode->lingkup_audit }}

                    </td>

                    <td>

                        {{ $periode->tanggal_buka_ami }}

                        -

                        {{ $periode->tanggal_tutup_ami }}

                    </td>

                    <td>

                        @if($periode->status=='berjalan')

                            <span class="badge-berjalan">

                                Berjalan

                            </span>

                        @elseif($periode->status=='draft')

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

                            <a href="{{ route('auditor.periode.show',$periode->id) }}"
                               class="btn-icon btn-detail">

                                <i class="bi bi-eye"></i>

                            </a>

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>

@endsection