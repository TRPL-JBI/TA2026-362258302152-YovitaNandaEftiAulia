@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Rekomendasi

</h3>

<!-- ===========================================================
    CARD
=========================================================== -->

<div class="card">

    <!-- =======================================================
        HEADER
    ======================================================== -->

    <div class="temuan-header">

        <div>

            <h4>

                Daftar Rekomendasi Peningkatan

            </h4>

            <small>

                Data Rekomendasi Peningkatan Audit Mutu Internal

            </small>

        </div>

        <a href="{{ route('auditor.rekomendasi.create') }}"
           class="btn-add">

            <i class="bi bi-plus-lg"></i>

            Tambah Rekomendasi

        </a>

    </div>

   <!-- =======================================================
    TAB MENU
======================================================= -->

<div class="temuan-tab">

    <a
        href="{{ route('auditor.temuan.index') }}"
        class="{{ request()->routeIs('auditor.temuan.*') ? 'active' : '' }}">

        Temuan Audit

    </a>

    <a
        href="{{ route('auditor.tanggapan.index') }}"
        class="{{ request()->routeIs('auditor.tanggapan.*') ? 'active' : '' }}">

        Tanggapan Auditee

    </a>

    <a
        href="{{ route('auditor.akarmasalah.index') }}"
        class="{{ request()->routeIs('auditor.akarmasalah.*') ? 'active' : '' }}">

        Akar Masalah

    </a>

    <a
        href="{{ route('auditor.rekomendasi.index') }}"
        class="{{ request()->routeIs('auditor.rekomendasi.*') ? 'active' : '' }}">

        Rekomendasi

    </a>

    <a
        href="{{ route('auditor.kesimpulan.index') }}"
        class="{{ request()->routeIs('auditor.kesimpulan.*') ? 'active' : '' }}">

        Kesimpulan

    </a>

    <a
        href="{{ route('auditor.lampiran.index') }}"
        class="{{ request()->routeIs('auditor.lampiran.*') ? 'active' : '' }}">

        Lampiran

    </a>

</div>

    <!-- =======================================================
        TABLE
    ======================================================== -->

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

                    Aspek

                </th>

                <th>

                    Kelebihan

                </th>

                <th>

                    Rekomendasi

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

                    {{ $item->penerapan->standarmutuPeriode->standarMutu->nama_standar_mutu ?? '-' }}

                </td>

                <td>

                    {{ $item->aspek }}

                </td>

                <td>

                    {{ $item->kelebihan }}

                </td>

                <td>

                    {{ $item->rekomendasi }}

                </td>

                <td>

                    {{ $item->user->nama ?? '-' }}

                </td>

                <td>

                    <div class="action-buttons">

                        <!-- DETAIL -->

                        <a href="{{ route('auditor.rekomendasi.show',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        <!-- EDIT -->

                        <a href="{{ route('auditor.rekomendasi.edit',$item->id) }}"
                           class="btn-icon btn-edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                        <!-- DELETE -->

                        <form
                            action="{{ route('auditor.rekomendasi.destroy',$item->id) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf

                            @method('DELETE')

                            <button
                                type="submit"
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

                    Belum ada Data Rekomendasi.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

    <!-- =======================================================
        FOOTER
    ======================================================== -->

    @if(session('success'))

        <div class="alert-success">

            {{ session('success') }}

        </div>

    @endif

</div>

@endsection