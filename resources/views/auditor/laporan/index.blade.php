@extends('layouts.auditor')

@section('content')

<div class="breadcrumb">

    Dashboard /

    Laporan Audit Mutu Internal

</div>

<div class="card">

    <div class="temuan-header">

        <div>

            <h2>

                Laporan Audit Mutu Internal

            </h2>

            <p>

                Daftar laporan audit mutu internal berdasarkan periode audit.

            </p>

        </div>

    </div>

    @forelse($data as $item)

    <div class="laporan-item">

        <div class="laporan-left">

            <div class="laporan-icon">

                <i class="bi bi-file-earmark-text-fill"></i>

            </div>

            <div>

                <h4>

                    Laporan AMI Tahun

                    {{ $item->tahun }}

                </h4>

                <p>

                    Standar :

                    {{ $item->standarMutu->nama_standar_mutu ?? '-' }}

                </p>

                <p>

                    Unit Kerja :

                    {{ $item->unitKerja->nama ?? '-' }}

                </p>

            </div>

        </div>

        <div class="laporan-right">

            @if($item->status=='ditutup')

                <span class="badge badge-success">

                    Audit Selesai

                </span>

            @elseif($item->status=='berjalan')

                <span class="badge badge-warning">

                    Sedang Berjalan

                </span>

            @else

                <span class="badge badge-secondary">

                    Draft

                </span>

            @endif

            <a

                href="{{ route('auditor.laporan.show',$item->id) }}"

                class="btn-detail"

            >

                <i class="bi bi-eye"></i>

                Lihat Laporan

            </a>

        </div>

    </div>

    @empty

    <div class="empty-state">

        <i class="bi bi-file-earmark-text"></i>

        <h3>

            Belum Ada Laporan

        </h3>

        <p>

            Laporan akan muncul setelah audit selesai.

        </p>

    </div>

    @endforelse

</div>

@endsection