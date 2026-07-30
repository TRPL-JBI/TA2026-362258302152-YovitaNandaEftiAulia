@extends('layouts.app')

@section('content')

<div class="unit-page">

    <div class="unit-breadcrumb">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>

        <span>/</span>

        <strong>
            Daftar Unit Kerja
        </strong>
    </div>

    @if (session('success'))
        <div class="unit-alert unit-alert-success">
            <i class="bi bi-check-circle-fill"></i>

            <span>
                {{ session('success') }}
            </span>
        </div>
    @endif

    @if (session('error'))
        <div class="unit-alert unit-alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>

            <span>
                {{ session('error') }}
            </span>
        </div>
    @endif

    <div class="unit-card">

        <div class="unit-card-header">

            <div>
                <h1>
                    Daftar Unit Kerja
                </h1>

                <p>
                    Data Unit Kerja beserta user yang menjadi
                    penanggung jawab.
                </p>
            </div>

            <a
                href="{{ route('unit-kerja.create') }}"
                class="unit-btn-add"
            >
                <i class="bi bi-plus-circle"></i>

                Tambah Data
            </a>

        </div>

        <div class="unit-table-wrapper">

            <table class="unit-table">

                <thead>
                    <tr>
                        <th class="unit-col-no">
                            No.
                        </th>

                        <th>
                            Nama Unit Kerja
                        </th>

                        <th>
                            Kategori Unit Kerja
                        </th>

                        <th>
                            Nama User
                        </th>

                        <th class="unit-col-action">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($data as $item)

                        <tr>
                            <td class="unit-text-center">
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <strong class="unit-name">
                                    {{ $item->nama }}
                                </strong>
                            </td>

                            <td>
                                <span class="unit-category">
                                    {{ $item->kategori_unit_kerja }}
                                </span>
                            </td>

                            <td>
                                @if ($item->kepalaUnit)

                                    <div class="unit-user">
                                        <div class="unit-user-icon">
                                            <i class="bi bi-person"></i>
                                        </div>

                                        <div>
                                            <strong>
                                                {{ $item->kepalaUnit->nama }}
                                            </strong>

                                            <small>
                                                {{ $item->kepalaUnit->email }}
                                            </small>
                                        </div>
                                    </div>

                                @else

                                    <span class="unit-user-empty">
                                        Belum ditentukan
                                    </span>

                                @endif
                            </td>

                            <td>
                                <div class="unit-actions">

                                    <a
                                        href="{{ route('unit-kerja.show', $item->id) }}"
                                        class="unit-action-btn unit-action-show"
                                        title="Lihat"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a
                                        href="{{ route('unit-kerja.edit', $item->id) }}"
                                        class="unit-action-btn unit-action-edit"
                                        title="Edit penugasan"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <form
                                        action="{{ route('unit-kerja.destroy', $item->id) }}"
                                        method="POST"
                                        class="unit-delete-form"
                                        onsubmit="return confirm('Yakin ingin menghapus Unit Kerja ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="unit-action-btn unit-action-delete"
                                            title="Hapus"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">

                                <div class="unit-empty">
                                    <i class="bi bi-building"></i>

                                    <strong>
                                        Data Unit Kerja belum tersedia
                                    </strong>
                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection