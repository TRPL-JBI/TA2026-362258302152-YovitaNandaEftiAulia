@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Tambah Periode AMI
</h3>

<div class="form-card">

    <h2 class="form-title">
        Tambah Periode AMI
    </h2>

    <form action="{{ route('periode-ami.store') }}"
          method="POST">

        @csrf

        <div class="form-grid">

            <!-- =========================
                 KIRI
            ========================== -->
            <div>

                <!-- TAHUN -->
                <div class="form-group">

                    <label>Tahun</label>

                    <select name="tahun" required>

                        <option value="">
                            -- Pilih Tahun --
                        </option>

                        @for($i = 2025; $i <= 2035; $i++)

                            <option value="{{ $i }}">
                                {{ $i }}
                            </option>

                        @endfor

                    </select>

                </div>

                <!-- STANDAR MUTU -->
                <div class="form-group">

                    <label>Standar Mutu</label>

                    <select name="id_standar_mutu" required>

                        <option value="">
                            -- Pilih Standar Mutu --
                        </option>

                        @foreach($standarMutu as $item)

                            <option value="{{ $item->id }}">
                                {{ $item->nama_standar_mutu }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <!-- UNIT KERJA -->
                <div class="form-group">

                    <label>Unit Kerja</label>

                    <select name="id_unit_kerja" required>

                        <option value="">
                            -- Pilih Unit Kerja --
                        </option>

                        @foreach($unitKerja as $item)

                            <option value="{{ $item->id }}">
                                {{ $item->nama }}
                            </option>

                        @endforeach

                    </select>

                </div>

                <!-- USER -->
                <div class="form-group">

                    <label>Ketua AMI / Pembuat</label>

                    <input type="text"
                           value="{{ session('user')['nama'] }}"
                           readonly>

                </div>

                <!-- TANGGAL -->
                <div class="date-wrapper">

                    <div class="form-group">

                        <label>Tanggal Buka Audit</label>

                        <input type="date"
                               name="tanggal_buka_ami"
                               required>

                    </div>

                    <div class="form-group">

                        <label>Tanggal Tutup Audit</label>

                        <input type="date"
                               name="tanggal_tutup_ami"
                               required>

                    </div>

                </div>

            </div>

            <!-- =========================
                 KANAN
            ========================== -->
            <div>

                <!-- TUJUAN -->
                <div class="form-group">

                    <label>Tujuan Audit</label>

                    <textarea name="tujuan_audit"
                              required></textarea>

                </div>

                <!-- LINGKUP -->
                <div class="form-group">

                    <label>Lingkup Audit</label>

                    <textarea name="lingkup_audit"
                              required></textarea>

                </div>

                <!-- WAKTU -->
                <div class="form-group">

                    <label>Waktu Audit</label>

                    <input type="text"
                           name="waktu_audit"
                           placeholder="08.00 - 15.00 WIB"
                           required>

                </div>

                    <!-- STATUS -->
                    <div class="form-group">

                        <label>Status</label>

                        <div class="status-wrapper">

                            <label class="status-item">
                                <input type="radio"
                                    name="status"
                                    value="draft"
                                    checked>

                                <span>Draft</span>
                            </label>

                            <label class="status-item">
                                <input type="radio"
                                    name="status"
                                    value="berjalan">

                                <span>Berjalan</span>
                            </label>

                            <label class="status-item">
                                <input type="radio"
                                    name="status"
                                    value="ditutup">

                                <span>Ditutup</span>
                            </label>

                        </div>

                    </div>

            </div>

        </div>

        <!-- BUTTON -->
        <div class="form-action">

            <button type="submit"
                    class="btn-save">

                Simpan

            </button>

            <a href="{{ route('periode-ami.index') }}"
               class="btn-cancel">

                Batal

            </a>

        </div>

    </form>

</div>

@endsection
