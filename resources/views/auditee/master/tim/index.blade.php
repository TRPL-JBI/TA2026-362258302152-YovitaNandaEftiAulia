@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">
    Dashboard / Audit AMI / Tim Audit
</div>

<div class="card auditee-master-card">

    <div class="auditee-master-header">

        <div>
            <h2>
                Data Tim Audit
            </h2>

            <p>
                Daftar auditor yang ditugaskan pada unit kerja Anda.
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
                        Nama Auditor
                    </th>

                    <th>
                        Email
                    </th>

                    <th width="170">
                        Peran
                    </th>

                    <th width="100">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody>

                @forelse($data as $item)

                    @php
                        $role = strtolower(
                            trim($item->role ?? '')
                        );
                    @endphp

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
                            {{ $item->user->nama ?? '-' }}
                        </td>

                        <td>
                            {{ $item->user->email ?? '-' }}
                        </td>

                        <td>

                            @if(str_contains($role, 'ketua'))

                                <span class="auditee-role role-leader">
                                    Ketua Auditor
                                </span>

                            @elseif($role === 'auditor')

                                <span class="auditee-role role-auditor">
                                    Auditor
                                </span>

                            @else

                                <span class="auditee-role role-other">
                                    {{ ucwords($item->role ?? '-') }}
                                </span>

                            @endif

                        </td>

                        <td>

                            <a
                                href="{{ route(
                                    'auditee.audit.tim.show',
                                    $item->id
                                ) }}"
                                class="auditee-detail-button"
                                title="Lihat detail tim"
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
                            Belum ada tim audit untuk unit kerja Anda.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection