@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Akar Masalah

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

                Daftar Akar Masalah

            </h4>

            <small>

                Data Akar Masalah Audit Mutu Internal

            </small>

        </div>

        <a href="{{ route('auditor.akarmasalah.create') }}"
           class="btn-add">

            <i class="bi bi-plus-lg"></i>

            Tambah Akar Masalah

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

                    Temuan Audit

                </th>

                <th>

                    Akar Masalah

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

                    {{ $item->temuan->temuan ?? '-' }}

                </td>

                <td>

                    {{ $item->akar_masalah }}

                </td>

                <td>

                    {{ $item->user->nama ?? '-' }}

                </td>

                <td>

                    <div class="action-buttons">

                        <!-- DETAIL -->

                        <a href="{{ route('auditor.akarmasalah.show',$item->id) }}"
                           class="btn-icon btn-detail"
                           title="Detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        <!-- EDIT -->

                        <a href="{{ route('auditor.akarmasalah.edit',$item->id) }}"
                           class="btn-icon btn-edit"
                           title="Edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                        <!-- DELETE -->

                        <form
                            action="{{ route('auditor.akarmasalah.destroy',$item->id) }}"
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

                <td colspan="5"
                    class="table-empty">

                    Belum ada Data Akar Masalah.

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
