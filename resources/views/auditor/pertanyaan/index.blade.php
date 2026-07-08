@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">

    Dashboard /

    Periode AMI /

    Pertanyaan AMI

</h3>

<div class="card">

    <div class="card-header"
         style="display:flex;
                justify-content:space-between;
                align-items:center;">

        <div>

            <h4>

                Data Pertanyaan AMI

            </h4>

            <small>

                Periode :
                <b>{{ $periode->tahun }}</b>

            </small>

        </div>

        <a href="{{ route('auditor.pertanyaan.create',$periode->id) }}"
           class="btn-add">

            <i class="bi bi-plus-lg"></i>

            Tambah Pertanyaan

        </a>

    </div>

    <table>

        <thead>

            <tr>

                <th width="60">No</th>

                <th>Standar Mutu</th>

                <th>Indikator</th>

                <th>Pertanyaan</th>

                <th>Referensi</th>

                <th width="180">Aksi</th>

            </tr>

        </thead>

        <tbody>

        @forelse($data as $item)

            <tr>

                <td>

                    {{ $loop->iteration }}

                </td>

                <td>

                    {{ $item->penerapan->standarmutuPeriode->standarMutu->nama_standar_mutu ?? '-' }}

                </td>

                <td>

                    {{ $item->indikator }}

                </td>

                <td>

                    {{ $item->pertanyaan }}

                </td>

                <td>

                    {{ $item->referensi }}

                </td>

                <td>

                    <div class="action-buttons">

                        <a href="{{ route('auditor.pertanyaan.show',$item->id) }}"
                           class="btn-icon btn-detail">

                            <i class="bi bi-eye"></i>

                        </a>

                        <a href="{{ route('auditor.pertanyaan.edit',$item->id) }}"
                           class="btn-icon btn-edit">

                            <i class="bi bi-pencil"></i>

                        </a>

                        <form
                            action="{{ route('auditor.pertanyaan.destroy',$item->id) }}"
                            method="POST"
                            style="display:inline;">

                            @csrf

                            @method('DELETE')

                            <button
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

                <td colspan="6"
                    style="text-align:center;
                           padding:30px;">

                    Belum ada Pertanyaan AMI.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection