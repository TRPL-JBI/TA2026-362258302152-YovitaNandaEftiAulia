@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">
    Dashboard / Audit AMI / Temuan Audit
</div>

<div class="card auditee-master-card">

    <div class="auditee-master-header">

        <div>
            <h2>
                Data Temuan Audit
            </h2>

            <p>
                Daftar temuan audit untuk unit kerja Anda.
            </p>
        </div>

        <span class="auditee-view-badge">
            <i class="bi bi-eye"></i>
            View Only
        </span>

    </div>

    <div class="table-wrapper">

        <table class="custom-table auditee-master-table">

            <thead>
                <tr>
                    <th width="70">
                        No.
                    </th>

                    <th>
                        Tahun AMI
                    </th>

                    <th>
                        Standar Mutu
                    </th>

                    <th>
                        Pertanyaan Audit
                    </th>

                    <th>
                        Temuan Auditor
                    </th>

                    <th width="150">
                        Status
                    </th>

                    <th width="100">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $item)

                    @php
                        $penerapan =
                            $item->pertanyaan->penerapan ?? null;

                        $standarPeriode =
                            $penerapan->standarmutuPeriode ?? null;

                        $periode =
                            $standarPeriode->periodeAmi ?? null;

                        $status = strtolower(
                            trim($item->status_temuan ?? '')
                        );
                    @endphp

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $periode->tahun ?? '-' }}
                        </td>

                        <td>
                            {{
                                $standarPeriode
                                    ->standarMutu
                                    ->nama_standar_mutu
                                ?? '-'
                            }}
                        </td>

                        <td>
                            <div class="auditee-text-preview">
                                {{
                                    $item->pertanyaan->pertanyaan
                                    ?? '-'
                                }}
                            </div>
                        </td>

                        <td>
                            <div class="auditee-text-preview">
                                {{ $item->temuan ?? '-' }}
                            </div>
                        </td>

                        <td>

                            @if(in_array(
                                $status,
                                ['selesai', 'ditutup', 'closed']
                            ))

                                <span class="auditee-status status-finished">
                                    Ditutup
                                </span>

                            @elseif(in_array(
                                $status,
                                ['terbuka', 'open']
                            ))

                                <span class="auditee-status status-open">
                                    Terbuka
                                </span>

                            @else

                                <span class="auditee-status status-neutral">
                                    {{
                                        ucfirst(
                                            $item->status_temuan ?? '-'
                                        )
                                    }}
                                </span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route(
                                    'auditee.audit.temuan.show',
                                    $item->id
                                ) }}"
                                class="auditee-detail-button"
                                title="Lihat detail temuan"
                            >
                                <i class="bi bi-eye"></i>
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="7"
                            class="auditee-empty-table"
                        >
                            Belum ada data temuan audit untuk unit kerja Anda.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection