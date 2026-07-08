@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Pertanyaan AMI
</h3>

<div class="card">

    <div class="card-header periode-header">

        <a href="{{ route('pertanyaan.create', $periode->id) }}"
        class="btn-add">

            <i class="bi bi-plus-lg"></i>

            Tambah Pertanyaan

        </a>

    </div>

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

            <tr>

                <th>No</th>
                <th>Pertanyaan</th>
                <th>Dibuat Oleh</th>
                <th>Aksi</th>

            </tr>

            </thead>

            <tbody>

            @foreach($data as $item)

            <tr>

                <td>
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $item->pertanyaan }}
                </td>

                <td>
                    {{ $item->user->nama }}
                </td>

                <td>

                    <div class="action-buttons">

                        <a href="{{ route('pertanyaan.edit',$item->id) }}"
                           class="btn-icon btn-edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                        <form action="{{ route('pertanyaan.destroy',$item->id) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button class="btn-icon btn-delete">

                                <i class="bi bi-trash"></i>

                            </button>

                        </form>

                    </div>

                </td>

            </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection