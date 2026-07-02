@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Audit Mutu Internal
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('auditor.temuan.index') }}"
       class="active">
        Temuan Audit
    </a>

    <a href="#">
        Tanggapan Auditee
    </a>

    <a href="#">
        Akar Masalah
    </a>

    <a href="#">
        Rekomendasi
    </a>

    <a href="#">
        Kesimpulan
    </a>

    <a href="#">
        Lampiran
    </a>

</div>

<div class="card">

    <!-- HEADER -->
    <div class="card-header periode-header">

        <div class="header-left">

            <h4>Data Temuan Audit</h4>

        </div>

        <div class="header-right">

            <a href="{{ route('auditor.temuan.create') }}"
               class="btn-add">

                <i class="bi bi-plus-lg"></i>
                Tambah Temuan Audit

            </a>

        </div>

    </div>

    <!-- TABLE -->

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>

                    <th width="70">No.</th>

                    <th>Periode AMI</th>

                    <th>Unit Kerja</th>

                    <th>Standar Mutu</th>

                    <th>Temuan</th>

                    <th>Status</th>

                    <th width="170">
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

                        {{ $item->pertanyaan->penerapanStandar->standarMutuPeriodeAmi->periodeAmi->tahun ?? '-' }}

                    </td>

                    <td>

                        {{ $item->pertanyaan->penerapanStandar->standarMutuPeriodeAmi->periodeAmi->unitKerja->nama ?? '-' }}

                    </td>

                    <td>

                        {{ $item->pertanyaan->penerapanStandar->standarMutuPeriodeAmi->standarMutu->nama_standar_mutu ?? '-' }}

                    </td>

                    <td>

                        {{ \Illuminate\Support\Str::limit($item->temuan,70) }}

                    </td>

                    <td>

                        @if($item->status_temuan=='open')

                            <span class="badge-draft">

                                Open

                            </span>

                        @else

                            <span class="badge-berjalan">

                                Closed

                            </span>

                        @endif

                    </td>

                    <td>

                        <div class="action-buttons">

                            <a href="{{ route('auditor.temuan.show',$item->id) }}"
                               class="btn-icon btn-detail">

                                <i class="bi bi-eye"></i>

                            </a>

                            <a href="{{ route('auditor.temuan.edit',$item->id) }}"
                               class="btn-icon btn-edit">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <form action="{{ route('auditor.temuan.destroy',$item->id) }}"
                                  method="POST"
                                  style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn-icon btn-delete"
                                        onclick="return confirm('Yakin ingin menghapus data?')">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="7"
                        style="text-align:center;padding:20px;">

                        Belum ada Data Temuan Audit

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection