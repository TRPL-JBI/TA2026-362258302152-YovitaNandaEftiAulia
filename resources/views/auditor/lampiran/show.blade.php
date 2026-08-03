@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/app/18-auditor-kesimpulan.css') }}"
    >
@endpush

@section('content')

<h3 class="breadcrumb">
    Dashboard /
    Audit Mutu Internal /
    Lampiran Audit /
    Detail
</h3>

<div class="card">

    <div class="temuan-header">

        <div>
            <h4>
                Detail Lampiran Audit
            </h4>

            <small>
                Informasi lampiran Audit Mutu Internal
            </small>
        </div>

    </div>

    <table class="detail-table">

        <tr>
            <th width="220">
                Periode AMI
            </th>

            <td>
                {{ $data->periodeAmi->tahun ?? '-' }}
            </td>
        </tr>

        <tr>
            <th>
                Standar Mutu
            </th>

            <td>
                {{
                    $data->periodeAmi
                        ->standarMutu
                        ->nama_standar_mutu
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
                    $data->periodeAmi
                        ->unitKerja
                        ->nama
                    ?? $data->periodeAmi
                        ->unitKerja
                        ->nama_unit_kerja
                    ?? '-'
                }}
            </td>
        </tr>

        <tr>
            <th>
                Link Lampiran
            </th>

            <td>

                @if(!empty($data->link_file))

                    <a
                        href="{{ $data->link_file }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn-detail"
                    >
                        <i class="bi bi-box-arrow-up-right"></i>

                        Buka Lampiran
                    </a>

                    <div style="
                        margin-top: 10px;
                        word-break: break-all;
                        color: #667085;
                    ">
                        {{ $data->link_file }}
                    </div>

                @else

                    <span>
                        Tidak ada link lampiran.
                    </span>

                @endif

            </td>
        </tr>

        <tr>
            <th>
                Dibuat Oleh
            </th>

            <td>
                {{
                    $data->user->nama
                    ?? $data->user->name
                    ?? '-'
                }}
            </td>
        </tr>

    </table>

    <div class="form-action">

        <a
            href="{{ route('auditor.lampiran.index') }}"
            class="btn-back"
        >
            <i class="bi bi-arrow-left"></i>

            Kembali
        </a>

        <a
            href="{{ route(
                'auditor.lampiran.edit',
                $data->id
            ) }}"
            class="btn-save"
        >
            <i class="bi bi-pencil"></i>

            Edit
        </a>

    </div>

</div>

@endsection
