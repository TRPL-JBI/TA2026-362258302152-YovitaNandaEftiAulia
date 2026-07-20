@extends('layouts.auditor')

@section('content')

<div class="breadcrumb">
    Dashboard / Audit Mutu Internal / Tambah Temuan Audit
</div>

<div class="card">

    <div class="card-header periode-header">

        <div class="header-left">

            <h2 class="card-title">
                Tambah Temuan Audit
            </h2>

            <p>
                Pilih hasil penerapan standar auditee yang akan
                diberikan temuan audit.
            </p>

        </div>

    </div>

    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Data belum dapat disimpan.
            </strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

    <form
        action="{{ route('auditor.temuan.store') }}"
        method="POST"
    >

        @csrf

        <div class="form-group">

            <label for="id_penerapan_standar">
                Penerapan Standar
                <span class="required">*</span>
            </label>

            <select
                id="id_penerapan_standar"
                name="id_penerapan_standar"
                class="form-control"
                required
            >

                <option value="">
                    -- Pilih Penerapan Standar --
                </option>

                @foreach($penerapan as $item)

                    @php
                        $periode =
                            $item->standarmutuPeriode
                                ->periodeAmi
                                ->tahun
                            ?? '-';

                        $unitKerja =
                            $item->standarmutuPeriode
                                ->periodeAmi
                                ->unitKerja
                                ->nama
                            ?? $item->standarmutuPeriode
                                ->periodeAmi
                                ->unitKerja
                                ->nama_unit_kerja
                            ?? '-';

                        $standarMutu =
                            $item->standarmutuPeriode
                                ->standarMutu
                                ->nama_standar_mutu
                            ?? '-';

                        $indikator =
                            $item->indikator->deskripsi
                            ?? $item->indikator->indikator
                            ?? '-';

                        $auditee =
                            $item->user->nama
                            ?? $item->user->name
                            ?? '-';
                    @endphp

                    <option
                        value="{{ $item->id }}"
                        @selected(
                            old('id_penerapan_standar')
                            == $item->id
                        )
                    >
                        {{ $periode }}
                        | {{ $unitKerja }}
                        | {{ $standarMutu }}
                        | {{ $indikator }}
                        | Auditee: {{ $auditee }}
                    </option>

                @endforeach

            </select>

            @error('id_penerapan_standar')
                <small class="error-message">
                    {{ $message }}
                </small>
            @enderror

            @if($penerapan->isEmpty())

                <small class="form-help">
                    Semua penerapan standar sudah mempunyai temuan
                    atau belum ada data penerapan standar.
                </small>

            @endif

        </div>

        <div class="form-group">

            <label for="temuan">
                Temuan Audit
                <span class="required">*</span>
            </label>

            <textarea
                id="temuan"
                name="temuan"
                rows="7"
                class="form-control"
                placeholder="Tuliskan temuan berdasarkan indikator, hasil penerapan, dan bukti auditee..."
                required
            >{{ old('temuan') }}</textarea>

            @error('temuan')
                <small class="error-message">
                    {{ $message }}
                </small>
            @enderror

        </div>

        <div class="form-group">

            <label for="status_temuan">
                Status Temuan
                <span class="required">*</span>
            </label>

            <select
                id="status_temuan"
                name="status_temuan"
                class="form-control"
                required
            >

                <option
                    value="open"
                    @selected(
                        old('status_temuan', 'open')
                        === 'open'
                    )
                >
                    Open
                </option>

                <option
                    value="closed"
                    @selected(
                        old('status_temuan')
                        === 'closed'
                    )
                >
                    Closed
                </option>

            </select>

        </div>

        <div class="form-footer">

            <a
                href="{{ route('auditor.temuan.index') }}"
                class="btn-cancel"
            >
                <i class="bi bi-arrow-left"></i>
                Batal
            </a>

            <button
                type="submit"
                class="btn-save"
                @disabled($penerapan->isEmpty())
            >
                <i class="bi bi-check-circle"></i>
                Simpan Temuan
            </button>

        </div>

    </form>

</div>

@endsection
