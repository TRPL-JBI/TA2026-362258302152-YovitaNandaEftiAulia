@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Pertanyaan AMI
</h3>

<div class="tab-menu">

    <a href="{{ route('periode-ami.show',$periode->id) }}">
        Detail Periode AMI
    </a>

    <a href="{{ route('penerapan.index',$periode->id) }}">
        Penerapan Standar
    </a>

    <a href="{{ route('pertanyaan.index',$periode->id) }}"
       class="active">
        Pertanyaan AMI
    </a>

    <a href="{{ route('tim-ami.index',$periode->id) }}">
        Tim AMI
    </a>

    <a href="{{ route('jadwal.index',$periode->id) }}">
        Jadwal AMI
    </a>

</div>

<div class="card">

    <div class="card-header periode-header">

        <h2 class="card-title">

            Data Pertanyaan AMI

        </h2>

        <a href="{{ route('pertanyaan.create', $periode->id) }}"
           class="btn-add">

            <i class="bi bi-plus-lg"></i>

            Buat Pertanyaan

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