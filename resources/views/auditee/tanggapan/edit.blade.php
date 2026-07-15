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

    <form
        action="{{ route('auditee.tanggapan.update',$data->id) }}"
        method="POST">

        @csrf

        @method('PUT')

        <div class="form-group">

            <label>

                Pertanyaan AMI

            </label>

            <input
                type="text"
                class="form-control"
                value="{{ $data->temuan->pertanyaan->pertanyaan }}"
                readonly>

        </div>

        <div class="form-group">

            <label>

                Temuan Auditor

            </label>

            <textarea
                class="form-control"
                rows="5"
                readonly>{{ $data->temuan->temuan }}</textarea>

        </div>

        <div class="form-group">

            <label>

                Status Temuan

            </label>

            <input
                type="text"
                class="form-control"
                value="{{ $data->temuan->status_temuan }}"
                readonly>

        </div>

        <div class="form-group">

            <label>

                Tanggapan Auditee

            </label>

            <textarea
                name="tanggapan"
                rows="8"
                class="form-control"
                required>{{ old('tanggapan',$data->tanggapan) }}</textarea>

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

                Simpan Perubahan

            </button>

        </div>

    </form>

</div>

@endsection