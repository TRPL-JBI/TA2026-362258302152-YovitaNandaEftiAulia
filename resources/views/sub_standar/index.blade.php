@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">

    Dashboard / Standar Mutu / Isi Standar / Sub Standar

</h3>

<div class="card">

    <div class="card-header"
         style="display:flex;
                justify-content:space-between;
                align-items:center;">

        <div>

            <h4>Data Sub Standar</h4>

            <small>

                Isi Standar :

                <b>{{ $isiStandar->nama_standar }}</b>

            </small>

        </div>

        <a
            href="{{ route('substandar.create',$isiStandar->id) }}"
            class="btn-add">

            + Tambah Sub Standar

        </a>

    </div>

    <table>

        <thead>

            <tr>

                <th>No</th>

                <th>Nama Sub Standar</th>

                <th width="280">

                    Aksi

                </th>

            </tr>

        </thead>

        <tbody>

        @forelse($subStandar as $item)

        <tr>

            <td>

                {{ $loop->iteration }}

            </td>

            <td>

                {{ $item->nama_standar }}

            </td>

            <td>

                <div class="action-buttons">

                    <a
                        href="{{ route('substandar.show',$item->id) }}"
                        class="btn-icon btn-detail">

                        <i class="bi bi-eye"></i>

                    </a>

                    <a
                        href="{{ route('indikator.index',$item->id) }}"
                        class="btn-icon"
                        style="background:#DBEAFE;color:#2563EB;">

                        <i class="bi bi-card-checklist"></i>

                    </a>

                    <a
                        href="{{ route('substandar.edit',$item->id) }}"
                        class="btn-icon btn-edit">

                        <i class="bi bi-pencil"></i>

                    </a>

                    <form
                        action="{{ route('substandar.destroy',$item->id) }}"
                        method="POST">

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn-icon btn-delete"
                            onclick="return confirm('Yakin ingin menghapus data?')">

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

                Data Sub Standar belum tersedia.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection