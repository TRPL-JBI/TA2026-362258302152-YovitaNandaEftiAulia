@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">Dashboard / Hapus Unit Kerja</h3>

<div class="card">

    <!-- TABLE MINI -->
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Unit Kerja</th>
                <th>Kategori</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $data->nama }}</td>
                <td>{{ $data->kategori_unit_kerja }}</td>
            </tr>
        </tbody>
    </table>

    <!-- BOX KONFIRMASI -->
    <div class="delete-box">
        <h4>Apakah Anda Yakin Ingin Menghapus Unit Kerja?</h4>

        <div class="delete-action">
            <a href="{{ route('unit-kerja.index') }}" class="btn-cancel">
                Batal
            </a>

            <form action="{{ route('unit-kerja.destroy', $data->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <button class="btn-delete-confirm">
                    Hapus
                </button>
            </form>
        </div>
    </div>

</div>

@endsection
