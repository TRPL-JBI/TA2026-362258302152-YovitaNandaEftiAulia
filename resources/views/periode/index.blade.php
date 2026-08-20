@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Periode AMI
</h3>

@if (session('success'))
    <div style="margin-bottom:16px;padding:12px 16px;border-radius:8px;background:#ecfdf3;color:#027a48;font-weight:600;">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div style="margin-bottom:16px;padding:12px 16px;border-radius:8px;background:#fef3f2;color:#b42318;">
        <strong>Proses tidak dapat dilanjutkan:</strong>
        <ul style="margin:8px 0 0 18px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">

    <div class="card-header periode-header">

        <div class="header-left">
            <h4>Data Periode AMI</h4>
        </div>

        <div class="header-right">
            <a href="{{ route('periode-ami.create') }}"
               class="btn-add">
                <i class="bi bi-plus-lg"></i>
                Buat Periode AMI
            </a>
        </div>

    </div>

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tahun AMI</th>
                    <th>Standar Mutu</th>
                    <th>Unit Kerja</th>
                    <th>Tanggal Audit</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->tahun }}</td>

                        <td>
                            {{ $item->standarMutu->nama_standar_mutu ?? '-' }}
                        </td>

                        <td>
                            @if ($item->unitKerjas->isNotEmpty())
                                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                                    @foreach ($item->unitKerjas as $unit)
                                        <span style="display:inline-block;padding:5px 10px;border-radius:20px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600;">
                                            {{ $unit->nama ?? '-' }}
                                        </span>
                                    @endforeach
                                </div>
                            @elseif ($item->unitKerja)
                                {{ $item->unitKerja->nama ?? '-' }}
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            {{ $item->tanggal_buka_ami ?? '-' }}
                            -
                            {{ $item->tanggal_tutup_ami ?? '-' }}
                        </td>

                        <td>
                            @if (strtolower((string) $item->status) === 'berjalan')
                                <span class="badge-berjalan">Berjalan</span>
                            @elseif (strtolower((string) $item->status) === 'draft')
                                <span class="badge-draft">Draft</span>
                            @else
                                <span class="badge-ditutup">Ditutup</span>
                            @endif
                        </td>

                        <td>
                            <div class="action-buttons">

                                <a href="{{ route('periode-ami.show', $item->id) }}"
                                   class="btn-icon btn-detail"
                                   title="Detail Periode">
                                    <i class="bi bi-eye"></i>
                                </a>

                                @if (strtolower((string) $item->status) !== 'ditutup')
                                    <a href="{{ route('periode-ami.edit', $item->id) }}"
                                       class="btn-icon btn-edit"
                                       title="Edit Periode">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif

                                @if (strtolower((string) $item->status) === 'draft')
                                    <form
                                        action="{{ route('periode-ami.start', $item->id) }}"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Mulai Periode AMI ini? Pastikan Tim AMI dan Jadwal sudah lengkap.');"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn-icon btn-detail"
                                            title="Mulai AMI"
                                            style="border:none;cursor:pointer;"
                                        >
                                            <i class="bi bi-play-fill"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('periode-ami.delete', $item->id) }}"
                                       class="btn-icon btn-delete"
                                       title="Hapus Periode">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                @elseif (strtolower((string) $item->status) === 'berjalan')
                                    <form
                                        action="{{ route('periode-ami.close', $item->id) }}"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('Tutup Periode AMI ini? Setelah ditutup, data audit tidak dapat diubah.');"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn-icon btn-delete"
                                            title="Tutup AMI"
                                            style="border:none;cursor:pointer;"
                                        >
                                            <i class="bi bi-lock-fill"></i>
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:25px;">
                            Data Periode AMI belum tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

@endsection
