<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>

        Laporan Audit Mutu Internal

    </title>

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}">

    <link
        rel="stylesheet"
        href="{{ asset('css/laporan.css') }}">

</head>

<body class="laporan-body">

<div class="laporan-container">

<!-- ===========================================================
    COVER
=========================================================== -->

<section class="cover">

    <img
        src="{{ asset('images/poliwangi.png') }}"
        class="cover-logo">

    <h4>

        POLITEKNIK NEGERI BANYUWANGI

    </h4>

    <h1>

        LAPORAN AUDIT MUTU INTERNAL

    </h1>

    <h2>

        STANDAR PENDIDIKAN

    </h2>

    <div class="cover-periode">

        PERIODE AMI {{ $periode->tahun }}

    </div>

    <br>

    <table class="laporan-table" style="width:420px;margin:auto;">

        <tr>

            <td>Nomor Dokumen</td>

            <td>:</td>

            <td>

                AMI/{{ $periode->tahun }}

            </td>

        </tr>

        <tr>

            <td>Status Audit</td>

            <td>:</td>

            <td>

                {{ ucfirst($periode->status) }}

            </td>

        </tr>

        <tr>

            <td>Tanggal Cetak</td>

            <td>:</td>

            <td>

                {{ now()->format('d F Y') }}

            </td>

        </tr>

    </table>

</section>

<!-- ===========================================================
    IDENTITAS AUDIT
=========================================================== -->

<section class="laporan-card">

    <h3>

        IDENTITAS AUDIT

    </h3>

    <table class="laporan-table">

        <tr>

            <td width="230">

                Periode Audit

            </td>

            <td width="20">

                :

            </td>

            <td>

                {{ $periode->tahun }}

            </td>

        </tr>

        <tr>

            <td>

                Standar Mutu

            </td>

            <td>

                :

            </td>

            <td>

                {{ $periode->standarMutu->nama_standar_mutu ?? '-' }}

            </td>

        </tr>

        <tr>

            <td>

                Unit Kerja

            </td>

            <td>

                :

            </td>

            <td>

                {{ $periode->unitKerja->nama ?? '-' }}

            </td>

        </tr>

        <tr>

            <td>

                Tujuan Audit

            </td>

            <td>

                :

            </td>

            <td>

                {{ $periode->tujuan_audit }}

            </td>

        </tr>

        <tr>

            <td>

                Lingkup Audit

            </td>

            <td>

                :

            </td>

            <td>

                {{ $periode->lingkup_audit }}

            </td>

        </tr>

        <tr>

            <td>

                Waktu Audit

            </td>

            <td>

                :

            </td>

            <td>

                {{ $periode->waktu_audit }}

            </td>

        </tr>

        <tr>

            <td>

                Status Audit

            </td>

            <td>

                :

            </td>

            <td>

                <strong>

                    {{ ucfirst($periode->status) }}

                </strong>

            </td>

        </tr>

    </table>

</section>

<!-- ===========================================================
    TIM AUDITOR
=========================================================== -->

<section class="laporan-card">

    <h3>

        TIM AUDITOR

    </h3>

    <table class="laporan-table">

        <thead>

            <tr>

                <th width="70">

                    No

                </th>

                <th>

                    Nama Auditor

                </th>

                <th>

                    Jabatan

                </th>

            </tr>

        </thead>

        <tbody>

        @foreach($periode->tim as $tim)

            <tr>

                <td>

                    {{ $loop->iteration }}

                </td>

                <td>

                    {{ $tim->user->nama ?? '-' }}

                </td>

                <td>

                    {{ $tim->role }}

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</section>

<!-- ===========================================================
    RINGKASAN AUDIT
=========================================================== -->

<section class="laporan-card">

    <h3>

        RINGKASAN AUDIT

    </h3>

    <div class="summary-grid">

    <div class="summary-card">

        <h2>{{ $jumlahStandar }}</h2>

        <span>Standar</span>

    </div>

    <div class="summary-card">

        <h2>{{ $jumlahPenerapan }}</h2>

        <span>Penerapan</span>

    </div>

    <div class="summary-card">

        <h2>{{ $jumlahPertanyaan }}</h2>

        <span>Pertanyaan</span>

    </div>

    <div class="summary-card">

        <h2>{{ $jumlahTemuan }}</h2>

        <span>Temuan</span>

    </div>

    <div class="summary-card">

        <h2>{{ $jumlahTanggapan }}</h2>

        <span>Tanggapan</span>

    </div>

    <div class="summary-card">

        <h2>{{ $jumlahAkarMasalah }}</h2>

        <span>Akar Masalah</span>

    </div>

    <div class="summary-card">

        <h2>{{ $jumlahRekomendasi }}</h2>

        <span>Rekomendasi</span>

    </div>

    <div class="summary-card">

        <h2>{{ $jumlahLampiran }}</h2>

        <span>Lampiran</span>

    </div>

</div>

</section>

<!-- ===========================================================
    PENDAHULUAN
=========================================================== -->

<section class="laporan-card">

    <h3>

        PENDAHULUAN

    </h3>

    <p style="text-align:justify;line-height:1.8;">

        Audit Mutu Internal (AMI) merupakan salah satu kegiatan dalam
        Sistem Penjaminan Mutu Internal (SPMI) yang bertujuan untuk
        memastikan bahwa pelaksanaan standar pendidikan telah sesuai
        dengan ketentuan yang berlaku serta menjadi dasar dalam
        peningkatan mutu secara berkelanjutan.

    </p>

    <p style="text-align:justify;line-height:1.8;">

        Laporan ini disusun berdasarkan hasil audit pada periode

        <strong>{{ $periode->tahun }}</strong>

        sebagai bentuk dokumentasi pelaksanaan audit terhadap unit kerja
        yang diaudit.

    </p>

</section>

<!-- ===========================================================
    TUJUAN AUDIT
=========================================================== -->

<section class="laporan-card">

    <h3>

        TUJUAN AUDIT

    </h3>

    <p>

        {{ $periode->tujuan_audit }}

    </p>

</section>

<!-- ===========================================================
    LINGKUP AUDIT
=========================================================== -->

<section class="laporan-card">

    <h3>

        LINGKUP AUDIT

    </h3>

    <p>

        {{ $periode->lingkup_audit }}

    </p>

</section>

<!-- ===========================================================
    JADWAL AUDIT
=========================================================== -->

<section class="laporan-card">

    <h3>

        JADWAL AUDIT

    </h3>

    <table class="laporan-table">

        <thead>

            <tr>

                <th width="70">

                    No

                </th>

                <th>

                    Kegiatan

                </th>

                <th width="220">

                    Waktu

                </th>

            </tr>

        </thead>

        <tbody>

        @forelse($periode->jadwal as $jadwal)

        <tr>

            <td>

                {{ $loop->iteration }}

            </td>

            <td>

                {{ $jadwal->kegiatan }}

            </td>

            <td>

                {{ $jadwal->waktu }}

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="3">

                Belum ada jadwal audit.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</section>

<!-- ===========================================================
    PENERAPAN STANDAR
=========================================================== -->

<section class="laporan-card">

    <h3>

        PENERAPAN STANDAR

    </h3>

    @forelse($periode->standarMutuPeriode as $standar)

        <div class="laporan-subcard">

            <h4>

                {{ $standar->standarMutu->nama_standar_mutu }}

            </h4>

            @forelse($standar->penerapanStandar as $penerapan)

                <table class="laporan-table">

                    <tr>

                        <td width="220">

                            Auditor

                        </td>

                        <td width="20">

                            :

                        </td>

                        <td>

                            {{ $penerapan->user->nama ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <td>

                            Deskripsi Hasil

                        </td>

                        <td>

                            :

                        </td>

                        <td>

                            {{ $penerapan->deskripsi_hasil }}

                        </td>

                    </tr>

                    <tr>

                      @if($penerapan->link_bukti)

                        <a
                            href="{{ $penerapan->link_bukti }}"
                            target="_blank"
                            class="btn-link">

                            Lihat Bukti

                        </a>

                        @else

                        -

                        @endif

                        <td>

                            :

                        </td>

                        <td>

                            <a
                                href="{{ $penerapan->link_bukti }}"
                                target="_blank">

                                {{ $penerapan->link_bukti }}

                            </a>

                        </td>

                    </tr>

                </table>

                <br>

            @empty

                <p>

                    Belum ada data penerapan.

                </p>

            @endforelse

        </div>

    @empty

        <p>

            Tidak ada data standar.

        </p>

    @endforelse

</section>
<!-- ===========================================================
    PERTANYAAN AUDIT
=========================================================== -->

<section class="laporan-card">

    <h3>

        PERTANYAAN AUDIT

    </h3>

    @php
        $noPertanyaan = 1;
    @endphp

    @foreach($periode->standarMutuPeriode as $standar)

        @foreach($standar->penerapanStandar as $penerapan)

            @foreach($penerapan->pertanyaan as $pertanyaan)

                <div class="laporan-subcard">

                    <table class="laporan-table">

                        <tr>

                            <td width="60">

                                No

                            </td>

                            <td width="20">

                                :

                            </td>

                            <td>

                                {{ $noPertanyaan++ }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Standar

                            </td>

                            <td>

                                :

                            </td>

                            <td>

                                {{ $standar->standarMutu->nama_standar_mutu }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Pertanyaan

                            </td>

                            <td>

                                :

                            </td>

                            <td>

                                {{ $pertanyaan->pertanyaan }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Indikator

                            </td>

                            <td>

                                :

                            </td>

                            <td>

                                {{ $pertanyaan->indikator }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Referensi

                            </td>

                            <td>

                                :

                            </td>

                            <td>

                                {{ $pertanyaan->referensi }}

                            </td>

                        </tr>

                    </table>

                </div>

                <br>

            @endforeach

        @endforeach

    @endforeach

</section>

<!-- ===========================================================
    TEMUAN AUDIT
=========================================================== -->

<section class="laporan-card">

    <h3>

        TEMUAN AUDIT

    </h3>

    <table class="laporan-table">

        <thead>

            <tr>

                <th width="60">

                    No

                </th>

                <th>

                    Pertanyaan

                </th>

                <th>

                    Temuan

                </th>

                <th width="140">

                    Status

                </th>

            </tr>

        </thead>

        <tbody>

        @php
            $noTemuan = 1;
        @endphp

        @foreach($periode->standarMutuPeriode as $standar)

            @foreach($standar->penerapanStandar as $penerapan)

                @foreach($penerapan->pertanyaan as $pertanyaan)

                    @foreach($pertanyaan->temuan as $temuan)

                    <tr>

                        <td>

                            {{ $noTemuan++ }}

                        </td>

                        <td>

                            {{ $pertanyaan->pertanyaan }}

                        </td>

                        <td>

                            {{ $temuan->temuan }}

                        </td>

                        <td>

                            @if(strtolower($temuan->status_temuan)=='sesuai')

    <span class="badge-success">

        Sesuai

    </span>

@elseif(strtolower($temuan->status_temuan)=='observasi')

    <span class="badge-warning">

        Observasi

    </span>

@else

    <span class="badge-danger">

        Tidak Sesuai

    </span>

@endif

                        </td>

                    </tr>

                    @endforeach

                @endforeach

            @endforeach

        @endforeach

        </tbody>

    </table>

</section>

<!-- ===========================================================
    TANGGAPAN AUDITEE
=========================================================== -->

<section class="laporan-card">

    <h3>

        TANGGAPAN AUDITEE

    </h3>

    @php
        $noTanggapan = 1;
    @endphp

    @foreach($periode->standarMutuPeriode as $standar)

        @foreach($standar->penerapanStandar as $penerapan)

            @foreach($penerapan->pertanyaan as $pertanyaan)

                @foreach($pertanyaan->temuan as $temuan)

                    @foreach($temuan->tanggapan as $tanggapan)

                    <div class="laporan-subcard">

                        <table class="laporan-table">

                            <tr>

                                <td width="60">

                                    No

                                </td>

                                <td width="20">

                                    :

                                </td>

                                <td>

                                    {{ $noTanggapan++ }}

                                </td>

                            </tr>

                            <tr>

                                <td>

                                    Auditor

                                </td>

                                <td>

                                    :

                                </td>

                                <td>

                                    {{ $tanggapan->user->nama ?? '-' }}

                                </td>

                            </tr>

                            <tr>

                                <td>

                                    Tanggapan

                                </td>

                                <td>

                                    :

                                </td>

                                <td>

                                    {{ $tanggapan->tanggapan }}

                                </td>

                            </tr>

                        </table>

                    </div>

                    <br>

                    @endforeach

                @endforeach

            @endforeach

        @endforeach

    @endforeach

</section>

<!-- ===========================================================
    AKAR MASALAH
=========================================================== -->

<section class="laporan-card">

    <h3>

        AKAR MASALAH

    </h3>

    <table class="laporan-table">

        <thead>

            <tr>

                <th width="60">

                    No

                </th>

                <th>

                    Temuan Audit

                </th>

                <th>

                    Akar Masalah

                </th>

            </tr>

        </thead>

        <tbody>

        @php
            $noAkar = 1;
        @endphp

        @foreach($periode->standarMutuPeriode as $standar)

            @foreach($standar->penerapanStandar as $penerapan)

                @foreach($penerapan->pertanyaan as $pertanyaan)

                    @foreach($pertanyaan->temuan as $temuan)

                        @foreach($temuan->akarMasalah as $akar)

                        <tr>

                            <td>

                                {{ $noAkar++ }}

                            </td>

                            <td>

                                {{ $temuan->temuan }}

                            </td>

                            <td>

                                {{ $akar->akar_masalah }}

                            </td>

                        </tr>

                        @endforeach

                    @endforeach

                @endforeach

            @endforeach

        @endforeach

        </tbody>

    </table>

</section>

<!-- ===========================================================
    REKOMENDASI
=========================================================== -->

<section class="laporan-card">

    <h3>

        REKOMENDASI PENINGKATAN

    </h3>

    <table class="laporan-table">

        <thead>

            <tr>

                <th>No</th>

                <th>Aspek</th>

                <th>Kelebihan</th>

                <th>Rekomendasi</th>

            </tr>

        </thead>

        <tbody>

        @php
            $noRekom = 1;
        @endphp

        @foreach($periode->standarMutuPeriode as $standar)

            @foreach($standar->penerapanStandar as $penerapan)

                @foreach($penerapan->rekomendasi as $rekom)

                <tr>

                    <td>

                        {{ $noRekom++ }}

                    </td>

                    <td>

                        {{ $rekom->aspek }}

                    </td>

                    <td>

                        {{ $rekom->kelebihan }}

                    </td>

                    <td>

                        {{ $rekom->rekomendasi }}

                    </td>

                </tr>

                @endforeach

            @endforeach

        @endforeach

        </tbody>

    </table>

</section>

<!-- ===========================================================
    KESIMPULAN
=========================================================== -->

<section class="laporan-card">

    <h3>

        KESIMPULAN AUDIT

    </h3>

    @forelse($periode->kesimpulanAudit as $kesimpulan)

        <div class="kesimpulan-box">

    <i class="bi bi-check-circle-fill"></i>

    <div>

        {{ $kesimpulan->kesimpulan }}

    </div>

</div>
    @empty

        <p>

            Belum ada kesimpulan audit.

        </p>

    @endforelse

</section>

<!-- ===========================================================
    LAMPIRAN
=========================================================== -->

<section class="laporan-card">

    <h3>

        LAMPIRAN

    </h3>

    <table class="laporan-table">

        <thead>

            <tr>

                <th width="60">

                    No

                </th>

                <th>

                    Link Lampiran

                </th>

            </tr>

        </thead>

        <tbody>

        @forelse($periode->lampiran as $lampiran)

        <tr>

            <td>

                {{ $loop->iteration }}

            </td>

            <td>

                @if($lampiran->link_file)

                    <a
                        href="{{ $lampiran->link_file }}"
                        target="_blank"
                        class="btn-link">

                        Download Lampiran

                    </a>

                @else

-

@endif

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="2">

                Belum ada lampiran.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</section>

<!-- ===========================================================
    PENGESAHAN
=========================================================== -->

<section class="laporan-card">

<h3>

PENGESAHAN

</h3>

<br><br>

<table style="width:100%;text-align:center;">

<tr>

<td>

Mengetahui,

<br>

Ketua PPMPP

</td>

<td>

Auditor Ketua

</td>

</tr>

<tr>

<td style="height:120px;"></td>

<td></td>

</tr>

<tr>

<td>

_____________________

</td>

<td>

_____________________

</td>

</tr>

</table>

</section>

<!-- ===========================================================
    BUTTON
=========================================================== -->

<div style="text-align:center;margin:40px 0;">

    <div class="button-group">

<a

href="{{ route('auditor.laporan.index') }}"

class="btn-secondary">

<i class="bi bi-arrow-left"></i>

Kembali

</a>

<button

class="btn-print"

onclick="window.print()">

<i class="bi bi-printer"></i>

Cetak

</button>

<a

href="#"

class="btn-primary">

<i class="bi bi-file-earmark-pdf"></i>

Download PDF

</a>

</div>

</div>

</div>

</body>

</html>