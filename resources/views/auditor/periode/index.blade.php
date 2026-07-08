@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Periode AMI
</h3>

<div class="card">

    <div class="card-header"
         style="display:flex;
                justify-content:space-between;
                align-items:center;">

        <h4>Data Periode AMI</h4>

    </div>

    <table>

        <thead>

            <tr>

                <th width="70">No.</th>

                <th>Tahun</th>

                <th>Standar Mutu</th>

                <th>Unit Kerja</th>

                <th>Status</th>

                <th width="120" style="text-align:center;">
                    Aksi
                </th>

            </tr>

        </thead>

        <tbody>

        @forelse($data as $item)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>{{ $item->tahun }}</td>

                <td>{{ $item->standarMutu->nama_standar_mutu }}</td>

                <td>{{ $item->unitKerja->nama }}</td>

                <td>

                    @if($item->status == 'ditutup')

                        <span style="
                            background:#FEE2E2;
                            color:#DC2626;
                            padding:6px 12px;
                            border-radius:20px;
                            font-size:13px;
                            font-weight:600;">

                            Ditutup

                        </span>

                    @elseif($item->status == 'dibuka')

                        <span style="
                            background:#DCFCE7;
                            color:#16A34A;
                            padding:6px 12px;
                            border-radius:20px;
                            font-size:13px;
                            font-weight:600;">

                            Dibuka

                        </span>

                    @else

                        <span style="
                            background:#E5E7EB;
                            color:#374151;
                            padding:6px 12px;
                            border-radius:20px;
                            font-size:13px;
                            font-weight:600;">

                            {{ ucfirst($item->status) }}

                        </span>

                    @endif

                </td>

                <td style="text-align:center;">

                    <div class="action-buttons"
                         style="justify-content:center;">

                        <a href="{{ route('auditor.periode.show',$item->id) }}"
                           class="btn-icon btn-detail"
                           title="Detail Periode">

                            <i class="bi bi-eye"></i>

                        </a>

                    </div>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="6"
                    style="text-align:center;padding:30px;">

                    Belum ada Data Periode AMI

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection