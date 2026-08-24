<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Audit Mutu Internal
    </title>

    <style>

        @page {
            margin: 22mm 16mm 19mm 16mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #1f2937;
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5pt;
            line-height: 1.55;
        }

        h1,
        h2,
        h3,
        h4,
        p {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .page-break {
            page-break-after: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        /*
        |--------------------------------------------------------------------------
        | COVER
        |--------------------------------------------------------------------------
        */

        .cover {
            position: relative;
            min-height: 238mm;
            padding: 14mm 14mm;
            border: 1.5px solid #244b7a;
            page-break-after: always;
        }

        .cover-top-line {
            height: 7px;
            margin: -14mm -14mm 20mm;
            background: #244b7a;
        }

        .cover-logo {
            width: 77px;
            margin-bottom: 16px;
        }

        .cover-institution {
            margin-bottom: 42px;
            color: #244b7a;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.8px;
        }

        .cover-small-title {
            margin-bottom: 9px;
            color: #64748b;
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 1.8px;
        }

        .cover-title {
            margin: 0;
            color: #16375f;
            font-size: 28pt;
            line-height: 1.25;
            letter-spacing: 0.4px;
        }

        .cover-unit {
            margin-top: 15px;
            color: #334155;
            font-size: 16pt;
            font-weight: bold;
        }

        .cover-divider {
            width: 84px;
            height: 5px;
            margin: 23px 0;
            background: #d7a52a;
        }

        .cover-period {
            color: #475569;
            font-size: 12pt;
        }

        .cover-information {
            position: absolute;
            right: 14mm;
            bottom: 25mm;
            left: 14mm;
            padding: 14px 16px;
            border-left: 5px solid #244b7a;
            background: #f4f7fb;
        }

        .cover-information-table td {
            padding: 4px 5px;
            vertical-align: top;
        }

        .cover-information-table td:first-child {
            width: 145px;
            color: #64748b;
        }

        .cover-information-table td:nth-child(2) {
            width: 14px;
            text-align: center;
        }

        .cover-footer {
            position: absolute;
            right: 14mm;
            bottom: 9mm;
            left: 14mm;
            color: #64748b;
            font-size: 8pt;
            text-align: center;
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER DOKUMEN
        |--------------------------------------------------------------------------
        */

        .document-header {
            width: 100%;
            margin-bottom: 18px;
            border-bottom: 2px solid #244b7a;
        }

        .document-header td {
            padding-bottom: 10px;
            vertical-align: middle;
        }

        .header-logo {
            width: 48px;
        }

        .header-title {
            color: #16375f;
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
        }

        .header-subtitle {
            margin-top: 3px;
            color: #64748b;
            font-size: 8pt;
            text-align: center;
        }

        .document-number {
            width: 128px;
            color: #475569;
            font-size: 7.5pt;
            text-align: right;
        }

        <style>
    /*
    |--------------------------------------------------------------------------
    | TABEL LAPORAN PDF
    |--------------------------------------------------------------------------
    */

    .official-table {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        margin: 0;
        font-size: 9px;
    }

    .official-table th,
    .official-table td {
        border: 1px solid #8da0b8;
        padding: 6px 5px;
        vertical-align: top;
        overflow-wrap: anywhere;
        word-wrap: break-word;
        word-break: normal;
    }

    .official-table th {
        text-align: center;
        vertical-align: middle;
        font-weight: 700;
        background: #e8eef5;
        color: #173f70;
    }

    .official-table td {
        line-height: 1.45;
    }

    /*
    |--------------------------------------------------------------------------
    | KHUSUS TABEL REKAPITULASI PENERAPAN
    |--------------------------------------------------------------------------
    */

    .table-penerapan {
        width: 100%;
        table-layout: fixed;
    }

    .table-penerapan th:nth-child(1),
    .table-penerapan td:nth-child(1) {
        width: 5%;
        text-align: center;
    }

    .table-penerapan th:nth-child(2),
    .table-penerapan td:nth-child(2) {
        width: 17%;
    }

    .table-penerapan th:nth-child(3),
    .table-penerapan td:nth-child(3) {
        width: 22%;
    }

    .table-penerapan th:nth-child(4),
    .table-penerapan td:nth-child(4) {
        width: 26%;
    }

    .table-penerapan th:nth-child(5),
    .table-penerapan td:nth-child(5) {
        width: 8%;
        text-align: center;
    }

    .table-penerapan th:nth-child(6),
    .table-penerapan td:nth-child(6) {
        width: 12%;
        text-align: center;
    }

    .table-penerapan th:nth-child(7),
    .table-penerapan td:nth-child(7) {
        width: 10%;
        text-align: center;
    }

    /*
    |--------------------------------------------------------------------------
    | SKOR
    |--------------------------------------------------------------------------
    */

    .score-number {
        display: block;
        font-size: 13px;
        font-weight: 700;
        line-height: 1.2;
        text-align: center;
    }

    .score-label {
        display: block;
        margin-top: 3px;
        font-size: 8px;
        line-height: 1.2;
        text-align: center;
        color: #64748b;
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    .status-badge {
        display: inline-block;
        max-width: 100%;
        padding: 3px 5px;
        border: 1px solid #6b7280;
        border-radius: 10px;
        font-size: 8px;
        font-weight: 600;
        line-height: 1.2;
        text-align: center;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .status-sesuai {
        color: #166534;
        border-color: #22c55e;
    }

    .status-belum-sesuai {
        color: #c2410c;
        border-color: #f97316;
    }

    /*
    |--------------------------------------------------------------------------
    | BUKTI
    |--------------------------------------------------------------------------
    */

    .bukti-text {
        font-size: 8px;
        line-height: 1.3;
        text-align: center;
    }

    /*
    |--------------------------------------------------------------------------
    | CATATAN KECIL
    |--------------------------------------------------------------------------
    */

    .small-note {
        font-size: 8px;
        line-height: 1.2;
    }

    /*
    |--------------------------------------------------------------------------
    | DOMPDF
    |--------------------------------------------------------------------------
    */

    @page {
        size: A4 portrait;
        margin: 15mm 12mm 15mm 12mm;
    }

    .section {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    /*
    | Jangan memaksa tabel melewati halaman.
    */

    table {
        max-width: 100%;
    }

    tr {
        page-break-inside: avoid;
    }

    thead {
        display: table-header-group;
    }

    tfoot {
        display: table-footer-group;
    }


        /*
        |--------------------------------------------------------------------------
        | RINGKASAN
        |--------------------------------------------------------------------------
        */

        .summary-grid td {
            width: 25%;
            padding: 5px;
            border: 0;
        }

        .summary-box {
            min-height: 68px;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            background: #f8fafc;
            text-align: center;
        }

        .summary-number {
            margin-bottom: 3px;
            color: #16375f;
            font-size: 18pt;
            font-weight: bold;
        }

        .summary-label {
            color: #64748b;
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .summary-highlight {
            border-color: #d7a52a;
            background: #fffaf0;
        }

        .summary-danger {
            border-color: #f1a6a6;
            background: #fff5f5;
        }

        .summary-success {
            border-color: #9bd4ae;
            background: #f2fbf5;
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        .status {
            display: inline-block;
            min-width: 54px;
            padding: 3px 7px;
            border-radius: 10px;
            font-size: 7pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .status-open {
            border: 1px solid #c2410c;
            background: #fff7ed;
            color: #9a3412;
        }

        .status-closed {
            border: 1px solid #15803d;
            background: #f0fdf4;
            color: #166534;
        }

        /*
        |--------------------------------------------------------------------------
        | DETAIL TEMUAN
        |--------------------------------------------------------------------------
        */

        .finding-card {
            margin-bottom: 15px;
            padding: 11px 12px;
            border: 1px solid #cbd5e1;
            border-left: 5px solid #244b7a;
            page-break-inside: avoid;
        }

        .finding-heading {
            margin-bottom: 9px;
            padding-bottom: 7px;
            border-bottom: 1px solid #e2e8f0;
            color: #16375f;
            font-weight: bold;
        }

        .detail-table td {
            padding: 5px 4px;
            vertical-align: top;
        }

        .detail-table td:first-child {
            width: 145px;
            color: #64748b;
            font-weight: bold;
        }

        .detail-table td:nth-child(2) {
            width: 14px;
            text-align: center;
        }

        /*
        |--------------------------------------------------------------------------
        | KOSONG DAN INFORMASI
        |--------------------------------------------------------------------------
        */

        .empty-value {
            padding: 12px;
            border: 1px dashed #cbd5e1;
            background: #f8fafc;
            color: #64748b;
            text-align: center;
        }

        .commitment-box {
            padding: 13px 15px;
            border: 1px solid #aebfd2;
            background: #f8fafc;
            text-align: justify;
        }

        .small-note {
            color: #64748b;
            font-size: 7.5pt;
        }

        .link-text {
            color: #244b7a;
            font-size: 7.5pt;
            word-break: break-all;
        }

        /*
        |--------------------------------------------------------------------------
        | TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        .signature-table {
            margin-top: 28px;
        }

        .signature-table td {
            width: 33.33%;
            padding: 4px 10px;
            text-align: center;
            vertical-align: top;
        }

        .signature-space {
            height: 62px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

    </style>

</head>

<body>

{{-- ============================================================
     COVER
============================================================ --}}

<section class="cover">

    <div class="cover-top-line"></div>

    @if($logoBase64)

        <img
            src="{{ $logoBase64 }}"
            class="cover-logo"
            alt="Logo Politeknik Negeri Banyuwangi"
        >

    @endif

    <div class="cover-institution">
        POLITEKNIK NEGERI BANYUWANGI
    </div>

    <div class="cover-small-title">
        LAPORAN HASIL
    </div>

    <h1 class="cover-title">
        AUDIT MUTU<br>
        INTERNAL
    </h1>

    <div class="cover-divider"></div>

    <div class="cover-unit">

        {{
            $periode->unitKerja->nama
            ?? $periode->unitKerja->nama_unit_kerja
            ?? 'UNIT KERJA'
        }}

    </div>

    <div class="cover-period">
        Periode AMI {{ $periode->tahun ?? '-' }}
    </div>

    <div class="cover-information">

        <table class="cover-information-table">

            <tr>
                <td>Nomor Dokumen</td>
                <td>:</td>
                <td>{{ $nomorDokumen }}</td>
            </tr>

            <tr>
                <td>Standar Mutu</td>
                <td>:</td>
                <td>
                    {{
                        $periode->standarMutu->nama_standar_mutu
                        ?? $periode->standarMutu->nama
                        ?? '-'
                    }}
                </td>
            </tr>

            <tr>
                <td>Ketua Tim Auditor</td>
                <td>:</td>
                <td>
                    {{
                        $ketuaAuditor->user->nama
                        ?? $ketuaAuditor->user->name
                        ?? '-'
                    }}
                </td>
            </tr>

            <tr>
                <td>Status Audit</td>
                <td>:</td>
                <td>
                    {{ ucfirst($periode->status ?? '-') }}
                </td>
            </tr>

            <tr>
                <td>Tanggal Dokumen</td>
                <td>:</td>
                <td>
                    {{ now()->format('d/m/Y') }}
                </td>
            </tr>

        </table>

    </div>

    <div class="cover-footer">
        Pusat Penjaminan Mutu · Politeknik Negeri Banyuwangi
    </div>

</section>

{{-- ============================================================
     HEADER DOKUMEN
============================================================ --}}

<table class="document-header">

    <tr>

        <td width="60">

            @if($logoBase64)

                <img
                    src="{{ $logoBase64 }}"
                    class="header-logo"
                    alt="Logo"
                >

            @endif

        </td>

        <td>

            <div class="header-title">
                LAPORAN AUDIT MUTU INTERNAL
            </div>

            <div class="header-subtitle">
                POLITEKNIK NEGERI BANYUWANGI
            </div>

        </td>

        <td class="document-number">
            {{ $nomorDokumen }}<br>
            Periode {{ $periode->tahun ?? '-' }}
        </td>

    </tr>

</table>

{{-- ============================================================
     I. IDENTITAS AUDIT
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        I. Identitas Audit
    </h2>

    <table class="official-table identity-table">

        <tr>
            <td>Perguruan Tinggi</td>
            <td>:</td>
            <td>Politeknik Negeri Banyuwangi</td>
        </tr>

        <tr>
            <td>Unit Kerja/Program Studi</td>
            <td>:</td>
            <td>
                {{
                    $periode->unitKerja->nama
                    ?? $periode->unitKerja->nama_unit_kerja
                    ?? '-'
                }}
            </td>
        </tr>

        <tr>
            <td>Standar Mutu</td>
            <td>:</td>
            <td>
                {{
                    $periode->standarMutu->nama_standar_mutu
                    ?? $periode->standarMutu->nama
                    ?? '-'
                }}
            </td>
        </tr>

        <tr>
            <td>Periode Audit</td>
            <td>:</td>
            <td>
                {{ $periode->tahun ?? '-' }}
            </td>
        </tr>

        <tr>
            <td>Tanggal Pembukaan AMI</td>
            <td>:</td>
            <td>
                {{ $periode->tanggal_buka_ami ?? '-' }}
            </td>
        </tr>

        <tr>
            <td>Tanggal Penutupan AMI</td>
            <td>:</td>
            <td>
                {{ $periode->tanggal_tutup_ami ?? '-' }}
            </td>
        </tr>

        <tr>
            <td>Waktu Audit</td>
            <td>:</td>
            <td>
                {{ $periode->waktu_audit ?? '-' }}
            </td>
        </tr>

        <tr>
            <td>Ketua Tim Auditor</td>
            <td>:</td>
            <td>
                {{
                    $ketuaAuditor->user->nama
                    ?? $ketuaAuditor->user->name
                    ?? '-'
                }}
            </td>
        </tr>

        <tr>
            <td>Anggota Auditor</td>
            <td>:</td>
            <td>

                @forelse($anggotaAuditor as $anggota)

                    {{ $loop->iteration }}.
                    {{
                        $anggota->user->nama
                        ?? $anggota->user->name
                        ?? '-'
                    }}

                    @if(!$loop->last)
                        <br>
                    @endif

                @empty
                    -
                @endforelse

            </td>
        </tr>

        <tr>
            <td>Auditee</td>
            <td>:</td>
            <td>

                @forelse($auditeeList as $auditee)

                    {{ $loop->iteration }}.
                    {{
                        $auditee->nama
                        ?? $auditee->name
                        ?? '-'
                    }}

                    @if(!$loop->last)
                        <br>
                    @endif

                @empty
                    -
                @endforelse

            </td>
        </tr>

        <tr>
            <td>Status Audit</td>
            <td>:</td>
            <td>
                {{ ucfirst($periode->status ?? '-') }}
            </td>
        </tr>

    </table>

</section>

{{-- ============================================================
     II. RINGKASAN EKSEKUTIF
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        II. Ringkasan Eksekif
    </h2>

    <p class="paragraph">
        Ringkasan berikut menggambarkan cakupan pelaksanaan Audit
        Mutu Internal berdasarkan data penerapan standar, bukti
        pendukung, temuan auditor, tindak lanjut, dan rekomendasi
        yang tersimpan dalam Sistem Informasi SPMI.
    </p>

    <table class="summary-grid">

        <tr>

            <td>
                <div class="summary-box">
                    <div class="summary-number">
                        {{ $statistik['jumlah_standar'] }}
                    </div>
                    <div class="summary-label">
                        Standar Diaudit
                    </div>
                </div>
            </td>

            <td>
                <div class="summary-box">
                    <div class="summary-number">
                        {{ $statistik['jumlah_indikator'] }}
                    </div>
                    <div class="summary-label">
                        Indikator
                    </div>
                </div>
            </td>

            <td>
                <div class="summary-box">
                    <div class="summary-number">
                        {{ $statistik['jumlah_penerapan'] }}
                    </div>
                    <div class="summary-label">
                        Penerapan
                    </div>
                </div>
            </td>

            <td>
                <div class="summary-box">
                    <div class="summary-number">
                        {{ $statistik['jumlah_bukti'] }}
                    </div>
                    <div class="summary-label">
                        Bukti Pendukung
                    </div>
                </div>
            </td>

        </tr>

        <tr>

            <td>
                <div class="summary-box summary-highlight">
                    <div class="summary-number">
                        {{ $statistik['jumlah_temuan'] }}
                    </div>
                    <div class="summary-label">
                        Total Temuan
                    </div>
                </div>
            </td>

            <td>
                <div class="summary-box summary-danger">
                    <div class="summary-number">
                        {{ $statistik['jumlah_temuan_open'] }}
                    </div>
                    <div class="summary-label">
                        Temuan Open
                    </div>
                </div>
            </td>

            <td>
                <div class="summary-box summary-success">
                    <div class="summary-number">
                        {{ $statistik['jumlah_temuan_closed'] }}
                    </div>
                    <div class="summary-label">
                        Temuan Closed
                    </div>
                </div>
            </td>

            <td>
                <div class="summary-box summary-success">
                    <div class="summary-number">
                        {{
                            number_format(
                                $statistik['persentase_penyelesaian'],
                                2,
                                ',',
                                '.'
                            )
                        }}%
                    </div>
                    <div class="summary-label">
                        Penyelesaian
                    </div>
                </div>
            </td>

        </tr>

    </table>

</section>

{{-- ============================================================
     III. PENDAHULUAN
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        III. Pendahuluan
    </h2>

    <p class="paragraph">
        Audit Mutu Internal merupakan proses evaluasi yang
        dilaksanakan secara sistematis, mandiri, dan terdokumentasi
        untuk memastikan bahwa pelaksanaan standar mutu telah
        berjalan sesuai dengan ketentuan yang ditetapkan.
    </p>

    <p class="paragraph">
        Pelaksanaan audit pada
        <strong>
            {{
                $periode->unitKerja->nama
                ?? $periode->unitKerja->nama_unit_kerja
                ?? 'unit kerja'
            }}
        </strong>
        dilaksanakan dalam Periode AMI
        <strong>{{ $periode->tahun ?? '-' }}</strong>
        sebagai bagian dari peningkatan mutu secara berkelanjutan
        di lingkungan Politeknik Negeri Banyuwangi.
    </p>

</section>

{{-- ============================================================
     IV. TUJUAN DAN LINGKUP
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        IV. Tujuan dan Lingkup Audit
    </h2>

    <h3 class="subsection-title">
        A. Tujuan Audit
    </h3>

    @if(filled($periode->tujuan_audit))

        <div class="paragraph">
            {!! nl2br(e($periode->tujuan_audit)) !!}
        </div>

    @else

        <ol class="ordered-list">
            <li>
                Memastikan pelaksanaan standar mutu telah sesuai
                dengan ketentuan yang ditetapkan.
            </li>

            <li>
                Memastikan bukti penerapan standar tersedia dan
                dapat diverifikasi.
            </li>

            <li>
                Mengidentifikasi ketidaksesuaian serta peluang
                peningkatan mutu.
            </li>

            <li>
                Mendorong pelaksanaan peningkatan mutu secara
                berkelanjutan.
            </li>
        </ol>

    @endif

    <h3 class="subsection-title">
        B. Lingkup Audit
    </h3>

    @if(filled($periode->lingkup_audit))

        <div class="paragraph">
            {!! nl2br(e($periode->lingkup_audit)) !!}
        </div>

    @else

        <p class="paragraph">
            Lingkup audit meliputi pelaksanaan standar mutu,
            kesesuaian hasil penerapan, kelengkapan bukti
            pendukung, temuan audit, serta tindak lanjut pada unit
            kerja yang diaudit.
        </p>

    @endif

</section>

{{-- ============================================================
     V. JADWAL AUDIT
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        V. Jadwal Pelaksanaan Audit
    </h2>

    @if($periode->jadwal->isNotEmpty())

        <table class="official-table">

            <thead>

                <tr>
                    <th width="38">
                        No.
                    </th>

                    <th width="125">
                        Waktu
                    </th>

                    <th>
                        Kegiatan Audit
                    </th>
                </tr>

            </thead>

            <tbody>

                @foreach($periode->jadwal as $jadwal)

                    <tr>

                        <td style="text-align: center;">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $jadwal->waktu ?? '-' }}
                        </td>

                        <td>
                            {{ $jadwal->kegiatan ?? '-' }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty-value">
            Jadwal audit belum tersedia.
        </div>

    @endif

</section>

<div class="page-break"></div>

{{-- ============================================================
     VI. REKAPITULASI PENERAPAN STANDAR
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        VI. REKAPITULASI PENERAPAN STANDAR
    </h2>

    @if($penerapanList->isNotEmpty())

        <table class="official-table table-penerapan">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Standar</th>
                    <th>Indikator</th>
                    <th>Hasil Penerapan</th>
                    <th>Skor</th>
                    <th>Status</th>
                    <th>Bukti</th>
                </tr>
            </thead>

            <tbody>

                @foreach($penerapanList as $penerapan)

                    @php
                        $nilaiSkor =
                            $penerapan->skor?->skalaSkor?->nilai_skor;

                        $labelSkor =
                            $penerapan->skor?->skalaSkor?->label_skor;

                        $status =
                            trim(
                                (string) (
                                    $penerapan->status_penerapan ?? ''
                                )
                            );

                        $statusNormal =
                            strtolower(
                                str_replace(
                                    [' ', '-'],
                                    '_',
                                    $status
                                )
                            );
                    @endphp

                    <tr>

                        {{-- NO --}}
                        <td style="text-align:center;">
                            {{ $loop->iteration }}
                        </td>

                        {{-- STANDAR --}}
                        <td>
                            {{
                                $penerapan
                                    ->standarmutuPeriode
                                    ?->standarMutu
                                    ?->nama_standar_mutu
                                ??
                                $penerapan
                                    ->standarmutuPeriode
                                    ?->standarMutu
                                    ?->nama
                                ??
                                '-'
                            }}
                        </td>

                        {{-- INDIKATOR --}}
                        <td>
                            {{
                                $penerapan
                                    ->indikator
                                    ?->deskripsi
                                ??
                                $penerapan
                                    ->indikator
                                    ?->indikator
                                ??
                                '-'
                            }}
                        </td>

                        {{-- HASIL PENERAPAN --}}
                        <td>
                            {!! nl2br(
                                e(
                                    $penerapan->deskripsi_hasil
                                    ?? '-'
                                )
                            ) !!}
                        </td>

                        {{-- SKOR --}}
                        <td>

                            @if($nilaiSkor !== null)

                                <span class="score-number">
                                    {{ $nilaiSkor }}
                                </span>

                                @if($labelSkor)
                                    <span class="score-label">
                                        {{ $labelSkor }}
                                    </span>
                                @endif

                            @else

                                <span class="small-note">
                                    -
                                </span>

                            @endif

                        </td>

                        {{-- STATUS --}}
                        <td>

                            @if(
                                in_array(
                                    $statusNormal,
                                    [
                                        'sesuai',
                                        'terpenuhi',
                                        'memenuhi'
                                    ]
                                )
                            )

                                <span
                                    class="status-badge status-sesuai"
                                >
                                    {{ $status ?: 'Sesuai' }}
                                </span>

                            @elseif(
                                in_array(
                                    $statusNormal,
                                    [
                                        'belum_sesuai',
                                        'tidak_sesuai',
                                        'belum_terpenuhi'
                                    ]
                                )
                            )

                                <span
                                    class="status-badge status-belum-sesuai"
                                >
                                    {{ $status }}
                                </span>

                            @elseif($status !== '')

                                <span class="status-badge">
                                    {{ $status }}
                                </span>

                            @else

                                -

                            @endif

                        </td>

                        {{-- BUKTI --}}
                        <td>

                            <div class="bukti-text">

                                @if(
                                    filled(
                                        $penerapan->link_bukti
                                    )
                                )

                                    Tersedia

                                @else

                                    Belum ada

                                @endif

                            </div>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty-value">
            Belum ada data penerapan standar.
        </div>

    @endif

</section>

{{-- ============================================================
     VII. TEMUAN AUDIT
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        VII. Temuan Audit
    </h2>

    @if($temuanList->isNotEmpty())

        <table class="official-table">

            <thead>

                <tr>
                    <th width="31">
                        No.
                    </th>

                    <th width="105">
                        Standar
                    </th>

                    <th width="160">
                        Indikator
                    </th>

                    <th>
                        Temuan Auditor
                    </th>

                    <th width="56">
                        Status
                    </th>
                </tr>

            </thead>

            <tbody>

                @foreach($temuanList as $temuan)

                    @php
                        $statusTemuan = strtolower(
                            trim(
                                (string) $temuan->status_temuan
                            )
                        );
                    @endphp

                    <tr>

                        <td style="text-align: center;">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{
                                $temuan
                                    ->penerapan
                                    ->standarmutuPeriode
                                    ->standarMutu
                                    ->nama_standar_mutu
                                ?? $temuan
                                    ->penerapan
                                    ->standarmutuPeriode
                                    ->standarMutu
                                    ->nama
                                ?? '-'
                            }}
                        </td>

                        <td>
                            {{
                                $temuan
                                    ->penerapan
                                    ->indikator
                                    ->deskripsi
                                ?? $temuan
                                    ->penerapan
                                    ->indikator
                                    ->indikator
                                ?? '-'
                            }}
                        </td>

                        <td>
                            {!! nl2br(e(
                                $temuan->temuan
                                ?? '-'
                            )) !!}
                        </td>

                        <td style="text-align: center;">

                            @if($statusTemuan === 'closed')

                                <span class="status status-closed">
                                    Closed
                                </span>

                            @else

                                <span class="status status-open">
                                    Open
                                </span>

                            @endif

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty-value">
            Tidak terdapat temuan audit pada periode ini.
        </div>

    @endif

</section>

<div class="page-break"></div>

{{-- ============================================================
     VIII. DETAIL TEMUAN DAN TINDAK LANJUT
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        VIII. Detail Temuan dan Tindak Lanjut
    </h2>

    @forelse($temuanList as $temuan)

        @php
            $statusTemuan = strtolower(
                trim(
                    (string) $temuan->status_temuan
                )
            );
        @endphp

        <div class="finding-card">

            <div class="finding-heading">

                Temuan {{ $loop->iteration }}

                &nbsp;·&nbsp;

                @if($statusTemuan === 'closed')
                    Status Closed
                @else
                    Status Open
                @endif

            </div>

            <table class="detail-table">

                <tr>
                    <td>Standar Mutu</td>
                    <td>:</td>
                    <td>
                        {{
                            $temuan
                                ->penerapan
                                ->standarmutuPeriode
                                ->standarMutu
                                ->nama_standar_mutu
                            ?? $temuan
                                ->penerapan
                                ->standarmutuPeriode
                                ->standarMutu
                                ->nama
                            ?? '-'
                        }}
                    </td>
                </tr>

                <tr>
                    <td>Indikator</td>
                    <td>:</td>
                    <td>
                        {{
                            $temuan
                                ->penerapan
                                ->indikator
                                ->deskripsi
                            ?? $temuan
                                ->penerapan
                                ->indikator
                                ->indikator
                            ?? '-'
                        }}
                    </td>
                </tr>

                <tr>
                    <td>Hasil Penerapan</td>
                    <td>:</td>
                    <td>
                        {!! nl2br(e(
                            $temuan
                                ->penerapan
                                ->deskripsi_hasil
                            ?? '-'
                        )) !!}
                    </td>
                </tr>

                <tr>
                    <td>Temuan Auditor</td>
                    <td>:</td>
                    <td>
                        {!! nl2br(e(
                            $temuan->temuan
                            ?? '-'
                        )) !!}
                    </td>
                </tr>

                <tr>
                    <td>Tanggapan Auditee</td>
                    <td>:</td>
                    <td>

                        @forelse(
                            $temuan->tanggapan
                            as $tanggapan
                        )

                            {{ $loop->iteration }}.
                            {!! nl2br(e(
                                $tanggapan->tanggapan
                                ?? '-'
                            )) !!}

                            @if(!$loop->last)
                                <br><br>
                            @endif

                        @empty
                            Belum ada tanggapan.
                        @endforelse

                    </td>
                </tr>

                <tr>
                    <td>Akar Masalah</td>
                    <td>:</td>
                    <td>

                        @forelse(
                            $temuan->akarMasalah
                            as $akar
                        )

                            {{ $loop->iteration }}.
                            {!! nl2br(e(
                                $akar->akar_masalah
                                ?? '-'
                            )) !!}

                            @if(!$loop->last)
                                <br><br>
                            @endif

                        @empty
                            Belum ada akar masalah.
                        @endforelse

                    </td>
                </tr>

            </table>

        </div>

    @empty

        <div class="empty-value">
            Tidak ada detail temuan untuk ditampilkan.
        </div>

    @endforelse

</section>

{{-- ============================================================
     IX. REKOMENDASI PENINGKATAN
============================================================ --}}

{{-- ============================================================
     IX. REKOMENDASI PENINGKATAN
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        IX. Rekomendasi Peningkatan
    </h2>

    @if($rekomendasiList->isNotEmpty())

        <table class="official-table">

            <thead>

                <tr>

                    <th width="31">
                        No.
                    </th>

                    <th width="120">
                        Aspek
                    </th>

                    <th width="190">
                        Deskripsi
                    </th>

                    <th>
                        Rekomendasi
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach(
                    $rekomendasiList
                    as $rekomendasi
                )

                    <tr>

                        <td style="text-align: center;">
                            {{ $loop->iteration }}
                        </td>

                        <td>

                            {!! nl2br(e(
                                $rekomendasi->aspek
                                ?? '-'
                            )) !!}

                        </td>

                        <td>

                            {!! nl2br(e(
                                $rekomendasi->deskripsi
                                ?? '-'
                            )) !!}

                        </td>

                        <td>

                            {!! nl2br(e(
                                $rekomendasi->rekomendasi
                                ?? '-'
                            )) !!}

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty-value">
            Belum ada rekomendasi peningkatan.
        </div>

    @endif

</section>

{{-- ============================================================
     X. KESIMPULAN AUDIT
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        X. Kesimpulan Audit
    </h2>

    @forelse(
        $periode->kesimpulanAudit
        as $kesimpulan
    )

        <div class="finding-card">

            {!! nl2br(e(
                $kesimpulan->kesimpulan
                ?? '-'
            )) !!}

        </div>

    @empty

        <p class="paragraph">
            Berdasarkan hasil Audit Mutu Internal, terdapat
            <strong>
                {{ $statistik['jumlah_temuan'] }}
            </strong>
            temuan audit, terdiri dari
            <strong>
                {{ $statistik['jumlah_temuan_open'] }}
            </strong>
            temuan berstatus open dan
            <strong>
                {{ $statistik['jumlah_temuan_closed'] }}
            </strong>
            temuan berstatus closed.
        </p>

    @endforelse

    <div class="commitment-box">

        Unit kerja berkomitmen untuk menindaklanjuti seluruh
        temuan audit sesuai rekomendasi, akar masalah, dan target
        penyelesaian yang telah disepakati sebagai bagian dari
        peningkatan mutu secara berkelanjutan.

    </div>

</section>

{{-- ============================================================
     XI. LAMPIRAN AUDIT
============================================================ --}}

<section class="section">

    <h2 class="section-title">
        XI. Daftar Lampiran Audit
    </h2>

    @if($periode->lampiran->isNotEmpty())

        <table class="official-table">

            <thead>

                <tr>
                    <th width="38">
                        No.
                    </th>

                    <th>
                        Lampiran/File Audit
                    </th>

                    <th width="140">
                        Pengunggah
                    </th>
                </tr>

            </thead>

            <tbody>

                @foreach($periode->lampiran as $lampiran)

                    <tr>

                        <td style="text-align: center;">
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            <span class="link-text">
                                {{
                                    $lampiran->link_file
                                    ?? $lampiran->file
                                    ?? '-'
                                }}
                            </span>
                        </td>

                        <td>
                            {{
                                $lampiran->user->nama
                                ?? $lampiran->user->name
                                ?? '-'
                            }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <div class="empty-value">
            Belum ada lampiran audit yang dicatat.
        </div>

    @endif

</section>

{{-- ============================================================
     XII. PENGESAHAN
============================================================ --}}

<section class="section avoid-break">

    <h2 class="section-title">
        XII. Pengesahan
    </h2>

    <p class="paragraph">
        Laporan ini disusun berdasarkan hasil Audit Mutu Internal
        dan data yang tercatat dalam Sistem Informasi SPMI
        Politeknik Negeri Banyuwangi.
    </p>

    <table class="signature-table">

        <tr>

            <td>

                Ketua Tim Auditor

                <div class="signature-space"></div>

                <div class="signature-name">
                    {{
                        $ketuaAuditor->user->nama
                        ?? $ketuaAuditor->user->name
                        ?? '________________________'
                    }}
                </div>

            </td>

            <td>

                Auditee

                <div class="signature-space"></div>

                <div class="signature-name">
                    {{
                        $auditeeList->first()->nama
                        ?? $auditeeList->first()->name
                        ?? '________________________'
                    }}
                </div>

            </td>

            <td>

                Ketua Unit Kerja

                <div class="signature-space"></div>

                <div class="signature-name">
                    ________________________
                </div>

            </td>

        </tr>

    </table>

</section>

{{-- ============================================================
     NOMOR HALAMAN
============================================================ --}}

<script type="text/php">

    if (isset($pdf)) {

        $font = $fontMetrics->get_font(
            "DejaVu Sans",
            "normal"
        );

        $pdf->page_text(
            470,
            813,
            "Halaman {PAGE_NUM} dari {PAGE_COUNT}",
            $font,
            7,
            array(0.35, 0.39, 0.45)
        );

        $pdf->page_text(
            45,
            813,
            "Laporan Audit Mutu Internal",
            $font,
            7,
            array(0.35, 0.39, 0.45)
        );

    }

</script>

</body>

</html>