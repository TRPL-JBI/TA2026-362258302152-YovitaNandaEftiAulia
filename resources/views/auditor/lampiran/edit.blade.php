@extends('layouts.auditor')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/app/18-auditor-kesimpulan.css') }}"
    >
@endpush

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Lampiran Audit /

    Edit

</h3>

<!-- ===========================================================
    CARD
=========================================================== -->

<div class="card">

    <!-- =======================================================
        HEADER
    ======================================================== -->

    <div class="temuan-header">

        <div>

            <h4>

                Edit Lampiran Audit

            </h4>

            <small>

                Form Edit Lampiran Audit

            </small>

        </div>

    </div>


    <!-- =======================================================
        FORM
    ======================================================== -->

    <form
        action="{{ route('auditor.lampiran.update',$data->id) }}"
        method="POST">

        @csrf

        @method('PUT')

        <!-- PERIODE -->

        <div class="form-group">

            <label>

                Periode AMI

            </label>

            <select
                name="id_periode_ami"
                class="form-control"
                required>

             @foreach($periode as $item)

    <option
        value="{{ $item->id }}"
        @selected(
            old(
                'id_periode_ami',
                $data->id_periode_ami
            ) == $item->id
        )
    >
        {{ $item->tahun }}

        â€”

        {{
            $item->unitKerja->nama
            ?? $item->unitKerja->nama_unit_kerja
            ?? '-'
        }}
    </option>

@endforeach

            </select>

        </div>

        <!-- LINK -->

        <div class="form-group">

            <label>

                Link Lampiran

            </label>

            <input
                type="url"
                name="link_file"
                class="form-control"
                value="{{ old('link_file', $data->link_file) }}"
                placeholder="https://drive.google.com/..."
                required
            >

        </div>

        <!-- BUTTON -->

        <div class="form-action">

            <a
                href="{{ route('auditor.lampiran.index') }}"
                class="btn-back">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <button
                type="submit"
                class="btn-save">

                <i class="bi bi-check-lg"></i>

                Update

            </button>

        </div>

    </form>

</div>

@endsection
