@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
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

<div class="tab-menu">

    <a href="{{ route('periode-ami.show', $periode->id) }}"
       class="active">
        Detail Periode AMI
    </a>

    <a href="{{ route('penerapan.index', $periode->id) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('tim-ami.index', $periode->id) }}">
        Tim AMI
    </a>

    <a href="{{ route('jadwal.index', $periode->id) }}">
        Jadwal AMI
    </a>

</div>

<div class="card">

    <div class="card-header periode-header">

        <div class="header-left">
            <h4>Detail Periode AMI</h4>
        </div>

        <div class="header-right" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">

            @if (strtolower((string) $periode->status) === 'draft')
                <form
                    action="{{ route('periode-ami.start', $periode->id) }}"
                    method="POST"
                    style="display:inline;"
                    onsubmit="return confirm('Mulai Periode AMI ini? Pastikan Tim AMI dan Jadwal sudah lengkap.');"
                >
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn-add" style="border:none;cursor:pointer;">
                        <i class="bi bi-play-fill"></i>
                        Mulai AMI
                    </button>
                </form>
            @elseif (strtolower((string) $periode->status) === 'berjalan')
                <form
                    action="{{ route('periode-ami.close', $periode->id) }}"
                    method="POST"
                    style="display:inline;"
                    onsubmit="return confirm('Tutup Periode AMI ini? Setelah ditutup, data audit tidak dapat diubah.');"
                >
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn-add" style="border:none;cursor:pointer;background:#b42318;">
                        <i class="bi bi-lock-fill"></i>
                        Tutup AMI
                    </button>
                </form>
            @endif

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
                    <th>Ketua AMI</th>
                    <th>Tujuan Audit</th>
                    <th>Lingkup Audit</th>
                    <th>Waktu Audit</th>
                    <th>Tanggal Audit</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>

                    <td>{{ $periode->tahun }}</td>

                    <td>
                        {{ $periode->standarMutu->nama_standar_mutu ?? '-' }}
                    </td>

                    <td>
                        @if ($periode->unitKerjas->isNotEmpty())
                            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                                @foreach ($periode->unitKerjas as $unit)
                                    <span style="display:inline-block;padding:5px 10px;border-radius:20px;background:#eef2ff;color:#4338ca;font-size:12px;font-weight:600;">
                                        {{ $unit->nama ?? '-' }}
                                    </span>
                                @endforeach
                            </div>
                        @elseif ($periode->unitKerja)
                            {{ $periode->unitKerja->nama ?? '-' }}
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $periode->user->nama ?? '-' }}</td>
                    <td>{{ $periode->tujuan_audit ?? '-' }}</td>
                    <td>{{ $periode->lingkup_audit ?? '-' }}</td>
                    <td>{{ $periode->waktu_audit ?? '-' }}</td>

                    <td>
                        {{ $periode->tanggal_buka_ami ?? '-' }}
                        -
                        {{ $periode->tanggal_tutup_ami ?? '-' }}
                    </td>

                    <td>
                        @if (strtolower((string) $periode->status) === 'berjalan')
                            <span class="badge-berjalan">Berjalan</span>
                        @elseif (strtolower((string) $periode->status) === 'draft')
                            <span class="badge-draft">Draft</span>
                        @else
                            <span class="badge-ditutup">Ditutup</span>
                        @endif
                    </td>

                    <td>
                        <div class="action-buttons">

                            <a href="{{ route('periode-ami.show', $periode->id) }}"
                               class="btn-icon btn-detail"
                               title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if (strtolower((string) $periode->status) !== 'ditutup')
                                <a href="{{ route('periode-ami.edit', $periode->id) }}"
                                   class="btn-icon btn-edit"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif

                            @if (strtolower((string) $periode->status) === 'draft')
                                <a href="{{ route('periode-ami.delete', $periode->id) }}"
                                   class="btn-icon btn-delete"
                                   title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            @endif

                        </div>
                    </td>
                </tr>
            </tbody>

        </table>

    </div>

</div>

@endsection
