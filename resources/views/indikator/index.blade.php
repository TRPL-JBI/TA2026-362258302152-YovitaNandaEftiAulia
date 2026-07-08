@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">

    Dashboard /
    Standar Mutu /
    Isi Standar /
    Indikator

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

        <div style="display:flex;gap:10px;">

            @if($isiStandar->parent_standar_id)

                <a href="{{ route('isi.show',$isiStandar->parent_standar_id) }}"
                   class="btn-secondary">

                    ← Kembali

                </a>

            @else

                <a href="{{ route('isi.index',$isiStandar->id_standar_mutu) }}"
                   class="btn-secondary">

                    ← Kembali

                </a>

            @endif

            <a href="{{ route('indikator.create',$isiStandar->id) }}"
               class="btn-add">

                + Tambah Indikator

            </a>

        </div>

    </div>

    <table>

        <thead>

            <tr>

                <th width="70">No</th>

                <th>Deskripsi Indikator</th>

                <th width="220">Aksi</th>

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

                        {{-- Detail --}}
                        <a href="{{ route('indikator.show',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('indikator.edit',$item->id) }}"
                           class="btn-icon btn-edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('indikator.destroy',$item->id) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn-icon btn-delete"
                                onclick="return confirm('Yakin ingin menghapus indikator ini?')">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="3"
                    style="text-align:center;padding:30px;">

                    Belum ada indikator.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection