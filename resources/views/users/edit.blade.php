@extends('layouts.app')

@section('content')

<div class="user-form-page">

    <div class="user-breadcrumb">
        <a href="{{ route('user.index') }}">
            Dashboard
        </a>

        <i class="bi bi-chevron-right"></i>

        <strong>Edit User</strong>
    </div>

    @if ($errors->any())
        <div class="user-alert user-alert-danger">
            <div class="user-alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>

            <div class="user-alert-content">
                <strong>Data belum dapat diperbarui</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <button
                type="button"
                class="user-alert-close"
                onclick="this.parentElement.remove()"
            >
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    <div class="user-form-card">

        <div class="user-form-header">
            <div class="user-form-header-icon">
                <i class="bi bi-person-gear"></i>
            </div>

            <div>
                <h2>Edit User</h2>

                <p>
                    Perbarui data user dan Unit Kerja yang menjadi
                    tanggung jawabnya.
                </p>
            </div>
        </div>

        <form
            action="{{ route('user.update', $data->id) }}"
            method="POST"
            autocomplete="off"
        >
            @csrf
            @method('PUT')

            <div class="user-form-grid">

                {{-- NAMA --}}
                <div class="user-form-group">
                    <label for="nama">
                        Nama
                        <span class="required-mark">*</span>
                    </label>

                    <div class="user-input-wrapper">
                        <i class="bi bi-person user-input-icon"></i>

                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            value="{{ old('nama', $data->nama) }}"
                            class="@error('nama') is-invalid @enderror"
                            maxlength="100"
                            autocomplete="off"
                            required
                        >
                    </div>

                    @error('nama')
                        <small class="user-error-message">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="user-form-group">
                    <label for="email">
                        Email
                        <span class="required-mark">*</span>
                    </label>

                    <div class="user-input-wrapper">
                        <i class="bi bi-envelope user-input-icon"></i>

                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email', $data->email) }}"
                            class="@error('email') is-invalid @enderror"
                            maxlength="150"
                            autocomplete="off"
                            required
                        >
                    </div>

                    @error('email')
                        <small class="user-error-message">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="user-form-group">
                    <label for="password">
                        Password Baru
                    </label>

                    <div class="user-input-wrapper">
                        <i class="bi bi-lock user-input-icon"></i>

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="@error('password') is-invalid @enderror"
                            minlength="8"
                            maxlength="255"
                            placeholder="Kosongkan jika tidak diubah"
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            id="togglePassword"
                            class="user-password-toggle"
                            aria-label="Tampilkan password"
                        >
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    @error('password')
                        <small class="user-error-message">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                {{-- KONFIRMASI PASSWORD --}}
                <div class="user-form-group">
                    <label for="password_confirmation">
                        Konfirmasi Password Baru
                    </label>

                    <div class="user-input-wrapper">
                        <i class="bi bi-lock-fill user-input-icon"></i>

                        <input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            minlength="8"
                            maxlength="255"
                            placeholder="Ulangi password baru"
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            id="togglePasswordConfirmation"
                            class="user-password-toggle"
                            aria-label="Tampilkan konfirmasi password"
                        >
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    <small
                        id="passwordMatchMessage"
                        class="password-match-message"
                    >
                        Kosongkan jika password tidak diubah.
                    </small>
                </div>

            </div>

            {{-- ATURAN PASSWORD --}}
            <div class="password-rule-box">
                <strong>Ketentuan password baru</strong>

                <p>
                    Kosongkan kedua field password apabila password
                    tidak ingin diubah.
                </p>

                <div class="password-rule-list">
                    <span id="ruleLength">
                        ○ Minimal 8 karakter
                    </span>

                    <span id="ruleUppercase">
                        ○ Minimal satu huruf besar
                    </span>

                    <span id="ruleLowercase">
                        ○ Minimal satu huruf kecil
                    </span>

                    <span id="ruleNumber">
                        ○ Minimal satu angka
                    </span>

                    <span id="ruleSymbol">
                        ○ Minimal satu simbol
                    </span>
                </div>
            </div>

            <div class="user-form-grid">

                {{-- ROLE --}}
                <div class="user-form-group">
                    <label for="role">
                        Role
                        <span class="required-mark">*</span>
                    </label>

                    <div class="user-input-wrapper">
                        <i class="bi bi-person-badge user-input-icon"></i>

                        <select
                            name="role"
                            id="role"
                            class="@error('role') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                -- Pilih Role --
                            </option>

                            <option
                                value="admin"
                                @selected(
                                    old('role', $data->role) === 'admin'
                                )
                            >
                                Admin
                            </option>

                            <option
                                value="auditor"
                                @selected(
                                    old('role', $data->role) === 'auditor'
                                )
                            >
                                Auditor
                            </option>

                            <option
                                value="auditee"
                                @selected(
                                    old('role', $data->role) === 'auditee'
                                )
                            >
                                Auditee / Kepala Unit
                            </option>
                        </select>
                    </div>

                    @error('role')
                        <small class="user-error-message">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </small>
                    @enderror
                </div>

                {{-- STATUS --}}
                <div class="user-form-group">
                    <label for="status">
                        Status
                        <span class="required-mark">*</span>
                    </label>

                    <div class="user-input-wrapper">
                        <i class="bi bi-toggle-on user-input-icon"></i>

                        <select
                            name="status"
                            id="status"
                            class="@error('status') is-invalid @enderror"
                            required
                        >
                            <option
                                value="aktif"
                                @selected(
                                    old('status', $data->status) === 'aktif'
                                )
                            >
                                Aktif
                            </option>

                            <option
                                value="nonaktif"
                                @selected(
                                    old('status', $data->status) === 'nonaktif'
                                )
                            >
                                Nonaktif
                            </option>
                        </select>
                    </div>

                    @error('status')
                        <small class="user-error-message">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </small>
                    @enderror
                </div>

            </div>

            @php
                $unitDipilih = old(
                    'unit_kerja_ids',
                    $unitKerjaTerpilih ?? []
                );

                $unitDipilih = array_map(
                    'intval',
                    (array) $unitDipilih
                );
            @endphp

            {{-- CHECKLIST UNIT KERJA --}}
            <div
                id="unitKerjaSection"
                class="unit-check-section"
            >
                <div class="unit-check-header">
                    <div>
                        <h3>
                            Unit Kerja yang Ditangani
                        </h3>

                        <p>
                            Pilih satu atau beberapa Unit Kerja yang
                            ditangani user sebagai Kepala Unit.
                        </p>
                    </div>

                    <span
                        id="selectedUnitCount"
                        class="unit-selected-count"
                    >
                        0 Unit dipilih
                    </span>
                </div>

                <label class="unit-check-all">
                    <input
                        type="checkbox"
                        id="checkAllUnit"
                    >

                    <span>
                        Pilih Semua Unit Kerja
                    </span>
                </label>

                <div class="unit-check-grid">

                    @forelse ($unit as $u)

                        @php
                            $sedangDipakaiUserLain =
                                !is_null($u->id_user)
                                && (int) $u->id_user !== (int) $data->id;
                        @endphp

                        <label
                            class="unit-check-item
                                {{ $sedangDipakaiUserLain
                                    ? 'unit-check-used'
                                    : '' }}"
                        >
                            <input
                                type="checkbox"
                                name="unit_kerja_ids[]"
                                value="{{ $u->id }}"
                                class="unit-checkbox"
                                @checked(
                                    in_array(
                                        (int) $u->id,
                                        $unitDipilih,
                                        true
                                    )
                                )
                            >

                            <span class="unit-check-box-icon">
                                <i class="bi bi-check"></i>
                            </span>

                            <span class="unit-check-information">
                                <strong>
                                    {{ $u->nama }}
                                </strong>

                                <small>
                                    {{ $u->kategori_unit_kerja }}
                                </small>

                                @if ($sedangDipakaiUserLain)
                                    <em>
                                        Saat ini ditangani oleh
                                        {{ $u->kepalaUnit?->nama ?? 'user lain' }}.
                                        Memilih unit ini akan memindahkan Kepala Unit.
                                    </em>
                                @elseif ((int) $u->id_user === (int) $data->id)
                                    <em class="unit-current-owner">
                                        Sedang ditangani user ini
                                    </em>
                                @endif
                            </span>
                        </label>

                    @empty

                        <div class="unit-empty-message">
                            <i class="bi bi-building-x"></i>

                            <span>
                                Data Unit Kerja belum tersedia.
                            </span>
                        </div>

                    @endforelse

                </div>

                @error('unit_kerja_ids')
                    <small class="user-error-message">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </small>
                @enderror

                @error('unit_kerja_ids.*')
                    <small class="user-error-message">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="user-form-actions">
                <a
                    href="{{ route('user.index') }}"
                    class="user-btn-cancel"
                >
                    <i class="bi bi-x-circle"></i>
                    Batal
                </a>

                <button
                    type="submit"
                    class="user-btn-save"
                >
                    <i class="bi bi-check-circle"></i>
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const role = document.getElementById('role');
        const unitSection = document.getElementById('unitKerjaSection');
        const checkAllUnit = document.getElementById('checkAllUnit');
        const unitCheckboxes = Array.from(
            document.querySelectorAll('.unit-checkbox')
        );
        const selectedCount = document.getElementById('selectedUnitCount');

        const password = document.getElementById('password');
        const confirmation = document.getElementById(
            'password_confirmation'
        );
        const togglePassword = document.getElementById(
            'togglePassword'
        );
        const toggleConfirmation = document.getElementById(
            'togglePasswordConfirmation'
        );
        const matchMessage = document.getElementById(
            'passwordMatchMessage'
        );

        const rules = {
            length: document.getElementById('ruleLength'),
            uppercase: document.getElementById('ruleUppercase'),
            lowercase: document.getElementById('ruleLowercase'),
            number: document.getElementById('ruleNumber'),
            symbol: document.getElementById('ruleSymbol')
        };

        function updateUnitSection() {
            const auditeeSelected = role.value === 'auditee';

            unitSection.style.display = auditeeSelected
                ? 'block'
                : 'none';

            unitCheckboxes.forEach(function (checkbox) {
                checkbox.disabled = !auditeeSelected;
            });

            checkAllUnit.disabled = !auditeeSelected;

            updateSelectedCount();
        }

        function updateSelectedCount() {
            const checked = unitCheckboxes.filter(function (checkbox) {
                return checkbox.checked;
            }).length;

            selectedCount.textContent =
                checked + (checked === 1
                    ? ' Unit dipilih'
                    : ' Unit dipilih');

            checkAllUnit.checked =
                unitCheckboxes.length > 0
                && checked === unitCheckboxes.length;

            checkAllUnit.indeterminate =
                checked > 0
                && checked < unitCheckboxes.length;
        }

        checkAllUnit.addEventListener('change', function () {
            unitCheckboxes.forEach(function (checkbox) {
                checkbox.checked = checkAllUnit.checked;
            });

            updateSelectedCount();
        });

        unitCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener(
                'change',
                updateSelectedCount
            );
        });

        role.addEventListener('change', updateUnitSection);

        function toggleVisibility(input, button) {
            const hidden = input.type === 'password';

            input.type = hidden ? 'text' : 'password';

            button.innerHTML = hidden
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';
        }

        function setRuleState(element, valid, text) {
            element.textContent =
                (valid ? '✓ ' : '○ ') + text;

            element.classList.toggle(
                'rule-valid',
                valid
            );
        }

        function resetRules() {
            setRuleState(
                rules.length,
                false,
                'Minimal 8 karakter'
            );

            setRuleState(
                rules.uppercase,
                false,
                'Minimal satu huruf besar'
            );

            setRuleState(
                rules.lowercase,
                false,
                'Minimal satu huruf kecil'
            );

            setRuleState(
                rules.number,
                false,
                'Minimal satu angka'
            );

            setRuleState(
                rules.symbol,
                false,
                'Minimal satu simbol'
            );
        }

        function validatePasswordRules() {
            const value = password.value;

            if (value === '') {
                resetRules();
                validatePasswordMatch();
                return;
            }

            setRuleState(
                rules.length,
                value.length >= 8,
                'Minimal 8 karakter'
            );

            setRuleState(
                rules.uppercase,
                /[A-Z]/.test(value),
                'Minimal satu huruf besar'
            );

            setRuleState(
                rules.lowercase,
                /[a-z]/.test(value),
                'Minimal satu huruf kecil'
            );

            setRuleState(
                rules.number,
                /[0-9]/.test(value),
                'Minimal satu angka'
            );

            setRuleState(
                rules.symbol,
                /[^A-Za-z0-9]/.test(value),
                'Minimal satu simbol'
            );

            validatePasswordMatch();
        }

        function validatePasswordMatch() {
            if (
                password.value === ''
                && confirmation.value === ''
            ) {
                matchMessage.textContent =
                    'Kosongkan jika password tidak diubah.';

                matchMessage.className =
                    'password-match-message';

                return;
            }

            if (
                password.value !== ''
                && confirmation.value === ''
            ) {
                matchMessage.textContent =
                    'Masukkan konfirmasi password baru.';

                matchMessage.className =
                    'password-match-message password-warning';

                return;
            }

            const match =
                password.value === confirmation.value;

            matchMessage.textContent = match
                ? '✓ Konfirmasi password sesuai.'
                : '✕ Konfirmasi password tidak sama.';

            matchMessage.className = match
                ? 'password-match-message password-success'
                : 'password-match-message password-danger';
        }

        togglePassword.addEventListener(
            'click',
            function () {
                toggleVisibility(
                    password,
                    togglePassword
                );
            }
        );

        toggleConfirmation.addEventListener(
            'click',
            function () {
                toggleVisibility(
                    confirmation,
                    toggleConfirmation
                );
            }
        );

        password.addEventListener(
            'input',
            validatePasswordRules
        );

        confirmation.addEventListener(
            'input',
            validatePasswordMatch
        );

        resetRules();
        updateUnitSection();
    });
</script>

@endsection