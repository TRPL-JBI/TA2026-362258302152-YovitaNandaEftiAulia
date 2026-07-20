@extends('layouts.auditor')

@section('content')

<div class="breadcrumb">
    Dashboard / Audit Mutu Internal / Detail Temuan
</div>

<div class="card">

    <div class="card-header">

        <h2 class="card-title">
            Detail Temuan Audit
        </h2>

        <a
            href="{{ route('auditor.temuan.index') }}"
            class="btn-back"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

    </div>

    <table class="table-detail">

        <tr>
            <th width="240">
                Tahun AMI
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
                        ->nama
                    ?? $temuan->penerapan
                        ->standarmutuPeriode
                        ->periodeAmi
                        ->unitKerja
                        ->nama_unit_kerja
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
                Indikator
            </th>

            <td>
                {{
                    $temuan->penerapan
                        ->indikator
                        ->deskripsi
                    ?? $temuan->penerapan
                        ->indikator
                        ->indikator
                    ?? '-'
                }}
            </td>
        </tr>

        <tr>
            <th>
                Hasil Penerapan Auditee
            </th>

            <td>
                {{
                    $temuan->penerapan
                        ->deskripsi_hasil
                    ?? '-'
                }}
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

                    Belum ada bukti.

                @endif

            </td>
        </tr>

        <tr>
            <th>
                Auditee
            </th>

            <td>
                {{
                    $temuan->penerapan
                        ->user
                        ->nama
                    ?? $temuan->penerapan
                        ->user
                        ->name
                    ?? '-'
                }}
            </td>
        </tr>

        <tr>
            <th>
                Temuan Auditor
            </th>

            <td>
                {!! nl2br(e($temuan->temuan)) !!}
            </td>
        </tr>

        <tr>
            <th>
                Status Temuan
            </th>

            <td>

                @if(
                    strtolower($temuan->status_temuan)
                    === 'open'
                )

                    <span class="badge-open">
                        Open
                    </span>

                @else

                    <span class="badge-close">
                        Closed
                    </span>

                @endif

            </td>
        </tr>

        <tr>
            <th>
                Tanggapan Auditee
            </th>

            <td>

                @forelse($temuan->tanggapan as $tanggapan)

                    <div>
                        {{ $loop->iteration }}.
                        {{ $tanggapan->tanggapan }}
                    </div>

                @empty

                    Belum ada tanggapan.

                @endforelse

            </td>
        </tr>

    </table>

</div>

@endsection
