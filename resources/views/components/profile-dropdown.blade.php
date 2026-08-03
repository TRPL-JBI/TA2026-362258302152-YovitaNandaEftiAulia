@php
    $sessionUser = $authUser;

    $nama = is_array($sessionUser)
        ? ($sessionUser['nama'] ?? 'Pengguna')
        : ($sessionUser->nama ?? 'Pengguna');

    $email = is_array($sessionUser)
        ? ($sessionUser['email'] ?? '-')
        : ($sessionUser->email ?? '-');

    $role = is_array($sessionUser)
        ? ($sessionUser['role'] ?? $sessionUser['status'] ?? 'pengguna')
        : ($sessionUser->role ?? $sessionUser->status ?? 'pengguna');

    $statusAkun = is_array($sessionUser)
        ? ($sessionUser['status'] ?? 'aktif')
        : ($sessionUser->status ?? 'aktif');

    $roleLower = strtolower(trim((string) $role));

    $roleLabel = match ($roleLower) {
        'admin' => 'Administrator',
        'auditor' => 'Auditor',
        'auditee' => 'Auditee',
        default => ucfirst($roleLower),
    };

    $roleDescription = match ($roleLower) {
        'admin' => 'Pengelola Sistem',
        'auditor' => 'Tim Audit Mutu Internal',
        'auditee' => 'Pelaksana Penerapan Standar',
        default => 'Pengguna Sistem',
    };

    $initial = strtoupper(
        substr(
            trim((string) $nama),
            0,
            1
        )
    );

    $statusLower = strtolower(
        trim((string) $statusAkun)
    );

    $isActive = in_array(
        $statusLower,
        ['aktif', 'active', 'admin', 'auditor', 'auditee'],
        true
    );
@endphp

<div class="spmi-profile-dropdown">

    <button
        type="button"
        class="spmi-profile-trigger"
        id="profileBtn"
        aria-label="Buka menu profil"
        aria-expanded="false"
    >

        <span class="spmi-profile-trigger-avatar">
            {{ $initial }}
        </span>

        <span class="spmi-profile-online-dot"></span>

    </button>

    <div
        class="spmi-profile-menu"
        id="profileMenu"
    >

        <div class="spmi-profile-arrow"></div>

        <div class="spmi-profile-header">

            <div class="spmi-profile-avatar-large">
                {{ $initial }}
            </div>

            <div class="spmi-profile-identity">

                <strong>
                    {{ $nama }}
                </strong>

                <span>
                    {{ $email }}
                </span>

            </div>

        </div>

        <div class="spmi-profile-role">

            <div class="spmi-profile-role-icon">

                @if($roleLower === 'admin')

                    <i class="bi bi-shield-lock-fill"></i>

                @elseif($roleLower === 'auditor')

                    <i class="bi bi-clipboard-check-fill"></i>

                @elseif($roleLower === 'auditee')

                    <i class="bi bi-person-check-fill"></i>

                @else

                    <i class="bi bi-person-fill"></i>

                @endif

            </div>

            <div class="spmi-profile-role-text">

                <strong>
                    {{ $roleLabel }}
                </strong>

                <span>
                    {{ $roleDescription }}
                </span>

            </div>

        </div>

        <div class="spmi-profile-status">

            <div>

                <span class="spmi-profile-status-label">
                    Status akun
                </span>

                <strong
                    class="{{
                        $isActive
                            ? 'status-active'
                            : 'status-inactive'
                    }}"
                >

                    <span class="spmi-profile-status-dot"></span>

                    {{
                        $isActive
                            ? 'Aktif'
                            : ucfirst($statusLower)
                    }}

                </strong>

            </div>

        </div>

        <div class="spmi-profile-divider"></div>

        <form
            action="{{ route('logout') }}"
            method="POST"
            class="spmi-profile-logout-form"
            onsubmit="
                return confirm(
                    'Apakah Anda yakin ingin keluar dari sistem?'
                );
            "
        >

            @csrf

            <button
                type="submit"
                class="spmi-profile-logout-button"
            >

                <span class="spmi-profile-logout-icon">
                    <i class="bi bi-box-arrow-right"></i>
                </span>

                <span>

                    <strong>
                        Keluar dari Sistem
                    </strong>

                    <small>
                        Akhiri sesi Anda
                    </small>

                </span>

            </button>

        </form>

    </div>

</div>