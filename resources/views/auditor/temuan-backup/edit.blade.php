@extends('layouts.auditor')

@section('content')

<div class="breadcrumb">
    Dashboard / Audit Mutu Internal / Edit Temuan Audit
</div>

<div class="card">

    <div class="card-header">

        <h2 class="card-title">
            Edit Temuan Audit
        </h2>

    </div>

    @if($errors->any())

        <div class="alert alert-danger">

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

    <form
        action="{{ route(
            'auditor.temuan.update',
            $temuan->id
        ) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <div class="form-group">

            <label for="id_penerapan_standar">
                Penerapan Standar
            </label>

            <select
                id="id_penerapan_standar"
                name="id_penerapan_standar"
                class="form-control"
                required
            >

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

                        $indikator =
                            $item->indikator->deskripsi
                            ?? $item->indikator->indikator
                            ?? '-';
                    @endphp

                    <option
                        value="{{ $item->id }}"
                        @selected(
                            old(
                                'id_penerapan_standar',
                                $temuan->id_penerapan_standar
                            ) == $item->id
                        )
                    >
                        {{ $periode }}
                        | {{ $unitKerja }}
                        | {{ $indikator }}
                    </option>

                @endforeach

            </select>

        </div>

        <div class="form-group">

            <label for="temuan">
                Temuan Audit
            </label>

            <textarea
                id="temuan"
                name="temuan"
                rows="7"
                class="form-control"
                required
            >{{ old('temuan', $temuan->temuan) }}</textarea>

        </div>

        <div class="form-group">

            <label for="status_temuan">
                Status Temuan
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
                        strtolower(
                            old(
                                'status_temuan',
                                $temuan->status_temuan
                            )
                        ) === 'open'
                    )
                >
                    Open
                </option>

                <option
                    value="closed"
                    @selected(
                        strtolower(
                            old(
                                'status_temuan',
                                $temuan->status_temuan
                            )
                        ) === 'closed'
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
            >
                <i class="bi bi-check-circle"></i>
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

@endsection
