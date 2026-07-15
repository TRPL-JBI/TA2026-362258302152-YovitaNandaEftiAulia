@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">

    Dashboard / Penerapan Standar

</div>

<div class="card">

    <div class="card-header periode-header">

        <h2 class="card-title">

            Data Penerapan Standar

        </h2>

        <a href="{{ route('auditee.penerapan.create',$periodeId) }}"
           class="btn-add">

            <i class="bi bi-plus-lg"></i>

            Buat Penerapan

        </a>

    </div>

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>

                    <th width="70">

                        No

                    </th>

                    <th>

                        Standar Mutu

                    </th>

                    <th>

                        Deskripsi Hasil

                    </th>

                    <th width="170">

                        Link Bukti

                    </th>

                    <th width="170">

                        Dibuat Oleh

                    </th>

                    <th width="170">

                        Aksi

                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($data as $item)

                <tr>

                    <td>

                        {{ $loop->iteration }}

                    </td>

                    <td>

                        {{ $item->standarmutuPeriode->standarMutu->nama_standar_mutu }}

                    </td>

                    <td>

                        {{ \Illuminate\Support\Str::limit($item->deskripsi_hasil,80) }}

                    </td>

                    <td>

                        @if($item->link_bukti)

                            <a href="{{ $item->link_bukti }}"
                               target="_blank">

                                Lihat

                            </a>

                        @else

                            -

                        @endif

                    </td>

                    <td>

                        {{ $item->user->nama }}

                    </td>

                    <td>

                        <div class="action-buttons">

                            <a href="{{ route('auditee.penerapan.show',$item->id) }}"
                               class="btn-icon btn-detail">

                                <i class="bi bi-eye"></i>

                            </a>

                            <a href="{{ route('auditee.penerapan.edit',$item->id) }}"
                               class="btn-icon btn-edit">

                                <i class="bi bi-pencil"></i>

                            </a>

                            <form
                                action="{{ route('auditee.penerapan.destroy',$item->id) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
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

                    <td colspan="6">

                        Belum ada data penerapan standar.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection