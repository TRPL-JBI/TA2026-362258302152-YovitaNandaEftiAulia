@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Pertanyaan AMI

</h3>

<!-- ===========================================================
    TAB MENU
=========================================================== -->

<div class="temuan-tab">

    <a href="{{ route('auditor.temuan.index') }}">

        Temuan Audit

    </a>

    <a href="{{ route('auditor.pertanyaan.index',$periode->id) }}"
       class="active">

        Pertanyaan AMI

    </a>

    <a href="{{ route('auditor.tanggapan.index') }}">

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

<!-- ===========================================================
    CARD
=========================================================== -->

<div class="card">

    <!-- HEADER -->

    <div class="temuan-header">

        <div>

            <h4>

                Data Pertanyaan AMI

            </h4>

            <small>

                Periode AMI :

                <b>{{ $periode->tahun }}</b>

            </small>

        </div>

        <a href="{{ route('auditor.pertanyaan.create',$periode->id) }}"
           class="btn-add">

            <i class="bi bi-plus-lg"></i>

            Tambah Pertanyaan

        </a>

    </div>

    <!-- TABLE -->

    <table>

        <thead>

            <tr>

                <th width="70">

                    No

                </th>

                <th>

                    Standar Mutu

                </th>

                <th>

                    Indikator

                </th>

                <th>

                    Pertanyaan

                </th>

                <th>

                    Referensi

                </th>

                <th>

                    Dibuat Oleh

                </th>

                <th width="180">

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

                    {{ $item->penerapanStandar->standarMutuPeriodeAmi->standarMutu->nama_standar_mutu ?? '-' }}

                </td>

                <td>

                    {{ $item->indikator ?? '-' }}

                </td>

                <td>

                    {{ $item->pertanyaan }}

                </td>

                <td>

                    {{ $item->referensi ?? '-' }}

                </td>

                <td>

                    {{ $item->user->nama ?? '-' }}

                </td>

                <td>

                    <div class="action-buttons">

                        <!-- DETAIL -->

                        <a href="{{ route('auditor.pertanyaan.show',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        <!-- EDIT -->

                        <a href="{{ route('auditor.pertanyaan.edit',$item->id) }}"
                           class="btn-icon btn-edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                        <!-- DELETE -->

                        <form action="{{ route('auditor.pertanyaan.destroy',$item->id) }}"
                              method="POST"
                              style="display:inline;">

                            @csrf

                            @method('DELETE')

                            <button type="submit"
                                    class="btn-icon btn-delete"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7"
                    class="table-empty">

                    Belum ada data Pertanyaan AMI.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection