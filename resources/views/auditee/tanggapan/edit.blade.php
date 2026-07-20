@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">
    Dashboard / Temuan Audit / Edit Tanggapan
</div>

<div class="card">

    <div class="card-header">

        <h2 class="card-title">
            Edit Tanggapan Auditee
        </h2>

    </div>

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Data belum dapat disimpan.
            </strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>

        </div>

    @endif

    <form
        action="{{ route(
            'auditee.tanggapan.update',
            $data->id
        ) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div class="form-group">

            <label>
                Indikator Standar
            </label>

            <textarea
                class="form-control"
                rows="4"
                readonly
            >{{
                $data->temuan
                    ->penerapan
                    ->indikator
                    ->deskripsi
                ?? '-'
            }}</textarea>

        </div>

        <div class="form-group">

            <label>
                Hasil Penerapan
            </label>

            <textarea
                class="form-control"
                rows="4"
                readonly
            >{{
                $data->temuan
                    ->penerapan
                    ->deskripsi_hasil
                ?? '-'
            }}</textarea>

        </div>

        <div class="form-group">

            <label>
                Bukti Pendukung
            </label>

            @if(!empty(
                $data->temuan
                    ->penerapan
                    ->link_bukti
            ))

                <div style="margin-top: 8px;">

                    <a
                        href="{{
                            $data->temuan
                                ->penerapan
                                ->link_bukti
                        }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn-detail"
                    >
                        <i class="bi bi-box-arrow-up-right"></i>
                        Lihat Bukti
                    </a>

                </div>

            @else

                <div style="margin-top: 8px;">
                    Belum ada bukti pendukung.
                </div>

            @endif

        </div>

        <div class="form-group">

            <label>
                Temuan Auditor
            </label>

            <textarea
                class="form-control"
                rows="5"
                readonly
            >{{ $data->temuan->temuan ?? '-' }}</textarea>

        </div>

        <div class="form-group">

            <label for="tanggapan">
                Tanggapan Auditee
                <span class="required">*</span>
            </label>

            <textarea
                id="tanggapan"
                name="tanggapan"
                class="form-control"
                rows="7"
                placeholder="Tuliskan tanggapan terhadap temuan auditor..."
                required
            >{{ old(
                'tanggapan',
                $data->tanggapan
            ) }}</textarea>

            @error('tanggapan')

                <small class="error-message">
                    {{ $message }}
                </small>

            @enderror

        </div>

        <div class="form-footer">

            <a
                href="{{ route(
                    'auditee.temuan.show',
                    $data->id_temuan_ami
                ) }}"
                class="btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Batal
            </a>

            <button
                type="submit"
                class="btn-save"
            >
                <i class="bi bi-check-circle"></i>
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection