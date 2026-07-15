@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">
    Dashboard / Audit AMI / Jadwal Audit
</div>

<div class="card auditee-master-card">

    <div class="auditee-master-header">

        <div>
            <h2>
                Data Jadwal Audit
            </h2>

            <p>
                Jadwal kegiatan audit untuk unit kerja Anda.
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
                        Unit Kerja
                    </th>

                    <th>
                        Kegiatan
                    </th>

                    <th width="190">
                        Waktu
                    </th>

                    <th width="100">
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
                            {{ $item->periode->tahun ?? '-' }}
                        </td>

                        <td>
                            {{
                                $item->periode
                                    ->standarMutu
                                    ->nama_standar_mutu
                                ?? '-'
                            }}
                        </td>

                        <td>
                            {{
                                $item->periode
                                    ->unitKerja
                                    ->nama
                                ?? '-'
                            }}
                        </td>

                        <td>
                            {{ $item->kegiatan ?? '-' }}
                        </td>

                        <td>
                            {{ $item->waktu ?? '-' }}
                        </td>

                        <td>

                            <a
                                href="{{ route(
                                    'auditee.audit.jadwal.show',
                                    $item->id
                                ) }}"
                                class="auditee-detail-button"
                                title="Lihat detail jadwal"
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
                            Belum ada jadwal audit untuk unit kerja Anda.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection