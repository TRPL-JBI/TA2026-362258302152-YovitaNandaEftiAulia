@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu
</h3>

<div class="card">

    <!-- HEADER -->
    <div class="card-header"
         style="display:flex;
                justify-content:space-between;
                align-items:center;">

        <h4>
            Data Standar Mutu
        </h4>

        <!-- Tambah -->
        <a href="{{ route('standarmutu.create') }}"
           class="btn-add">

            + Tambah Standar

        </a>

    </div>

    <!-- TABLE -->
    <table>

        <thead>

            <tr>

                <th>No.</th>
                <th>Nama Standar Mutu</th>
                <th width="280">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($standar as $item)

            <tr>

                <!-- Nomor -->
                <td>

                    {{ $loop->iteration }}

                </td>

                <!-- Nama -->
                <td>

                    {{ $item->nama_standar_mutu }}

                </td>

                <!-- Aksi -->
                <td>

                    <div class="action-buttons">

                        <!-- DETAIL -->
                        <a href="{{ route('standarmutu.show',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        <!-- ISI STANDAR -->
                        <a href="{{ route('isi.index',$item->id) }}"
                           class="btn-icon"
                           style="background:#DBEAFE;color:#2563EB;">

                            <i class="bi bi-list-ul"></i>

                        </a>

                        <!-- EDIT -->
                        <a href="{{ route('standarmutu.edit',$item->id) }}"
                           class="btn-icon btn-edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                        <!-- DELETE -->
                        <form action="{{ route('standarmutu.destroy',$item->id) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn-icon btn-delete"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="3"
                    style="text-align:center;
                           padding:15px;">

                    Data Standar Mutu belum tersedia

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection