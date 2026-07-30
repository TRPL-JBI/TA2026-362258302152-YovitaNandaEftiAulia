@extends('layouts.app')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('css/app/17-admin-unit-form.css') }}"
    >
@endpush

@section('content')

<div class="unit-form-page">

    <div class="unit-form-breadcrumb">
        <a href="{{ route('dashboard') }}">
            Dashboard
        </a>

        <span>/</span>

        <a href="{{ route('unit-kerja.index') }}">
            Unit Kerja
        </a>

        <span>/</span>

        <strong>
            Edit Data
        </strong>
    </div>

    <div class="unit-form-card">

        <div class="unit-form-header">
            <h1>
                Form Edit Data
            </h1>
        </div>

        @if ($errors->any())
            <div class="unit-form-alert">
                <i class="bi bi-exclamation-triangle-fill"></i>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('unit-kerja.update', $data->id) }}"
            method="POST"
            id="unitAssignmentForm"
        >
            @csrf
            @method('PUT')

            <input
                type="hidden"
                name="id_user_lama"
                value="{{ old('id_user_lama', $idUserLama) }}"
            >

            <div class="unit-form-group">

                <label for="id_user">
                    Nama User
                    <span>*</span>
                </label>

                <select
                    name="id_user"
                    id="id_user"
                    required
                >
                    <option value="">
                        -- Pilih Nama User --
                    </option>

                    @foreach ($users as $user)
                        <option
                            value="{{ $user->id }}"
                            @selected(
                                (string) old(
                                    'id_user',
                                    $idUserLama
                                )
                                === (string) $user->id
                            )
                        >
                            {{ $user->nama }} — {{ $user->email }}
                        </option>
                    @endforeach
                </select>

                @error('id_user')
                    <small class="unit-form-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>

            @php
                $unitEditTerpilih = array_map(
                    'intval',
                    (array) old(
                        'unit_kerja_ids',
                        $unitTerpilih
                    )
                );
            @endphp

            <div class="unit-form-group">

                <div class="unit-form-label-row">

                    <div>
                        <label>
                            Unit Kerja
                            <span>*</span>
                        </label>

                        <small>
                            Pilih satu atau beberapa Unit Kerja.
                        </small>
                    </div>

                    <strong id="jumlahDipilih">
                        0 Unit dipilih
                    </strong>

                </div>

                <label class="unit-form-check-all">
                    <input
                        type="checkbox"
                        id="pilihSemua"
                    >

                    <span>
                        Pilih Semua Unit Kerja
                    </span>
                </label>

                <div class="unit-form-checklist">

                    @forelse ($unitKerja as $unit)

                        <label class="unit-form-check-item">

                            <input
                                type="checkbox"
                                name="unit_kerja_ids[]"
                                value="{{ $unit->id }}"
                                class="unit-checkbox"
                                data-owner="{{ $unit->id_user ?? '' }}"
                                @checked(
                                    in_array(
                                        (int) $unit->id,
                                        $unitEditTerpilih,
                                        true
                                    )
                                )
                            >

                            <span class="unit-form-checkbox">
                                <i class="bi bi-check-lg"></i>
                            </span>

                            <span class="unit-form-check-content">

                                <strong>
                                    {{ $unit->nama }}
                                </strong>

                                <span>
                                    {{ $unit->kategori_unit_kerja }}
                                </span>

                                @if ($unit->kepalaUnit)
                                    <small>
                                        Saat ini:
                                        {{ $unit->kepalaUnit->nama }}
                                    </small>
                                @else
                                    <small>
                                        Belum memiliki user
                                    </small>
                                @endif

                            </span>

                        </label>

                    @empty

                        <div class="unit-form-empty">
                            Data Unit Kerja belum tersedia.
                        </div>

                    @endforelse

                </div>

                @error('unit_kerja_ids')
                    <small class="unit-form-error">
                        {{ $message }}
                    </small>
                @enderror

                @error('unit_kerja_ids.*')
                    <small class="unit-form-error">
                        {{ $message }}
                    </small>
                @enderror

            </div>

            <div class="unit-form-actions">

                <button
                    type="submit"
                    class="unit-form-btn unit-form-btn-save"
                >
                    Simpan Perubahan
                </button>

                <a
                    href="{{ route('unit-kerja.index') }}"
                    class="unit-form-btn unit-form-btn-cancel"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pilihSemua =
            document.getElementById('pilihSemua');

        const jumlahDipilih =
            document.getElementById('jumlahDipilih');

        const checkboxes = Array.from(
            document.querySelectorAll('.unit-checkbox')
        );

        function updateJumlah() {
            const jumlah = checkboxes.filter(function (checkbox) {
                return checkbox.checked;
            }).length;

            jumlahDipilih.textContent =
                jumlah + ' Unit dipilih';

            pilihSemua.checked =
                checkboxes.length > 0 &&
                jumlah === checkboxes.length;

            pilihSemua.indeterminate =
                jumlah > 0 &&
                jumlah < checkboxes.length;
        }

        pilihSemua.addEventListener(
            'change',
            function () {
                checkboxes.forEach(function (checkbox) {
                    checkbox.checked =
                        pilihSemua.checked;
                });

                updateJumlah();
            }
        );

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener(
                'change',
                updateJumlah
            );
        });

        updateJumlah();
    });
</script>
@endpush