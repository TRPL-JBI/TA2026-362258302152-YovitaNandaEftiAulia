@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Daftar Unit Kerja
</h3>

<div class="card">

    <!-- HEADER -->
    <div class="card-header"
         style="display:flex;
                justify-content:space-between;
                align-items:center;">

        <h4>
            Data Unit Kerja
        </h4>

        <!-- tambah -->
        <a href="{{ route('unit-kerja.create') }}"
           class="btn-add">

            + Tambah Unit

        </a>

    </div>

    <!-- TABLE -->
    <table>

        <thead>

            <tr>

                <th>No.</th>
                <th>Nama Unit Kerja</th>
                <th>Kategori</th>
                <th>Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($data as $item)

            <tr>

                <!-- nomor -->
                <td>
                    {{ $loop->iteration }}
                </td>

                <!-- nama -->
                <td>
                    {{ $item->nama }}
                </td>

                <!-- kategori -->
                <td>
                    {{ $item->kategori_unit_kerja }}
                </td>

                <!-- aksi -->
                <td>

                    <div class="action-buttons">

                        <!-- DETAIL -->
                        <a href="{{ route('unit-kerja.show', $item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        <!-- EDIT -->
                        <a href="{{ route('unit-kerja.edit', $item->id) }}"
                           class="btn-icon btn-edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                        <!-- DELETE -->
                        <form action="{{ route('unit-kerja.destroy', $item->id) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="btn-icon btn-delete"
                                    onclick="return confirm('Yakin hapus?')">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="4"
                    style="text-align:center;
                           padding:15px;">

                    Data belum tersedia

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection
