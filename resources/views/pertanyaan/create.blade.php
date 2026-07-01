@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Tambah Pertanyaan AMI
</h3>

<div class="form-card">

<form action="{{ route('pertanyaan.store') }}"
      method="POST">

@csrf

<input type="hidden"
       name="id_periode"
       value="{{ $periode->id }}">

<!-- PERIODE -->

<div class="form-group">

    <label>
        Pilih Periode AMI
    </label>

    <select disabled>

        <option>

            {{ $periode->tahun }}

        </option>

    </select>

</div>

<!-- PENERAPAN -->

<div class="form-group">

    <label>
        Pilih Penerapan Standar
    </label>

    <select
        name="id_penerapan_standar"
        required>

        <option value="">
            Pilih Penerapan Standar
        </option>

        @foreach($penerapan as $item)

        <option value="{{ $item->id }}">

            Standar #{{ $item->id }}

        </option>

        @endforeach

    </select>

</div>

<!-- INDIKATOR -->

<div class="form-group">

    <label>
        Indikator / Isi Standar
    </label>

    <input type="text"
           name="indikator"
           placeholder="">

</div>

<!-- REFERENSI -->

<div class="form-group">

    <label>
        Referensi / Butir Mutu
    </label>

    <input type="text"
           name="referensi"
           placeholder="">

</div>

<!-- PERTANYAAN -->

<div class="form-group">

    <label>
        Pertanyaan Audit
    </label>

    <textarea
        name=""
        required></textarea>

</div>

<!-- USER -->

<div class="form-group">

    <label>
        Dibuat Oleh
    </label>

    <input type="text"
           value="{{ (session('user')['nama'] ?? session('user')->nama) }} - {{ ucfirst(session('user')['role'] ?? session('user')->role) }}"
           disabled>

</div>

<div class="form-action">

    <button type="submit"
            class="btn-save">

        Simpan

    </button>

    <a href="{{ route('pertanyaan.index',$periode->id) }}"
       class="btn-delete">

        Batal

    </a>

</div>

</form>

</div>

@endsection