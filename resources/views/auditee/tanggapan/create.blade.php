@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">

    Dashboard / Temuan Audit / Beri Tanggapan

</div>

<div class="card">

    <div class="card-header">

        <h2 class="card-title">

            Form Tanggapan Auditee

        </h2>

    </div>

    <form
        action="{{ route('auditee.tanggapan.store',$temuan->id) }}"
        method="POST">

        @csrf

        <div class="form-group">

            <label>

                Pertanyaan AMI

            </label>

            <input
                type="text"
                class="form-control"
                value="{{ $temuan->pertanyaan->pertanyaan }}"
                readonly>

        </div>

        <div class="form-group">

            <label>

                Temuan Auditor

            </label>

            <textarea
                class="form-control"
                rows="5"
                readonly>{{ $temuan->temuan }}</textarea>

        </div>

        <div class="form-group">

            <label>

                Status Temuan

            </label>

            <input
                type="text"
                class="form-control"
                value="{{ $temuan->status_temuan }}"
                readonly>

        </div>

        <div class="form-group">

            <label>

                Tanggapan Auditee

            </label>

            <textarea
                name="tanggapan"
                class="form-control"
                rows="8"
                placeholder="Tuliskan tanggapan terhadap temuan auditor..."
                required>{{ old('tanggapan') }}</textarea>

        </div>

        <div class="form-footer">

            <a
                href="{{ route('auditee.temuan.index') }}"
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