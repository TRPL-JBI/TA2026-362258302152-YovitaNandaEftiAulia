@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">Dashboard / Standar Mutu</h3>

<div class="card">

    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h4>Data Standar Mutu</h4>

        <a href="{{ route('standarmutu.create') }}" class="btn-add">
            + Tambah Standar
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Standar Mutu</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_standar_mutu }}</td>
                <td>

                    <!-- MASUK KATEGORI -->
                    <a href="{{ route('isi.kategori', $item->id) }}" class="btn-icon">
                        <i class="bi bi-arrow-right-circle"></i>
                    </a>

                      <!-- DETAIL -->
                    <a href="{{ route('standarmutu.show', $item->id) }}" class="btn-icon btn-detail">
                        <i class="bi bi-eye"></i>
                    </a>

                    <!-- EDIT -->
                    <a href="{{ route('standarmutu.edit', $item->id) }}" class="btn-icon">
                        <i class="bi bi-pencil"></i>
                    </a>

                    <!-- DELETE -->
                    <form action="{{ route('standarmutu.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-icon" onclick="return confirm('Yakin hapus?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>

                </td>
            </tr>

            @empty
            <tr>
                <td colspan="3" style="text-align:center; padding:15px;">
                    Data belum tersedia
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection