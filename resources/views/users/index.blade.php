@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">

    Dashboard / Manajemen Pengguna

</h3>

<div class="card">

    <div class="card-header"
         style="display:flex;
                justify-content:space-between;
                align-items:center;">

        <h4>

            Periode AMI

            <span
                style="
                    background:#EEF2FF;
                    color:#1E293B;
                    padding:6px 12px;
                    border-radius:8px;
                    font-size:18px;
                    margin-left:10px;">

                2024/2025

            </span>

        </h4>

        <div style="display:flex;align-items:center;gap:10px;">

            <a href="{{ route('dashboard') }}"
               class="btn-back">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <a href="{{ route('user.create') }}"
               class="btn-add">

                <i class="bi bi-plus-lg"></i>

                Tambah

            </a>

        </div>

    </div>

    <table>

        <thead>

            <tr>

                <th width="70">

                    No.

                </th>

                <th>

                    Nama

                </th>

                <th>

                    Email

                </th>

                <th>

                    Unit Kerja

                </th>

                <th width="120">

                    Role

                </th>

                <th width="120">

                    Status

                </th>

                <th width="120"
                    style="text-align:center;">

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

                    {{ $item->nama }}

                </td>

                <td>

                    {{ $item->email }}

                </td>

                <td>

                    {{ $item->unit->nama ?? '-' }}

                </td>

                <td>

                    {{ ucfirst($item->role) }}

                </td>

                <td>

                    {{ ucfirst($item->status) }}

                </td>

                <td style="text-align:center;">

                    <div class="action-buttons">

                        <a href="{{ route('user.edit',$item->id) }}"
                           class="btn-icon btn-edit"
                           title="Edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7"
                    style="text-align:center;padding:35px;">

                    Data pengguna belum tersedia.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection