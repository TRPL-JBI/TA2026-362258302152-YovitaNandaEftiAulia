{{-- =========================================================
     DATA USER YANG SEDANG LOGIN
========================================================= --}}

@php
    /*
    |--------------------------------------------------------------------------
    | AMBIL USER DARI MIDDLEWARE
    |--------------------------------------------------------------------------
    |
    | Object user berasal dari CheckSession.php dan hanya tersedia
    | selama request berjalan. Object user tidak disimpan di session.
    |
    */

    $currentUser = $authUser ?? request()->attributes->get('auth_user');
@endphp

@if($currentUser)

    <div class="logged-user">

        {{-- FOTO / INISIAL USER --}}

        <div class="logged-user-avatar">
            {{ strtoupper(
                mb_substr(
                    $currentUser->nama ?? 'U',
                    0,
                    1
                )
            ) }}
        </div>

        {{-- INFORMASI USER --}}

        <div class="logged-user-information">

            <div class="logged-user-name">
                {{ $currentUser->nama ?? '-' }}
            </div>

            <div class="logged-user-email">
                {{ $currentUser->email ?? '-' }}
            </div>

            <div class="logged-user-detail">

                <span class="logged-user-role">
                    {{ ucfirst($currentUser->role ?? '-') }}
                </span>

                @if($currentUser->unit)

                    <span class="logged-user-separator">
                        •
                    </span>

                    <span class="logged-user-unit">
                        {{ $currentUser->unit->nama ?? '-' }}
                    </span>

                @endif

            </div>

        </div>

        {{-- TOMBOL LOGOUT --}}

        <form
            action="{{ route('logout') }}"
            method="POST"
            class="logged-user-logout-form"
        >
            @csrf

            <button
                type="submit"
                class="logged-user-logout-button"
                title="Keluar dari sistem"
                onclick="return confirm('Apakah Anda yakin ingin keluar?')"
            >
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>

    </div>

@endif