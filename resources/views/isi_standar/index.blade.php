@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Isi Standar
</h3>

<div class="card">

    <div class="card-header"
         style="display:flex;
                justify-content:space-between;
                align-items:center;">

        <div>

            <h4>Data Isi Standar</h4>

            <small>
                Standar Mutu :
                <b>{{ $standarMutu->nama_standar_mutu }}</b>
            </small>

        </div>

        <a href="{{ route('isi.create',$standarMutu->id) }}"
           class="btn-add">

            + Tambah Isi Standar

        </a>

    </div>

    <table>

        <thead>

        <tr>

            <th>No</th>

            <th>Nama Isi Standar</th>

            <th width="250">
                Aksi
            </th>

        </tr>

        </thead>

        <tbody>

        @forelse($isiStandar as $item)

        <tr>

            <td>

                {{ $loop->iteration }}

            </td>

 <td>
    {{ $item->nama_standar }}
</td>

<td>

    <div class="action-buttons">

        <!-- Detail -->
        <a href="{{ route('isi.show', $item->id) }}"
           class="btn-icon btn-detail">

            <i class="bi bi-eye"></i>

        </a>

        <!-- Indikator -->
        <a href="{{ route('indikator.index', $item->id) }}"
           class="btn-icon"
           style="background:#DBEAFE;color:#2563EB;">

            <i class="bi bi-card-checklist"></i>

        </a>

        <!-- Edit -->
        <a href="{{ route('isi.edit', $item->id) }}"
           class="btn-icon btn-edit">

            <i class="bi bi-pencil"></i>

        </a>

        <!-- Delete -->
        <form action="{{ route('isi.destroy', $item->id) }}"
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
                 style="text-align:center">

                 Data belum tersedia

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection