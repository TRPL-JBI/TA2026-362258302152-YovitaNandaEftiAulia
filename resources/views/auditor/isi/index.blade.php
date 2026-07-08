@extends('layouts.auditor')

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

                <b>{{ $standar->nama_standar_mutu }}</b>

            </small>

        </div>

        <div style="display:flex;align-items:center;gap:10px;">

            @if($parent)

                @if($parent->parent)

                    <a href="{{ route('auditor.isi.show',$parent->parent->id) }}"
                       class="btn-back">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>

                @else

                    <a href="{{ route('auditor.isi.index',$standar->id) }}"
                       class="btn-back">

                        <i class="bi bi-arrow-left"></i>

                        Kembali

                    </a>

                @endif

            @else

                <a href="{{ route('auditor.standarmutu.index') }}"
                   class="btn-back">

                    <i class="bi bi-arrow-left"></i>

                    Kembali

                </a>

            @endif

        </div>

    </div>

    <table>

        <thead>

            <tr>

                <th width="70">No</th>

                <th>Nama Isi Standar</th>

                <th width="180" style="text-align:center;">Aksi</th>

            </tr>

        </thead>

        <tbody>

        @forelse($data as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

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
                            margin-left:8px;">

                            {{ $item->children->count() }} Child

                        </span>

                    @endif

                </td>

                <td>

                    <div class="action-buttons">

                        {{-- Icon pertama --}}
                        @if($item->children->count())

                            <a href="{{ route('auditor.isi.show',$item->id) }}"
                               class="btn-icon"
                               style="background:#DCFCE7;color:#16A34A;"
                               title="Buka Sub Standar">

                                <i class="bi bi-card-checklist"></i>

                            </a>

                        @else

                            <a href="{{ route('auditor.indikator.index',$item->id) }}"
                               class="btn-icon"
                               style="background:#DCFCE7;color:#16A34A;"
                               title="Lihat Indikator">

                                <i class="bi bi-card-checklist"></i>

                            </a>

                        @endif

                        {{-- Detail --}}
                        <a href="{{ route('auditor.isi.detail',$item->id) }}"
                           class="btn-icon btn-detail"
                           title="Detail">

                            <i class="bi bi-eye"></i>

                        </a>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="3"
                    style="text-align:center;padding:30px;">

                    Belum ada Data Isi Standar

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection