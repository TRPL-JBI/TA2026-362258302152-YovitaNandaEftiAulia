@extends('layouts.auditor')

@section('content')

<div class="breadcrumb">
    Dashboard / Audit Mutu Internal / Temuan Audit
</div>

<div class="card">

    <div class="card-header periode-header">

        <div class="header-left">

            <h2 class="card-title">
                Data Temuan Audit
            </h2>

            <p>
                Temuan auditor berdasarkan hasil penerapan standar
                yang dikirim oleh auditee.
            </p>

        </div>

        <a
            href="{{ route('auditor.temuan.create') }}"
            class="btn-add"
        >
            <i class="bi bi-plus-circle"></i>
            Tambah Temuan
        </a>

    </div>

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>
                    <th width="65">No.</th>
                    <th>Indikator</th>
                    <th>Hasil Penerapan Auditee</th>
                    <th>Temuan Auditor</th>
                    <th width="125">Status</th>
                    <th width="180">Aksi</th>
                </tr>

            </thead>

            <tbody>

                @forelse($data as $item)

                    @php
                        $status = strtolower(
                            trim($item->status_temuan ?? '')
                        );
                    @endphp

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{
                                $item->penerapan
                                    ->indikator
                                    ->deskripsi
                                ?? $item->penerapan
                                    ->indikator
                                    ->indikator
                                ?? '-'
                            }}
                        </td>

                        <td>
                            {{
                                $item->penerapan
                                    ->deskripsi_hasil
                                ?? '-'
                            }}
                        </td>

                        <td>
                            {{ $item->temuan }}
                        </td>

                        <td>

                            @if($status === 'open')

                                <span class="badge-open">
                                    Open
                                </span>

                            @elseif($status === 'closed')

                                <span class="badge-close">
                                    Closed
                                </span>

                            @else

                                <span class="badge-draft">
                                    {{ ucfirst($status) }}
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="action-buttons">

                                <a
                                    href="{{ route(
                                        'auditor.temuan.show',
                                        $item->id
                                    ) }}"
                                    class="btn-icon btn-detail"
                                    title="Detail"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a
                                    href="{{ route(
                                        'auditor.temuan.edit',
                                        $item->id
                                    ) }}"
                                    class="btn-icon btn-edit"
                                    title="Edit"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form
                                    action="{{ route(
                                        'auditor.temuan.destroy',
                                        $item->id
                                    ) }}"
                                    method="POST"
                                    onsubmit="return confirm(
                                        'Hapus temuan audit ini?'
                                    )"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn-icon btn-delete"
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
                            colspan="6"
                            style="text-align:center;"
                        >
                            Belum ada data temuan audit.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
