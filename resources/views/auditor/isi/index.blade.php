@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu / Isi Standar
</h3>

<div class="card">

    <div class="card-header"
         style="display:flex;justify-content:space-between;align-items:center;">

        <div>

            <h4>Data Isi Standar</h4>

            <small>
                Standar Mutu :
                <b>{{ $standar->nama_standar_mutu }}</b>
            </small>

        </div>

        <a href="{{ route('auditor.standarmutu.index') }}"
           class="btn-back">

            <i class="bi bi-arrow-left"></i>
            Kembali

        </a>

    </div>

    <table>

        <thead>

            <tr>

                <th width="70">No.</th>

                <th>Nama Isi Standar</th>

                <th width="120">Aksi</th>

            </tr>

        </thead>

        <tbody>

        @forelse($data as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $item->nama_standar }}</td>


               <td>

    <div class="action-buttons">

        <!-- Detail -->
        <a href="{{ route('auditor.isi.show',$item->id) }}"
           class="btn-icon btn-detail">

            <i class="bi bi-eye"></i>

        </a>

        <!-- Indikator -->
        <a href="{{ route('auditor.indikator.index',$item->id) }}"
           class="btn-icon btn-list">

            <i class="bi bi-list-check"></i>

        </a>

    </div>

</td>

            </tr>

        @empty

            <tr>

                <td colspan="4"
                    style="text-align:center;padding:25px;">

                    Belum ada Data Isi Standar

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection