@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Isi Standar / Indikator
</h3>

<div class="card">

    <div class="card-header"
         style="display:flex;
                justify-content:space-between;
                align-items:center;">

        <div>

            <h4>Data Indikator</h4>

            <small>

                Isi Standar :

                <b>{{ $isiStandar->nama_standar }}</b>

            </small>

        </div>

        <a href="{{ route('indikator.create',$isiStandar->id) }}"
           class="btn-add">

            + Tambah Indikator

        </a>

    </div>

    <table>

        <thead>

            <tr>

                <th>No</th>

                <th>Deskripsi Indikator</th>

                <th width="180">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody>

        @forelse($indikator as $item)

            <tr>

                <td>

                    {{ $loop->iteration }}

                </td>

                <td style="text-align:left">

                    {{ $item->deskripsi }}

                </td>

                <td>

       <div class="action-buttons">

    <!-- Detail -->
    <a href="{{ route('indikator.show', $item->id) }}"
       class="btn-icon btn-detail">

        <i class="bi bi-eye"></i>

    </a>

    <!-- Edit -->
    <a href="{{ route('indikator.edit', $item->id) }}"
       class="btn-icon btn-edit">

        <i class="bi bi-pencil"></i>

    </a>

                      <!-- Delete -->
  

                        <form action="{{ route('indikator.destroy',$item->id) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn-icon btn-delete"
                                onclick="return confirm('Yakin ingin menghapus?')">

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

                    Belum ada indikator

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection