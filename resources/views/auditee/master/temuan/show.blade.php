@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">
    Dashboard / Temuan Audit / Detail Temuan
</div>

<div class="card">

    <div class="card-header">

        <h2 class="card-title">
            Detail Temuan Audit
        </h2>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <table class="detail-table">

        <tbody>

            <tr>
                <th width="220">
                    Periode AMI
                </th>

                <td>
                    {{
                        $temuan->penerapan
                            ->standarmutuPeriode
                            ->periodeAmi
                            ->tahun
                        ?? '-'
                    }}
                </td>
            </tr>

            <tr>
                <th>
                    Unit Kerja
                </th>

                <td>
                    {{
                        $temuan->penerapan
                            ->standarmutuPeriode
                            ->periodeAmi
                            ->unitKerja
                            ->nama_unit_kerja
                        ?? $temuan->penerapan
                            ->standarmutuPeriode
                            ->periodeAmi
                            ->unitKerja
                            ->nama
                        ?? '-'
                    }}
                </td>
            </tr>

            <tr>
                <th>
                    Standar Mutu
                </th>

                <td>
                    {{
                        $temuan->penerapan
                            ->standarmutuPeriode
                            ->standarMutu
                            ->nama_standar_mutu
                        ?? '-'
                    }}
                </td>
            </tr>

            <tr>
                <th>
                    Indikator Standar
                </th>

                <td>
                    {{
                        $temuan->penerapan
                            ->indikator
                            ->deskripsi
                        ?? '-'
                    }}
                </td>
            </tr>

            <tr>
                <th>
                    Hasil Penerapan
                </th>

                <td>
                    {!! nl2br(e(
                        $temuan->penerapan
                            ->deskripsi_hasil
                        ?? '-'
                    )) !!}
                </td>
            </tr>

            <tr>
                <th>
                    Bukti Pendukung
                </th>

                <td>

                    @if(!empty(
                        $temuan->penerapan->link_bukti
                    ))

                        <a
                            href="{{ $temuan->penerapan->link_bukti }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn-detail"
                        >
                            <i class="bi bi-box-arrow-up-right"></i>
                            Lihat Bukti
                        </a>

                    @else

                        Belum ada bukti pendukung.

                    @endif

                </td>
            </tr>

            <tr>
                <th>
                    Temuan Auditor
                </th>

                <td>
                    {!! nl2br(e(
                        $temuan->temuan
                        ?? '-'
                    )) !!}
                </td>
            </tr>

            <tr>
                <th>
                    Status Temuan
                </th>

                <td>

                    @php
                        $status = strtolower(
                            trim($temuan->status_temuan ?? '')
                        );
                    @endphp

                    @if($status === 'open')

                        <span class="badge-open">
                            Open
                        </span>

                    @elseif($status === 'closed')

                        <span class="badge-close">
                            Closed
                        </span>

                    @else

                        {{ ucfirst($status ?: '-') }}

                    @endif

                </td>
            </tr>

            <tr>
                <th>
                    Tanggapan Auditee
                </th>

                <td>

                    @forelse($temuan->tanggapan as $tanggapan)

                        <div style="margin-bottom: 12px;">

                            <strong>
                                Tanggapan {{ $loop->iteration }}
                            </strong>

                            <div style="margin-top: 5px;">
                                {!! nl2br(e(
                                    $tanggapan->tanggapan
                                    ?? '-'
                                )) !!}
                            </div>

                        </div>

                    @empty

                        Belum ada tanggapan.

                    @endforelse

                </td>
            </tr>

            <tr>
                <th>
                    Akar Masalah
                </th>

                <td>

                    @forelse($temuan->akarMasalah as $akar)

                        <div style="margin-bottom: 12px;">

                            <strong>
                                Akar Masalah {{ $loop->iteration }}
                            </strong>

                            <div style="margin-top: 5px;">
                                {!! nl2br(e(
                                    $akar->akar_masalah
                                    ?? '-'
                                )) !!}
                            </div>

                        </div>

                    @empty

                        Belum ada akar masalah.

                    @endforelse

                </td>
            </tr>

        </tbody>

    </table>

    <div class="form-footer">

        <a
            href="{{ route('auditee.temuan.index') }}"
            class="btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

        @php
            $tanggapanPertama = $temuan
                ->tanggapan
                ->first();
        @endphp

        @if(!$tanggapanPertama)

            <a
                href="{{ route(
                    'auditee.tanggapan.create',
                    $temuan->id
                ) }}"
                class="btn-save"
            >
                <i class="bi bi-chat-dots"></i>
                Beri Tanggapan
            </a>

        @else

            <a
                href="{{ route(
                    'auditee.tanggapan.edit',
                    $tanggapanPertama->id
                ) }}"
                class="btn-save"
            >
                <i class="bi bi-pencil"></i>
                Edit Tanggapan
            </a>

        @endif

    </div>

</div>

@endsection