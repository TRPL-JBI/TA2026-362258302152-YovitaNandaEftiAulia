@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">

    Dashboard / Temuan Audit

</div>

<div class="card">

    <div class="card-header periode-header">

        <h2 class="card-title">

            Data Temuan Audit

        </h2>

    </div>

    <div class="table-wrapper">

        <table class="custom-table">

            <thead>

                <tr>

                    <th width="70">
                        No
                    </th>

                    <th>
                        Pertanyaan AMI
                    </th>

                    <th>
                        Temuan Auditor
                    </th>

                    <th width="160">
                        Status
                    </th>

                    <th width="180">
                        Tanggapan
                    </th>

                    <th width="180">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody>

            @forelse($temuan as $item)

                @php

                    $tanggapan = $item->tanggapan->first();

                @endphp

                <tr>

                    <td>

                        {{ $loop->iteration }}

                    </td>

                    <td>

                        {{
                                $item->penerapan->indikator->deskripsi
                                ?? '-'
                        }}

                    </td>

                        <td>
                            {{
                                $item->penerapan->deskripsi_hasil
                                ?? '-'
                            }}
                        </td>

                    <td>

                        {{ $item->temuan }}

                    </td>

                    <td>

                        {{ $item->status_temuan }}

                    </td>

                    <td>

                        @if($tanggapan)

                            <span style="color:green;font-weight:600;">

                                Sudah Ditanggapi

                            </span>

                        @else

                            <span style="color:red;font-weight:600;">

                                Belum Ditanggapi

                            </span>

                        @endif

                    </td>

                    <td>

                        <div class="action-buttons">

                            @if(!$tanggapan)

                                <a href="{{ route('auditee.tanggapan.create',$item->id) }}"
                                   class="btn-icon btn-add">

                                    <i class="bi bi-chat-dots"></i>

                                </a>

                            @else

                                <a href="{{ route('auditee.temuan.show',$item->id) }}"
                                   class="btn-icon btn-detail">

                                    <i class="bi bi-eye"></i>

                                </a>

                                <a href="{{ route('auditee.tanggapan.edit',$tanggapan->id) }}"
                                   class="btn-icon btn-edit">

                                    <i class="bi bi-pencil"></i>

                                </a>

                                <form action="{{ route('auditee.tanggapan.destroy',$tanggapan->id) }}"
                                      method="POST">

                                    @csrf

                                    @method('DELETE')

                                    <button class="btn-icon btn-delete"
                                        onclick="return confirm('Hapus tanggapan ini?')">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            @endif

                        </div>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6">

                        Belum ada temuan audit.

                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
