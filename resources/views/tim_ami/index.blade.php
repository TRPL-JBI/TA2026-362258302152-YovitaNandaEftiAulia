@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Detail Periode AMI
</h3>

<div class="tab-menu">

    <a href="{{ route('periode-ami.show',$periodeAmi->id) }}">
        Detail Periode AMI
    </a>

    <a href="{{ route('penerapan.index',$periodeAmi->id) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('tim-ami.index',$periodeAmi->id) }}"
       class="active">
        Tim AMI
    </a>

    <a href="{{ route('jadwal.index',$periodeAmi->id) }}">
        Jadwal AMI
    </a>

</div>

<div class="card">

    <div class="card-header periode-header">

    <div class="header-left">

        <h4>Data Tim AMI</h4>

        <small>
            Periode AMI :
            <b>{{ $periodeAmi->tahun }}</b>
        </small>

    </div>

    <div class="header-right">

        <a href="{{ route('tim-ami.create',$periodeAmi->id) }}"
           class="btn-add">

            <i class="bi bi-plus-lg"></i>

            Tambah Tim AMI

        </a>

    </div>

</div>

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>

                    <th>No.</th>

                    <th>Nama Auditor</th>

                    <th>Role</th>

                    <th width="170">
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
                        {{ $item->user->nama }}
                    </td>

                    <td>

                        @switch($item->role)

                            @case('ketua auditor')

                                Ketua Auditor

                                @break

                            @case('auditor')

                                Auditor

                                @break

                            @default

                                Auditee

                        @endswitch

                    </td>

                    <td>

                        <div class="action-buttons">

                            <a href="{{ route('tim-ami.show',$item->id) }}"
                               class="btn-icon btn-detail">

                                <i class="bi bi-eye"></i>

                            </a>

                            <a href="{{ route('tim-ami.edit',$item->id) }}"
                               class="btn-icon btn-edit">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <form
                                action="{{ route('tim-ami.destroy',$item->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn-icon btn-delete"
                                    onclick="return confirm('Yakin hapus data?')">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4"
                        style="text-align:center">

                        Belum ada Tim AMI

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
