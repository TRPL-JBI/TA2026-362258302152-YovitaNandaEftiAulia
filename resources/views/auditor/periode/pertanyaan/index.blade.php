@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Audit Mutu Internal
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('auditor.temuan.index') }}">
        Temuan Audit
    </a>

    <a href="{{ route('auditor.pertanyaan.index',$periode->id) }}"
       class="active">
        Pertanyaan AMI
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

            <h4>Data Pertanyaan AMI</h4>

            <small>

                Periode AMI :

                <b>{{ $periode->tahun }}</b>

            </small>

        </div>

        <div class="header-right">

            <a href="{{ route('auditor.pertanyaan.create',$periode->id) }}"
               class="btn-add">

                <i class="bi bi-plus-lg"></i>

                Tambah Pertanyaan

            </a>

        </div>

    </div>

    <!-- TABLE -->

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>

                    <th width="70">
                        No.
                    </th>

                    <th>
                        Standar Mutu
                    </th>

                    <th>
                        Pertanyaan
                    </th>

                    <th>
                        Dibuat Oleh
                    </th>

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

                        {{ $item->penerapanStandar->standarMutuPeriodeAmi->standarMutu->nama_standar_mutu }}

                    </td>

                    <td>

                        {{ \Illuminate\Support\Str::limit($item->pertanyaan,80) }}

                    </td>

                    <td>

                        {{ $item->user->nama ?? '-' }}

                    </td>

                    <td>

                        <div class="action-buttons">

                            <a href="{{ route('auditor.pertanyaan.show',$item->id) }}"
                               class="btn-icon btn-detail">

                                <i class="bi bi-eye"></i>

                            </a>

                            <a href="{{ route('auditor.pertanyaan.edit',$item->id) }}"
                               class="btn-icon btn-edit">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <form
                                action="{{ route('auditor.pertanyaan.destroy',$item->id) }}"
                                method="POST"
                                style="display:inline;">

                                @csrf
                                @method('DELETE')

                                <button
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

                    <td colspan="5"
                        style="text-align:center;padding:25px;">

                        Belum ada Pertanyaan AMI

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection