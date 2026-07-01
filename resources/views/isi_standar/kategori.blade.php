@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">Kategori Standar</h3>

<div id="formTambah" style="display:none; margin-top:15px;">
    <form action="{{ route('isi.store') }}" method="POST">
        @csrf

        <input type="hidden" name="id_standar_mutu" value="{{ $standar_id }}">
        <input type="hidden" name="parent_standar_id">

        <input type="text" name="nama_standar" placeholder="Masukkan kategori" required>

        <button type="submit" class="btn-save">Simpan</button>
    </form>
</div>

<div class="card">

<div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h4>Data Kategori</h4>

    <button onclick="toggleForm()" class="btn-add">
        + Tambah Kategori
    </button>
</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_standar }}</td>
                <td>
                    <a href="{{ route('isi.jenis', $item->id) }}" class="btn-icon">
                        ➡
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">Data belum ada</td>
            </tr>
            @endforelse
        </tbody>

    </table>

</div>

@endsection

<script>
function toggleForm() {
    const form = document.getElementById("formTambah");
    form.style.display = form.style.display === "none" ? "block" : "none";
}
</script>