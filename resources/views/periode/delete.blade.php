@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Hapus Periode AMI
</h3>

<div class="card">

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>
                <tr>
                    <th>Tahun AMI</th>
                    <th>Standar Mutu</th>
                    <th>Unit Kerja</th>
                    <th>Tanggal Audit</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>{{ $data->tahun }}</td>

                    <td>
                        {{ $data->standarMutu->nama_standar_mutu ?? '-' }}
                    </td>

                    <td>
                        {{ $data->unitKerja->nama ?? '-' }}
                    </td>

                    <td>
                        {{ $data->tanggal_buka_ami }}
                        -
                        {{ $data->tanggal_tutup_ami }}
                    </td>

                    <td>{{ $data->status }}</td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

<!-- MODAL OVERLAY -->
<div class="delete-overlay">

    <div class="delete-modal">

        <h3>
            Apakah Anda Yakin Ingin Menghapus Periode AMI?
        </h3>

        <div class="delete-action">

            <a href="{{ route('periode-ami.index') }}"
               class="btn-batal">
                Batal
            </a>

            <form action="{{ route('periode-ami.destroy',$data->id) }}"
                  method="POST">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="btn-hapus">
                    Hapus
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
