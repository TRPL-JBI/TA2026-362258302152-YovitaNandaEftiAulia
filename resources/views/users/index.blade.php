@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">Dashboard / Manajemen Pengguna</h3>

<div class="card">

    <div class="card-header">
        <h4>Periode AMI <span style="background:#eee;padding:4px 10px;border-radius:5px;">2024/2025</span></h4>

        <a href="{{ route('user.create') }}" class="btn-add">
            + Tambah
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Unit Kerja</th>
                <th>Role</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->unit->nama ?? '-' }}</td>
                <td>Admin</td>
                <td>{{ $item->status }}</td>
                <td>
                    <a href="{{ route('user.edit', $item->id) }}" class="btn-icon">
                        <i class="bi bi-pencil"></i>
                    </a>
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="7">Data belum ada</td>
            </tr>
            @endforelse
        </tbody>

    </table>

</div>

@endsection