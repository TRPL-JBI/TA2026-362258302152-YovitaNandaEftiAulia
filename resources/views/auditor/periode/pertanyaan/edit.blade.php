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

    <a href="{{ route('auditor.pertanyaan.index',
        $data->penerapanStandar->standarMutuPeriodeAmi->id_periode_ami) }}"
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

            <h4>Edit Pertanyaan AMI</h4>

        </div>

    </div>

    <form action="{{ route('auditor.pertanyaan.update',$data->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="form-group">

            <label>Penerapan Standar</label>

            <select
                name="id_penerapan_standar"
                class="form-control"
                required>

                @foreach($penerapan as $item)

                <option
                    value="{{ $item->id }}"
                    {{ $item->id==$data->id_penerapan_standar ? 'selected' : '' }}>

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
                required>{{ $data->indikator }}</textarea>

        </div>

        <div class="form-group">

            <label>Referensi</label>

            <textarea
                name="referensi"
                rows="3"
                class="form-control"
                required>{{ $data->referensi }}</textarea>

        </div>

        <div class="form-group">

            <label>Pertanyaan AMI</label>

            <textarea
                name="pertanyaan"
                rows="6"
                class="form-control"
                required>{{ $data->pertanyaan }}</textarea>

        </div>

        <div
            style="
                display:flex;
                justify-content:end;
                gap:10px;
                margin-top:25px;">

            <a href="{{ route('auditor.pertanyaan.index',
                $data->penerapanStandar->standarMutuPeriodeAmi->id_periode_ami) }}"
               class="btn-cancel">

                Batal

            </a>

            <button
                type="submit"
                class="btn-save">

                Update

            </button>

        </div>

    </form>

</div>

@endsection