@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <h3 class="breadcrumb">Dashboard / Hapus Standar Mutu</h3>

    <div class="card">

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Standar Mutu</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $data->nama_standar_mutu }}</td>
                </tr>
            </tbody>
        </table>

        <!-- KONFIRMASI -->
        <div class="delete-box">
            <h4>Apakah Anda Yakin Ingin Menghapus Standar Mutu?</h4>

            <div class="delete-action">
                <a href="{{ route('standarmutu.index') }}" class="btn-cancel">
                    Batal
                </a>

                <form action="{{ route('standarmutu.destroy', $data->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn-delete-confirm">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

    </div>

</div>

@endsection
