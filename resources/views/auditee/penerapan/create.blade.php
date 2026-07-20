@extends('layouts.auditee')

@section('content')
<div class="quality-form-page">
    <div class="quality-page-heading">
        <div>
            <div class="breadcrumb quality-breadcrumb">
                Dashboard / Standar Mutu / Penerapan
            </div>

            <h2>Penerapan Standar</h2>

            <p>
                Lengkapi hasil penerapan dan tautan bukti untuk indikator
                yang dipilih.
            </p>
        </div>
    </div>

    @if($errors->any())
        <div class="quality-alert quality-alert-warning">
            <i class="bi bi-exclamation-triangle-fill"></i>

            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="quality-form-card">
        <div class="quality-form-summary">
            <div>
                <span>Standar Mutu</span>
                <strong>
                    {{ $standar->standarMutu->nama_standar_mutu }}
                </strong>
            </div>

            <div>
                <span>Periode AMI</span>
                <strong>
                    {{ $standar->periodeAmi->tahun ?? '-' }}
                </strong>
            </div>

            <div class="summary-wide">
                <span>Indikator</span>
                <strong>
                    {{ $indikator->deskripsi ?? '-' }}
                </strong>
            </div>
        </div>

        <form
            action="{{ route('auditee.penerapan.store') }}"
            method="POST"
            class="quality-form"
        >
            @csrf

            <input
                type="hidden"
                name="id_standarmutu_periodeami"
                value="{{ $standar->id }}"
            >

            <input
                type="hidden"
                name="id_indikator"
                value="{{ $indikator->id }}"
            >

            <div class="form-group">
                <label for="deskripsi_hasil">
                    Deskripsi Hasil Penerapan
                    <span class="required-mark">*</span>
                </label>

                <textarea
                    id="deskripsi_hasil"
                    name="deskripsi_hasil"
                    rows="7"
                    class="form-control"
                    placeholder="Jelaskan bagaimana indikator standar ini telah diterapkan pada unit kerja..."
                    required
                >{{ old('deskripsi_hasil') }}</textarea>
            </div>

            <div class="form-group">
                <label for="link_bukti">
                    Tautan Bukti Pendukung
                </label>

                <input
                    id="link_bukti"
                    type="url"
                    name="link_bukti"
                    class="form-control"
                    value="{{ old('link_bukti') }}"
                    placeholder="https://drive.google.com/..."
                >

                <small class="quality-help-text">
                    Gunakan tautan yang dapat diakses oleh auditor.
                </small>
            </div>

            <div class="quality-form-actions">
                <a
                    href="{{ route(
                        'auditee.standar.index',
                        $standar->id_standar_mutu
                    ) }}"
                    class="quality-secondary-button"
                >
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>

                <button type="submit" class="quality-primary-button">
                    <i class="bi bi-check2-circle"></i>
                    Simpan Penerapan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

