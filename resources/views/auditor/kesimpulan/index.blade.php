@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/app/18-auditor-kesimpulan.css') }}"
    >
@endpush

@section('content')

<div class="audit-list-page">

    {{-- BREADCRUMB --}}

    <div class="audit-list-breadcrumb">

        <a href="{{ url('/auditor') }}">
            Dashboard
        </a>

        <span>/</span>

        <a href="{{ route('auditor.temuan.index') }}">
            Audit Mutu Internal
        </a>

        <span>/</span>

        <strong>
            Kesimpulan Audit
        </strong>

    </div>

    {{-- CARD DAFTAR --}}

    <section class="audit-list-card">

        <div class="audit-list-header">

            <div>

                <h2>
                    Daftar Kesimpulan Audit
                </h2>

                <p>
                    Data Kesimpulan Audit Mutu Internal
                </p>

            </div>

            <a
                href="{{ route('auditor.kesimpulan.create') }}"
                class="audit-list-add-button"
            >

                <i class="bi bi-plus-lg"></i>

                Tambah Kesimpulan

            </a>

        </div>

        {{-- PESAN SUKSES --}}

        @if(session('success'))

            <div class="audit-list-alert-success">

                <i class="bi bi-check-circle-fill"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif

        {{-- TABEL --}}

        <div class="audit-list-table-wrapper">

            <table class="audit-list-table">

                <thead>

                    <tr>

                        <th class="column-number">
                            No
                        </th>

                        <th>
                            Periode AMI
                        </th>

                        <th>
                            Kesimpulan Audit
                        </th>

                        <th>
                            Dibuat Oleh
                        </th>

                        <th class="column-action">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $item)

                        <tr>

                            <td class="column-number">
                                {{ $loop->iteration }}
                            </td>

                            <td>

                                <strong>
                                    {{ $item->periodeAmi?->tahun ?? '-' }}
                                </strong>

                            </td>

                            <td>

                                <div
                                    class="audit-list-text"
                                    title="{{ $item->kesimpulan }}"
                                >
                                    {{
                                        \Illuminate\Support\Str::limit(
                                            $item->kesimpulan ?? '-',
                                            100
                                        )
                                    }}
                                </div>

                            </td>

                            <td>

                                {{ $item->user?->nama ?? '-' }}

                            </td>

                            <td>

                                <div class="audit-list-actions">

                                    {{-- LIHAT --}}

                                    <a
                                        href="{{
                                            route(
                                                'auditor.kesimpulan.show',
                                                $item->id
                                            )
                                        }}"
                                        class="
                                            audit-list-action-button
                                            action-view
                                        "
                                        title="Lihat"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>

                                    {{-- EDIT --}}

                                    <a
                                        href="{{
                                            route(
                                                'auditor.kesimpulan.edit',
                                                $item->id
                                            )
                                        }}"
                                        class="
                                            audit-list-action-button
                                            action-edit
                                        "
                                        title="Edit"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    {{-- HAPUS --}}

                                    <form
                                        action="{{
                                            route(
                                                'auditor.kesimpulan.destroy',
                                                $item->id
                                            )
                                        }}"
                                        method="POST"
                                        onsubmit="
                                            return confirm(
                                                'Yakin ingin menghapus kesimpulan ini?'
                                            );
                                        "
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="
                                                audit-list-action-button
                                                action-delete
                                            "
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

                            <td
                                colspan="5"
                                class="audit-list-empty"
                            >

                                <i class="bi bi-file-earmark-x"></i>

                                <strong>
                                    Belum ada Kesimpulan Audit
                                </strong>

                                <span>
                                    Klik tombol Tambah Kesimpulan
                                    untuk membuat data baru.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection