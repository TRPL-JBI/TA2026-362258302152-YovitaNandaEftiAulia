@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">

    Dashboard / Standar Mutu / Isi Standar / Detail

    @foreach($breadcrumb as $item)

        / {{ $item->nama_standar }}

    @endforeach

</h3>

<div class="detail-card">

    <div class="detail-header">

        <div>

            <h2>Detail Isi Standar</h2>

            <p class="detail-subtitle">

                Informasi lengkap mengenai isi standar.

            </p>

        </div>

        @if($isi->parent)

            <a
                href="{{ route('isi.show',$isi->parent->id) }}"
                class="btn-back">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

        @else

            <a
                href="{{ route('isi.index',$isi->id_standar_mutu) }}"
                class="btn-back">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

        @endif

    </div>

    <div class="detail-body">

        <div class="detail-item">

            <label>Standar Mutu</label>

            <div class="detail-value">

                <i class="bi bi-journal-bookmark"></i>

                {{ $isi->standarMutu->nama_standar_mutu }}

            </div>

        </div>

        <div class="detail-item">

            <label>Nama Isi Standar</label>

            <div class="detail-value">

                <i class="bi bi-file-earmark-text"></i>

                {{ $isi->nama_standar }}

            </div>

        </div>

        <div class="detail-item">

            <label>Parent</label>

            <div class="detail-value">

                <i class="bi bi-diagram-3"></i>

                {{ $isi->parent?->nama_standar ?? '-' }}

            </div>

        </div>

        <div class="detail-item">

            <label>Jumlah Child</label>

            <div class="detail-value">

                <i class="bi bi-folder2-open"></i>

                {{ $isi->children->count() }}

            </div>

        </div>

        <div class="detail-item">

            <label>Jumlah Indikator</label>

            <div class="detail-value">

                <i class="bi bi-list-check"></i>

                {{ $isi->indikator->count() }}

            </div>

        </div>

        <div class="detail-item">

            <label>Status</label>

            <div class="detail-value">

                @if($isi->children->count())

                    <span class="badge bg-primary">

                        Memiliki Child

                    </span>

                @else

                    <span class="badge bg-success">

                        Leaf (Indikator)

                    </span>

                @endif

            </div>

        </div>

    </div>

    <div style="margin-top:30px">

        @if($isi->children->count())

            <a
                href="{{ route('isi.show',$isi->id) }}"
                class="btn-save">

                <i class="bi bi-folder2-open"></i>

                Buka Isi Standar Berikutnya

            </a>

        @else

            <a
                href="{{ route('indikator.index',$isi->id) }}"
                class="btn-save">

                <i class="bi bi-card-checklist"></i>

                Kelola Indikator

            </a>

        @endif

    </div>

</div>

@endsection