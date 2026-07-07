@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Audit Mutu Internal
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('auditor.temuan.index') }}">
        Temuan Audit
    </a>

    <a href="{{ route('auditor.pertanyaan.index',$periode->id) }}"
       class="active">
        Pertanyaan AMI
    </a>

    <a href="#">
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

<div class="card">

    <div class="card-header periode-header">

        <div>

            <h4>Tambah Pertanyaan AMI</h4>

            <small>

                Periode :

                <b>{{ $periode->tahun }}</b>

            </small>

        </div>

    </div>

    <form action="{{ route('auditor.pertanyaan.store',$periode->id) }}"
          method="POST">

        @csrf

        <input
            type="hidden"
            name="id_periode"
            value="{{ $periode->id }}">

        <div class="form-group">

            <label>Penerapan Standar</label>

            <select
                name="id_penerapan_standar"
                class="form-control"
                required>

                <option value="">

                    -- Pilih Penerapan Standar --

                </option>

                @foreach($penerapan as $item)

                <option value="{{ $item->id }}">

                    {{ $item->standarMutuPeriodeAmi->standarMutu->nama_standar_mutu }}

                </option>

                @endforeach

            </select>

        </div>

        <div class="form-group">

            <label>Indikator</label>

            <textarea
                name="indikator"
                rows="3"
                class="form-control"
                required>{{ old('indikator') }}</textarea>

        </div>

        <div class="form-group">

            <label>Referensi</label>

            <textarea
                name="referensi"
                rows="3"
                class="form-control"
                required>{{ old('referensi') }}</textarea>

        </div>

        <div class="form-group">

            <label>Pertanyaan AMI</label>

            <textarea
                name="pertanyaan"
                rows="6"
                class="form-control"
                required>{{ old('pertanyaan') }}</textarea>

        </div>

        <div
            style="
                display:flex;
                justify-content:end;
                gap:10px;
                margin-top:30px;">

            <a href="{{ route('auditor.pertanyaan.index',$periode->id) }}"
               class="btn-cancel">

                Batal

            </a>

            <button
                type="submit"
                class="btn-save">

                Simpan

            </button>

        </div>

    </form>

</div>

@endsection