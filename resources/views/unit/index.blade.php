@extends('layouts.app')

@section('content')

<link
    rel="stylesheet"
    href="{{ asset('css/19-admin-unit-kerja.css') }}"
>

<div class="unit-page">

    <h3 class="unit-breadcrumb">
        Dashboard / Daftar Unit Kerja
    </h3>

    @if(session('success'))
        <div class="unit-alert unit-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="unit-alert unit-alert-danger">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="unit-card unit-list-card">

        <div class="unit-card-header">

            <h1>Data Unit Kerja</h1>

            <a
                href="{{ route('unit-kerja.create') }}"
                class="unit-primary-button"
            >
                <i class="bi bi-plus-lg"></i>
                Tambah Unit
            </a>

        </div>

        <div class="unit-table-wrapper">

            <table class="unit-table">

                <thead>
                    <tr>
                        <th class="unit-number-column">
                            No.
                        </th>

                        <th>
                            Nama Unit Kerja
                        </th>

                        <th>
                            Kategori
                        </th>

                        <th class="unit-action-column">
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
                                {{ $item->nama }}
                            </td>

                            <td>
                                {{ ucwords($item->kategori_unit_kerja) }}
                            </td>

                            <td>

                                <div class="unit-action-group">

                                    <a
                                        href="{{ route('unit-kerja.show', $item->id) }}"
                                        class="unit-action-button unit-action-view"
                                        title="Lihat detail"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a
                                        href="{{ route('unit-kerja.edit', $item->id) }}"
                                        class="unit-action-button unit-action-edit"
                                        title="Edit unit kerja"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form
                                        action="{{ route('unit-kerja.destroy', $item->id) }}"
                                        method="POST"
                                        class="unit-delete-form"
                                        onsubmit="return confirm('Yakin hapus?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="unit-action-button unit-action-delete"
                                            title="Hapus unit kerja"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4">

                                <div class="unit-empty-state">

                                    <i class="bi bi-building"></i>

                                    <p>
                                        Data Unit Kerja belum tersedia.
                                    </p>

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