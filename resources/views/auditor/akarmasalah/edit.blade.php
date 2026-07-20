@extends('layouts.auditor')

@section('content')

<!-- ===========================================================
    BREADCRUMB
=========================================================== -->

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Akar Masalah /

    Edit

</h3>

<!-- ===========================================================
    TAB MENU
=========================================================== -->

<div class="temuan-tab">

    <a href="{{ route('auditor.temuan.index') }}">

        Temuan Audit

    </a>

    <a href="{{ route('auditor.tanggapan.index') }}">

        Tanggapan Auditee

    </a>

    <a href="{{ route('auditor.akarmasalah.index') }}"
       class="active">

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

<!-- ===========================================================
    CARD
=========================================================== -->

<div class="card">

    <div class="card-header">

        <h4>

            Edit Akar Masalah

        </h4>

    </div>

    <form
        action="{{ route('auditor.akarmasalah.update',$data->id) }}"
        method="POST">

        @csrf

        @method('PUT')

        <!-- TEMUAN -->

        <div class="form-group">

            <label>

                Temuan Audit

            </label>

            <select
                name="id_temuan"
                class="form-control"
                required>

                @foreach($temuan as $item)

                    <option
                        value="{{ $item->id }}"
                        {{ $data->id_temuan == $item->id ? 'selected' : '' }}>

                        {{ $item->temuan }}

                    </option>

                @endforeach

            </select>

        </div>

        <!-- AKAR MASALAH -->

        <div class="form-group">

            <label>

                Akar Masalah

            </label>

            <textarea
                name="akar_masalah"
                rows="6"
                class="form-control"
                required>{{ $data->akar_masalah }}</textarea>

        </div>

        <!-- BUTTON -->

        <div class="form-action">

            <a
                href="{{ route('auditor.akarmasalah.index') }}"
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
