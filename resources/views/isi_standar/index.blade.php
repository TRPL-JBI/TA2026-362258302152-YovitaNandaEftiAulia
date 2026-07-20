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
         style="display:flex;
                justify-content:space-between;
                align-items:center;">

        <div>

            <h4>

                {{ $parent ? $parent->nama_standar : 'Data Isi Standar' }}

            </h4>

            <small>

                Standar Mutu :

                <b>{{ $standarMutu->nama_standar_mutu }}</b>

            </small>

        </div>

        <div style="display:flex;align-items:center;gap:10px;">

            {{-- BUTTON KEMBALI --}}
            @if($parent)

                @if($parent->parent)

                    <a href="{{ route('isi.show',$parent->parent->id) }}"
                       class="btn-back">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>

                @else

                    <a href="{{ route('isi.index',$standarMutu->id) }}"
                       class="btn-back">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>

                @endif

            @else

                <a href="{{ route('standarmutu.index') }}"
                   class="btn-back">

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>

            @endif

            {{-- BUTTON TAMBAH --}}
            @if($parent)

                <a href="{{ route('isi.node.create',$parent->id) }}"
                   class="btn-add">

                    <i class="bi bi-plus-lg"></i>

                    Tambah Sub Standar

                </a>

            @else

                <a href="{{ route('isi.create',$standarMutu->id) }}"
                   class="btn-add">

                    <i class="bi bi-plus-lg"></i>

                    Tambah Isi Standar

                </a>

            @endif

        </div>

    </div>

    <table>

        <thead>

            <tr>

                <th width="60">No</th>

                <th>Nama Isi Standar</th>

                <th width="280">Aksi</th>

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

                            {{ $item->children->count() }}

                            Child

                        </span>

                    @endif

                </td>

                <td>

                    <div class="action-buttons">

                        {{-- Folder --}}
                        <a href="{{ route('isi.show',$item->id) }}"
                           class="btn-icon"
                           style="background:#DBEAFE;color:#2563EB;"
                           title="Buka Sub Standar">

                            <i class="bi bi-folder2-open"></i>

                        </a>

                        {{-- Detail --}}
                        <a href="{{ route('isi.detail',$item->id) }}"
                           class="btn-icon btn-detail"
                           title="Detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        {{-- Edit --}}
                        <a href="{{ route('isi.edit',$item->id) }}"
                           class="btn-icon btn-edit"
                           title="Edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                        {{-- Delete --}}
                        <form action="{{ route('isi.destroy',$item->id) }}"
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
                    style="text-align:center;padding:35px;">

                    <b>Belum ada Sub Standar</b>

                    <br>

                    Silakan klik tombol

                    <b>Tambah Sub Standar</b>

                    untuk membuat struktur berikutnya.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection
