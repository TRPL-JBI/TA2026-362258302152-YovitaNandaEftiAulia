@extends('layouts.auditor')

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

                <b>{{ $isi->nama_standar }}</b>

            </small>

        </div>

        <a href="{{ route('auditor.isi.index',$isi->id_standar_mutu) }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

    <table>

        <thead>

            <tr>

                <th width="70">No.</th>

                <th>Deskripsi Indikator</th>

                <th width="120">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($data as $item)

            <tr>

                <td>

                    {{ $loop->iteration }}

                </td>

                <td>

                    {{ $item->deskripsi }}

                </td>

                <td>

                    <div class="action-buttons">

                        <a href="{{ route('auditor.indikator.show',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="3"
                    style="text-align:center;padding:25px;">

                    Belum ada Data Indikator

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection
