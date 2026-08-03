@extends('layouts.app')

@section('content')

<style>
    .delete-page {
        width: 100%;
    }

    .delete-breadcrumb {
        margin-bottom: 24px;
        color: #1e293b;
        font-size: 15px;
        font-weight: 700;
    }

    .delete-tab-menu {
        display: flex;
        align-items: center;
        gap: 38px;
        margin-bottom: 42px;
        padding-bottom: 1px;
    }

    .delete-tab-menu a {
        position: relative;
        padding: 8px 0 14px;
        color: #334155;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: 0.2s ease;
    }

    .delete-tab-menu a:hover {
        color: #4f46e5;
    }

    .delete-tab-menu a.active {
        color: #4f46e5;
        font-weight: 700;
    }

    .delete-tab-menu a.active::after {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 2px;
        border-radius: 4px;
        background: #4f46e5;
        content: "";
    }

    .delete-container {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .delete-card {
        width: 100%;
        max-width: 900px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
    }

    .delete-card-header {
        padding: 34px 30px 24px;
        text-align: center;
    }

    .delete-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        margin-bottom: 18px;
        border-radius: 50%;
        background: #fef2f2;
        color: #dc2626;
        font-size: 28px;
    }

    .delete-title {
        margin: 0 0 10px;
        color: #0f172a;
        font-size: 28px;
        font-weight: 800;
    }

    .delete-description {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.7;
    }

    .delete-content {
        padding: 10px 42px 34px;
    }

    .delete-table-wrapper {
        overflow: hidden;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .delete-detail-table {
        width: 100%;
        border-collapse: collapse;
    }

    .delete-detail-table th,
    .delete-detail-table td {
        padding: 18px 20px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
        line-height: 1.6;
        vertical-align: middle;
    }

    .delete-detail-table tr:last-child th,
    .delete-detail-table tr:last-child td {
        border-bottom: none;
    }

    .delete-detail-table th {
        width: 210px;
        border-right: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        text-align: left;
    }

    .delete-detail-table td {
        background: #ffffff;
        color: #0f172a;
        font-weight: 500;
    }

    .delete-warning {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-top: 20px;
        padding: 14px 16px;
        border: 1px solid #fed7aa;
        border-radius: 10px;
        background: #fff7ed;
        color: #9a3412;
        font-size: 13px;
        line-height: 1.6;
    }

    .delete-warning i {
        margin-top: 2px;
        font-size: 17px;
    }

    .delete-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 26px;
    }

    .delete-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-width: 130px;
        min-height: 44px;
        padding: 10px 20px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
        transition: 0.2s ease;
    }

    .delete-button-cancel {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
    }

    .delete-button-cancel:hover {
        border-color: #94a3b8;
        background: #f8fafc;
        color: #1e293b;
    }

    .delete-button-danger {
        border: 1px solid #dc2626;
        background: #dc2626;
        color: #ffffff;
    }

    .delete-button-danger:hover {
        border-color: #b91c1c;
        background: #b91c1c;
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .delete-tab-menu {
            gap: 22px;
            overflow-x: auto;
            white-space: nowrap;
        }

        .delete-content {
            padding: 10px 20px 26px;
        }

        .delete-detail-table th,
        .delete-detail-table td {
            display: block;
            width: 100%;
            box-sizing: border-box;
        }

        .delete-detail-table th {
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
        }

        .delete-actions {
            flex-direction: column-reverse;
        }

        .delete-button {
            width: 100%;
        }
    }
</style>

<div class="delete-page">

    <div class="delete-breadcrumb">
        Dashboard / Detail Periode AMI / Hapus Jadwal
    </div>

    <div class="delete-tab-menu">

        <a href="{{ route(
            'periode-ami.show',
            $jadwal->id_periode_ami
        ) }}">
            Detail Periode AMI
        </a>

        <a href="{{ route(
            'penerapan.index',
            $jadwal->id_periode_ami
        ) }}">
            Penerapan Standar
        </a>

        <a href="{{ route(
            'tim-ami.index',
            $jadwal->id_periode_ami
        ) }}">
            Tim AMI
        </a>

        <a
            href="{{ route(
                'jadwal.index',
                $jadwal->id_periode_ami
            ) }}"
            class="active"
        >
            Jadwal AMI
        </a>

    </div>

    <div class="delete-container">

        <div class="delete-card">

            <div class="delete-card-header">

                <div class="delete-icon">
                    <i class="bi bi-trash3"></i>
                </div>

                <h2 class="delete-title">
                    Hapus Jadwal AMI
                </h2>

                <p class="delete-description">
                    Pastikan kembali data jadwal sebelum menghapusnya.
                </p>

            </div>

            <div class="delete-content">

                <div class="delete-table-wrapper">

                    <table class="delete-detail-table">

                        <tbody>

                            <tr>

                                <th>
                                    Nama Kegiatan
                                </th>

                                <td>
                                    {{ $jadwal->kegiatan ?? '-' }}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Waktu
                                </th>

                                <td>
                                    {{ $jadwal->waktu ?? '-' }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

                <div class="delete-warning">

                    <i class="bi bi-exclamation-triangle"></i>

                    <div>
                        Data yang sudah dihapus tidak dapat dikembalikan.
                        Tekan tombol hapus hanya apabila data jadwal memang
                        sudah tidak digunakan.
                    </div>

                </div>

                <form
                    action="{{ route(
                        'jadwal.destroy',
                        $jadwal->id
                    ) }}"
                    method="POST"
                >

                    @csrf
                    @method('DELETE')

                    <div class="delete-actions">

                        <a
                            href="{{ route(
                                'jadwal.index',
                                $jadwal->id_periode_ami
                            ) }}"
                            class="delete-button delete-button-cancel"
                        >
                            <i class="bi bi-arrow-left"></i>

                            Batal
                        </a>

                        <button
                            type="submit"
                            class="delete-button delete-button-danger"
                            onclick="return confirm(
                                'Apakah Anda yakin ingin menghapus jadwal ini?'
                            )"
                        >
                            <i class="bi bi-trash3"></i>

                            Hapus Jadwal
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection