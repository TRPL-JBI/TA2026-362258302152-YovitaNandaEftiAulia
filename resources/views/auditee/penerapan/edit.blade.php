@extends('layouts.auditee')

@section('content')
<div class="quality-form-page">
    <div class="quality-page-heading">
        <div>
            <div class="breadcrumb quality-breadcrumb">
                Dashboard / Standar Mutu / Kelola Penerapan
            </div>

            <h2>Kelola Penerapan Standar</h2>

            <p>
                Perbarui hasil penerapan atau hapus data penerapan indikator ini.
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
                    {{ $data->standarmutuPeriode->standarMutu->nama_standar_mutu }}
                </strong>
            </div>

            <div>
                <span>Periode AMI</span>
                <strong>
                    {{ $data->standarmutuPeriode->periodeAmi->tahun ?? '-' }}
                </strong>
            </div>

            <div class="summary-wide">
                <span>Indikator</span>
                <strong>
                    {{ $data->indikator->deskripsi ?? '-' }}
                </strong>
            </div>
        </div>

        <form
            action="{{ route(
                'auditee.penerapan.update',
                $data->id
            ) }}"
            method="POST"
            class="quality-form"
        >
            @csrf
            @method('PUT')

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
                    required
                >{{ old('deskripsi_hasil', $data->deskripsi_hasil) }}</textarea>
            </div>

   <div class="quality-form-group">

    <label for="link_bukti">
        Tautan Bukti Pendukung
    </label>

    <input
        id="link_bukti"
        type="url"
        name="link_bukti"
        value="{{ old('link_bukti', $data->link_bukti) }}"
        placeholder="https://drive.google.com/..."
    >

    @error('link_bukti')
        <small class="quality-error-message">
            {{ $message }}
        </small>
    @enderror

    <small class="quality-help-text">
        Pastikan tautan dapat dibuka oleh auditor.
    </small>

    @if(!empty($data->link_bukti))

        <div class="quality-current-evidence">

            <div class="quality-current-evidence-info">

                <i class="bi bi-link-45deg"></i>

                <div>
                    <strong>
                        Bukti pendukung tersedia
                    </strong>

                    <span>
                        Klik tombol untuk membuka tautan di tab baru.
                    </span>
                </div>

            </div>

            <a
                href="{{ $data->link_bukti }}"
                target="_blank"
                rel="noopener noreferrer"
                class="quality-open-evidence-button"
            >
                <i class="bi bi-box-arrow-up-right"></i>

                Buka Bukti Pendukung
            </a>

        </div>

    @endif

</div>

            <div class="quality-form-actions">
                <a
                    href="{{ route(
                        'auditee.standar.index',
                        $data->standarmutuPeriode->id_standar_mutu
                    ) }}"
                    class="quality-secondary-button"
                >
                    <i class="bi bi-arrow-left"></i>
                    Kembali
                </a>

                <button type="submit" class="quality-primary-button">
                    <i class="bi bi-save"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>

        <div class="quality-danger-zone">
            <div>
                <strong>Hapus penerapan</strong>
                <span>
                    Data hasil dan tautan bukti pada indikator ini akan dihapus.
                </span>
            </div>

            <form
                action="{{ route(
                    'auditee.penerapan.destroy',
                    $data->id
                ) }}"
                method="POST"
                onsubmit="return confirm(
                    'Yakin ingin menghapus penerapan standar ini?'
                )"
            >
                @csrf
                @method('DELETE')

                <button type="submit" class="quality-danger-button">
                    <i class="bi bi-trash3"></i>
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

