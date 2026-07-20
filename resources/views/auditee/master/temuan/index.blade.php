@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">
    Dashboard / Temuan Audit
</div>

<div class="card">

    <div class="card-header periode-header">

        <div>
            <h2 class="card-title">
                Data Temuan Audit
            </h2>

            <p>
                Daftar temuan auditor berdasarkan penerapan standar
                yang telah Anda isi.
            </p>
        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>
                <tr>
                    <th width="65">No.</th>
                    <th>Indikator Standar</th>
                    <th>Hasil Penerapan</th>
                    <th>Temuan Auditor</th>
                    <th width="120">Status</th>
                    <th width="150">Tanggapan</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($temuan as $item)

                    @php
                        $tanggapan = $item->tanggapan->first();

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
                                ?? '-'
                            }}
                        </td>

                        <td>
                            {!! nl2br(e(
                                $item->penerapan
                                    ->deskripsi_hasil
                                ?? '-'
                            )) !!}
                        </td>

                        <td>
                            {!! nl2br(e(
                                $item->temuan
                                ?? '-'
                            )) !!}
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

                                <span>
                                    {{ ucfirst($status ?: '-') }}
                                </span>

                            @endif

                        </td>

                        <td>

                            @if($tanggapan)

                                <span style="
                                    color: #067647;
                                    font-weight: 700;
                                ">
                                    Sudah Ditanggapi
                                </span>

                            @else

                                <span style="
                                    color: #b42318;
                                    font-weight: 700;
                                ">
                                    Belum Ditanggapi
                                </span>

                            @endif

                        </td>

                        <td>

                            <div class="action-buttons">

                                <a
                                    href="{{ route(
                                        'auditee.temuan.show',
                                        $item->id
                                    ) }}"
                                    class="btn-icon btn-detail"
                                    title="Lihat detail"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if(!$tanggapan)

                                    <a
                                        href="{{ route(
                                            'auditee.tanggapan.create',
                                            $item->id
                                        ) }}"
                                        class="btn-icon btn-add"
                                        title="Beri tanggapan"
                                    >
                                        <i class="bi bi-chat-dots"></i>
                                    </a>

                                @else

                                    <a
                                        href="{{ route(
                                            'auditee.tanggapan.edit',
                                            $tanggapan->id
                                        ) }}"
                                        class="btn-icon btn-edit"
                                        title="Edit tanggapan"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form
                                        action="{{ route(
                                            'auditee.tanggapan.destroy',
                                            $tanggapan->id
                                        ) }}"
                                        method="POST"
                                        onsubmit="
                                            return confirm(
                                                'Apakah Anda yakin ingin menghapus tanggapan ini?'
                                            );
                                        "
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn-icon btn-delete"
                                            title="Hapus tanggapan"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </form>

                                @endif

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="7"
                            style="text-align: center;"
                        >
                            Belum ada temuan audit.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection