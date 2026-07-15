@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Temuan Audit

</h3>

<!-- ===========================================================
    CARD
=========================================================== -->

<div class="card">

    <!-- HEADER -->

    <div class="temuan-header">

        <div>

            <h4>

                Daftar Temuan Audit

            </h4>

            <small>

                Data Temuan Audit Mutu Internal

            </small>

        </div>

        <a
            href="{{ route('auditor.temuan.create') }}"
            class="btn-add">

            <i class="bi bi-plus-lg"></i>

            Tambah Temuan

        </a>

    </div>

    <!-- ======================================================
        TAB MENU
    ======================================================= -->

    <div class="temuan-tab">

        <a
            href="{{ route('auditor.temuan.index') }}"
            class="active">

            Temuan Audit

        </a>

        <a href="{{ route('auditor.tanggapan.index') }}"
            class="{{ request()->routeIs('auditor.tanggapan.*') ? 'active' : '' }}">
            Tanggapan Auditee
        </a>


        <a href="{{ route('auditor.akarmasalah.index') }}"
             class="{{ request()->routeIs('auditor.akarmasalah.*') ? 'active' : '' }}">

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

    <!-- ======================================================
        TABLE
    ======================================================= -->

    <table>

        <thead>

            <tr>

                <th width="70">

                    No

                </th>

                <th>

                    Pertanyaan Audit

                </th>

                <th>

                    Temuan

                </th>

                <th width="120">

                    Status

                </th>

                <th width="180">

                    Aksi

                </th>

            </tr>

        </thead>

        <tbody>

                @forelse($data as $item)

        <tr>

            <!-- NOMOR -->

            <td>

                {{ $loop->iteration }}

            </td>

            <!-- PERTANYAAN -->

            <td>

                {{ $item->pertanyaan->pertanyaan ?? '-' }}

            </td>

            <!-- TEMUAN -->

            <td>

                {{ $item->temuan }}

            </td>

            <!-- STATUS -->

            <td>

                @if($item->status_temuan == 'open')

                    <span class="badge-open">

                        Open

                    </span>

                @elseif($item->status_temuan == 'closed')

                    <span class="badge-close">

                        Closed

                    </span>

                @else

                    <span class="badge-draft">

                        {{ ucfirst($item->status_temuan) }}

                    </span>

                @endif

            </td>

            <!-- AKSI -->

            <td>

                <div class="action-buttons">

                    <!-- DETAIL -->

                    <a
                        href="{{ route('auditor.temuan.show',$item->id) }}"
                        class="btn-icon btn-detail"
                        title="Detail">

                        <i class="bi bi-eye"></i>

                    </a>

                    <!-- EDIT -->

                    <a
                        href="{{ route('auditor.temuan.edit',$item->id) }}"
                        class="btn-icon btn-edit"
                        title="Edit">

                        <i class="bi bi-pencil"></i>

                    </a>

                    <!-- HAPUS -->

                    <form
                        action="{{ route('auditor.temuan.destroy',$item->id) }}"
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

            <td
                colspan="5"
                class="table-empty">

                Belum ada data Temuan Audit.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

        <!-- ======================================================
        FOOTER
    ======================================================= -->

    @if(session('success'))

        <div class="alert-success">

            {{ session('success') }}

        </div>

    @endif

</div>

@endsection