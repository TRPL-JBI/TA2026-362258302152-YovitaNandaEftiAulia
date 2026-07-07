@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">

    Dashboard /

    Standar Mutu

    @foreach($breadcrumb as $item)

        / {{ $item->nama_standar }}

    @endforeach

</h3>

<div class="card">

    <div class="card-header"
        style="display:flex;justify-content:space-between;align-items:center;">

        <div>

            <h4>

                {{ $parent ? $parent->nama_standar : 'Data Isi Standar' }}

            </h4>

            <small>

                Standar Mutu :

                <b>{{ $standarMutu->nama_standar_mutu }}</b>

            </small>

        </div>

        @if($parent)

            <a
                href="{{ route('isi.node.create',$parent->id) }}"
                class="btn-add">

                + Tambah Isi Standar

            </a>

        @else

            <a
                href="{{ route('isi.create',$standarMutu->id) }}"
                class="btn-add">

                + Tambah Isi Standar

            </a>

        @endif

    </div>

    <table>

        <thead>

            <tr>

                <th width="60">No</th>

                <th>Nama Isi Standar</th>

                <th width="260">Aksi</th>

            </tr>

        </thead>

        <tbody>

        @forelse($data as $item)

            <tr>

                <td>

                    {{ $loop->iteration }}

                </td>

                <td>

                    {{ $item->nama_standar }}

                    @if($item->children->count())

                        <span
                            style="
                            background:#E0F2FE;
                            color:#2563EB;
                            padding:3px 8px;
                            border-radius:20px;
                            font-size:11px;
                            margin-left:10px;">

                            {{ $item->children->count() }} Child

                        </span>

                    @endif

                </td>

                <td>

                    <div class="action-buttons">

                        @if($item->children->count())

                            <a
                                href="{{ route('isi.show',$item->id) }}"
                                class="btn-icon"
                                style="background:#DBEAFE;color:#2563EB;">

                                <i class="bi bi-folder2-open"></i>

                            </a>

                        @else

                            <a
                                href="{{ route('indikator.index',$item->id) }}"
                                class="btn-icon"
                                style="background:#DBEAFE;color:#2563EB;">

                                <i class="bi bi-card-checklist"></i>

                            </a>

                        @endif

                        <a
                            href="{{ route('isi.detail',$item->id) }}"
                            class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        <a
                            href="{{ route('isi.edit',$item->id) }}"
                            class="btn-icon btn-edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                        <form
                            action="{{ route('isi.destroy',$item->id) }}"
                            method="POST">

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn-icon btn-delete"
                                onclick="return confirm('Yakin hapus data ini?')">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="3" style="text-align:center;">

                    Data belum tersedia

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection