@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Standar Mutu
</h3>

<div class="card">

    <div class="card-header"
         style="display:flex;
                justify-content:space-between;
                align-items:center;">

        <h4>Data Standar Mutu</h4>

    </div>

    <table>

        <thead>

            <tr>

                <th width="70">No.</th>

                <th>Nama Standar Mutu</th>

                <th width="150">Aksi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($data as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $item->nama_standar_mutu }}</td>

                <td>

                    <div class="action-buttons">

                        <a href="{{ route('auditor.standarmutu.show',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        <a href="{{ route('auditor.isi.index',$item->id) }}"
                           class="btn-icon btn-list">

                            <i class="bi bi-list-ul"></i>

                        </a>

                    </div>

                </td>

            </tr>

            @empty

            <tr>

                <td colspan="3"
                    style="text-align:center;padding:20px;">

                    Belum ada Data Standar Mutu

                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection