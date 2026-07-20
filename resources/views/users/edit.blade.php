@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Edit User
</h3>

<div class="form-card">

    {{-- =====================================================
         PESAN VALIDASI
    ====================================================== --}}

    @if($errors->any())

        <div
            style="
                margin-bottom: 20px;
                padding: 14px 16px;
                border: 1px solid #fecaca;
                border-radius: 10px;
                background: #fef2f2;
                color: #991b1b;
            "
        >
            <strong>
                Data belum dapat diperbarui:
            </strong>

            <ul
                style="
                    margin: 8px 0 0;
                    padding-left: 20px;
                "
            >
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
            'user.update',
            $data->id
        ) }}"
        method="POST"
        autocomplete="off"
    >
        @csrf
        @method('PUT')

        {{-- =================================================
             NAMA
        ================================================== --}}

        <div class="form-group">

            <label for="nama">
                Nama
            </label>

            <input
                type="text"
                name="nama"
                id="nama"
                value="{{ old('nama', $data->nama) }}"
                maxlength="100"
                autocomplete="off"
                required
            >

            @error('nama')
                <small
                    style="
                        display: block;
                        margin-top: 6px;
                        color: #dc2626;
                    "
                >
                    {{ $message }}
                </small>
            @enderror

        </div>

        {{-- =================================================
             EMAIL
        ================================================== --}}

        <div class="form-group">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email', $data->email) }}"
                maxlength="150"
                autocomplete="off"
                required
            >

            @error('email')
                <small
                    style="
                        display: block;
                        margin-top: 6px;
                        color: #dc2626;
                    "
                >
                    {{ $message }}
                </small>
            @enderror

        </div>

        {{-- =================================================
             PASSWORD BARU
        ================================================== --}}

        <div class="form-group">

            <label for="password">
                Password Baru
            </label>

            <div
                style="
                    position: relative;
                "
            >
                <input
                    type="password"
                    name="password"
                    id="password"
                    minlength="8"
                    maxlength="255"
                    placeholder="Kosongkan jika password tidak diubah"
                    autocomplete="new-password"
                    style="
                        width: 100%;
                        padding-right: 48px;
                    "
                >

                <button
                    type="button"
                    id="togglePassword"
                    aria-label="Tampilkan password"
                    style="
                        position: absolute;
                        top: 50%;
                        right: 12px;
                        transform: translateY(-50%);
                        border: 0;
                        background: transparent;
                        cursor: pointer;
                        color: #64748b;
                        font-size: 18px;
                    "
                >
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            @error('password')
                <small
                    style="
                        display: block;
                        margin-top: 6px;
                        color: #dc2626;
                    "
                >
                    {{ $message }}
                </small>
            @enderror

            <div
                style="
                    margin-top: 10px;
                    padding: 12px 14px;
                    border: 1px solid #dbeafe;
                    border-radius: 10px;
                    background: #eff6ff;
                    color: #475569;
                    font-size: 12px;
                    line-height: 1.7;
                "
            >
                <strong
                    style="
                        display: block;
                        margin-bottom: 5px;
                        color: #1e3a8a;
                    "
                >
                    Ketentuan password baru:
                </strong>

                <div>
                    Kosongkan kedua field password jika password tidak ingin diubah.
                </div>

                <div id="ruleLength">
                    ○ Minimal 8 karakter
                </div>

                <div id="ruleUppercase">
                    ○ Minimal satu huruf besar
                </div>

                <div id="ruleLowercase">
                    ○ Minimal satu huruf kecil
                </div>

                <div id="ruleNumber">
                    ○ Minimal satu angka
                </div>

                <div id="ruleSymbol">
                    ○ Minimal satu simbol
                </div>
            </div>

        </div>

        {{-- =================================================
             KONFIRMASI PASSWORD BARU
        ================================================== --}}

        <div class="form-group">

            <label for="password_confirmation">
                Konfirmasi Password Baru
            </label>

            <div
                style="
                    position: relative;
                "
            >
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    minlength="8"
                    maxlength="255"
                    placeholder="Ulangi password baru"
                    autocomplete="new-password"
                    style="
                        width: 100%;
                        padding-right: 48px;
                    "
                >

                <button
                    type="button"
                    id="togglePasswordConfirmation"
                    aria-label="Tampilkan konfirmasi password"
                    style="
                        position: absolute;
                        top: 50%;
                        right: 12px;
                        transform: translateY(-50%);
                        border: 0;
                        background: transparent;
                        cursor: pointer;
                        color: #64748b;
                        font-size: 18px;
                    "
                >
                    <i class="bi bi-eye"></i>
                </button>
            </div>

            <small
                id="passwordMatchMessage"
                style="
                    display: block;
                    margin-top: 7px;
                    color: #64748b;
                "
            >
                Kosongkan jika password tidak diubah.
            </small>

        </div>

        {{-- =================================================
             ROLE
        ================================================== --}}

        <div class="form-group">

            <label for="role">
                Role
            </label>

            <select
                name="role"
                id="role"
                required
            >
                <option
                    value="admin"
                    {{
                        old('role', $data->role) === 'admin'
                            ? 'selected'
                            : ''
                    }}
                >
                    Admin
                </option>

                <option
                    value="auditor"
                    {{
                        old('role', $data->role) === 'auditor'
                            ? 'selected'
                            : ''
                    }}
                >
                    Auditor
                </option>

                <option
                    value="auditee"
                    {{
                        old('role', $data->role) === 'auditee'
                            ? 'selected'
                            : ''
                    }}
                >
                    Auditee
                </option>
            </select>

            @error('role')
                <small
                    style="
                        display: block;
                        margin-top: 6px;
                        color: #dc2626;
                    "
                >
                    {{ $message }}
                </small>
            @enderror

        </div>

        {{-- =================================================
             UNIT KERJA
        ================================================== --}}

        <div class="form-group">

            <label for="id_unit_kerja">
                Unit Kerja
            </label>

            <select
                name="id_unit_kerja"
                id="id_unit_kerja"
                required
            >
                <option value="">
                    Pilih Unit Kerja
                </option>

                @foreach($unit as $u)

                    <option
                        value="{{ $u->id }}"
                        {{
                            (string) old(
                                'id_unit_kerja',
                                $data->id_unit_kerja
                            )
                            === (string) $u->id
                                ? 'selected'
                                : ''
                        }}
                    >
                        {{ $u->nama }}
                    </option>

                @endforeach
            </select>

            @error('id_unit_kerja')
                <small
                    style="
                        display: block;
                        margin-top: 6px;
                        color: #dc2626;
                    "
                >
                    {{ $message }}
                </small>
            @enderror

        </div>

        {{-- =================================================
             STATUS
        ================================================== --}}

        <div class="form-group">

            <label for="status">
                Status
            </label>

            <select
                name="status"
                id="status"
                required
            >
                <option
                    value="aktif"
                    {{
                        old('status', $data->status) === 'aktif'
                            ? 'selected'
                            : ''
                    }}
                >
                    Aktif
                </option>

                <option
                    value="nonaktif"
                    {{
                        old('status', $data->status) === 'nonaktif'
                            ? 'selected'
                            : ''
                    }}
                >
                    Nonaktif
                </option>
            </select>

            @error('status')
                <small
                    style="
                        display: block;
                        margin-top: 6px;
                        color: #dc2626;
                    "
                >
                    {{ $message }}
                </small>
            @enderror

        </div>

        {{-- =================================================
             TOMBOL
        ================================================== --}}

        <div
            class="form-action"
            style="
                display: flex;
                justify-content: flex-end;
                gap: 10px;
            "
        >
            <a
                href="{{ route('user.index') }}"
                style="
                    display: inline-flex;
                    min-height: 42px;
                    align-items: center;
                    justify-content: center;
                    padding: 0 18px;
                    border: 1px solid #cbd5e1;
                    border-radius: 9px;
                    background: #ffffff;
                    color: #475569;
                    font-weight: 700;
                    text-decoration: none;
                "
            >
                Batal
            </a>

            <button
                type="submit"
                class="btn-save"
            >
                Update
            </button>
        </div>

    </form>

</div>

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const password =
                document.getElementById('password');

            const confirmation =
                document.getElementById(
                    'password_confirmation'
                );

            const togglePassword =
                document.getElementById(
                    'togglePassword'
                );

            const toggleConfirmation =
                document.getElementById(
                    'togglePasswordConfirmation'
                );

            const matchMessage =
                document.getElementById(
                    'passwordMatchMessage'
                );

            const rules = {
                length:
                    document.getElementById('ruleLength'),

                uppercase:
                    document.getElementById('ruleUppercase'),

                lowercase:
                    document.getElementById('ruleLowercase'),

                number:
                    document.getElementById('ruleNumber'),

                symbol:
                    document.getElementById('ruleSymbol'),
            };

            function toggleVisibility(
                input,
                button
            ) {
                const hidden =
                    input.type === 'password';

                input.type =
                    hidden
                        ? 'text'
                        : 'password';

                button.innerHTML =
                    hidden
                        ? '<i class="bi bi-eye-slash"></i>'
                        : '<i class="bi bi-eye"></i>';
            }

            function setRuleState(
                element,
                valid,
                text
            ) {
                element.textContent =
                    `${valid ? '✓' : '○'} ${text}`;

                element.style.color =
                    valid
                        ? '#15803d'
                        : '#64748b';

                element.style.fontWeight =
                    valid
                        ? '700'
                        : '400';
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
                const value =
                    password.value;

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

                    matchMessage.style.color =
                        '#64748b';

                    return;
                }

                if (
                    password.value !== ''
                    && confirmation.value === ''
                ) {
                    matchMessage.textContent =
                        'Masukkan konfirmasi password baru.';

                    matchMessage.style.color =
                        '#d97706';

                    return;
                }

                const match =
                    password.value
                    === confirmation.value;

                matchMessage.textContent =
                    match
                        ? '✓ Konfirmasi password sesuai.'
                        : '✕ Konfirmasi password tidak sama.';

                matchMessage.style.color =
                    match
                        ? '#15803d'
                        : '#dc2626';
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
        }
    );
</script>

@endsection