@extends('layouts.auditor')

@php
use Illuminate\Support\Str;
@endphp

@section('content')

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Tanggapan Auditee

</h3>

<div class="card">

    <!-- HEADER -->

    <div class="temuan-header">

        <div>

            <h4>

                Daftar Tanggapan Auditee

            </h4>

            <small>

                Data Tanggapan Auditee

            </small>

        </div>

    </div>

    <!-- TAB MENU -->

    <div class="temuan-tab">

        <a href="{{ route('auditor.temuan.index') }}">

            Temuan Audit

        </a>

        <a
            href="{{ route('auditor.tanggapan.index') }}"
            class="active">

            Tanggapan Auditee

        </a>

        <a href="#">

            Akar Masalah

        </a>

        <a href="#">

            Rekomendasi

        </a>

        <a href="#">

            Kesimpulan

        </a>

        <a href="#">

            Lampiran

        </a>

    </div>

    <table>

        <thead>

            <tr>

                <th width="70">

                    No

                </th>

                <th>

                    Temuan Audit

                </th>

                <th>

                    Tanggapan Auditee

                </th>

                <th width="180">

                    Auditee

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

                    {{ $item->temuan->temuan ?? '-' }}

                </td>

                <td>

                    {{ Str::limit($item->tanggapan,60) }}

                </td>

                <td>

                    {{ $item->user->nama ?? '-' }}

                </td>

                <td>

                    <a
                        href="{{ route('auditor.tanggapan.show',$item->id) }}"
                        class="btn-icon btn-detail">

                        <i class="bi bi-eye"></i>

                    </a>

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="5"
                    class="table-empty">

                    Belum ada Tanggapan Auditee.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection