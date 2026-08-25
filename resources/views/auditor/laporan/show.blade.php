@extends('layouts.auditor')

@section('content')

@php
    $namaUnitKerja =
        $periode->unitKerja->nama
        ?? $periode->unitKerja->nama_unit_kerja
        ?? '-';

    $namaStandar =
        $periode->standarMutu->nama_standar_mutu
        ?? $periode->standarMutu->nama
        ?? '-';

    $statusPeriode = strtolower(
        trim($periode->status ?? '')
    );
@endphp

<div class="breadcrumb">
    Dashboard / Laporan AMI / Detail
</div>

<div class="card">

    <div class="card-header periode-header">

        <div class="header-left">

            <h2 class="card-title">
                Laporan Audit Mutu Internal
            </h2>

            <p>
                Laporan hasil pelaksanaan Audit Mutu Internal
                tahun {{ $periode->tahun }}.
            </p>

        </div>

        <a
            href="{{ route('auditor.laporan.index') }}"
            class="btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>

    </div>

    {{-- IDENTITAS AUDIT --}}
    <div style="padding: 24px;">

        <h3 style="margin: 0 0 15px;">
            Identitas Audit
        </h3>

        <table class="detail-table">

            <tbody>

                <tr>
                    <th width="240">Tahun AMI</th>
                    <td>{{ $periode->tahun ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Standar Mutu</th>
                    <td>{{ $namaStandar }}</td>
                </tr>

                <tr>
                    <th>Unit Kerja</th>
                    <td>{{ $namaUnitKerja }}</td>
                </tr>

                <tr>
                    <th>Tujuan Audit</th>
                    <td>
                        {{
                            $periode->tujuan_audit
                            ?? $periode->tujuan
                            ?? '-'
                        }}
                    </td>
                </tr>

                <tr>
                    <th>Lingkup Audit</th>
                    <td>
                        {{
                            $periode->lingkup_audit
                            ?? $periode->lingkup
                            ?? '-'
                        }}
                    </td>
                </tr>

                <tr>
                    <th>Tanggal Pelaksanaan</th>
                    <td>
                        {{
                            $periode->tanggal_buka_ami
                            ?? $periode->tanggal_mulai
                            ?? '-'
                        }}

                        sampai

                        {{
                            $periode->tanggal_tutup_ami
                            ?? $periode->tanggal_selesai
                            ?? '-'
                        }}
                    </td>
                </tr>

                <tr>
                    <th>Waktu Audit</th>
                    <td>
                        {{
                            $periode->waktu_audit
                            ?? $periode->waktu
                            ?? '-'
                        }}
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>

                        @if($statusPeriode === 'berjalan')

                            <span class="badge-open">
                                Berjalan
                            </span>

                        @elseif(
                            in_array(
                                $statusPeriode,
                                ['selesai', 'ditutup'],
                                true
                            )
                        )

                            <span class="badge-close">
                                {{ ucfirst($statusPeriode) }}
                            </span>

                        @else

                            {{ ucfirst($statusPeriode ?: '-') }}

                        @endif

                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    {{-- RINGKASAN --}}
    <div style="padding: 0 24px 24px;">

        <h3 style="margin: 0 0 15px;">
            Ringkasan Audit
        </h3>

        <div style="
            display:grid;
            grid-template-columns:repeat(4, minmax(0, 1fr));
            gap:15px;
        ">

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahStandar }}
                </div>
                <div>Standar</div>
            </div>

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahPenerapan }}
                </div>
                <div>Penerapan</div>
            </div>

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahBukti }}
                </div>
                <div>Bukti Pendukung</div>
            </div>

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahTemuan }}
                </div>
                <div>Temuan</div>
            </div>

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahTemuanTerbuka }}
                </div>
                <div>Temuan Open</div>
            </div>

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahTemuanDitutup }}
                </div>
                <div>Temuan Closed</div>
            </div>

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahTanggapan }}
                </div>
                <div>Tanggapan</div>
            </div>

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahAkarMasalah }}
                </div>
                <div>Akar Masalah</div>
            </div>

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahRekomendasi }}
                </div>
                <div>Rekomendasi</div>
            </div>

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahKesimpulan }}
                </div>
                <div>Kesimpulan</div>
            </div>

            <div class="card" style="padding:18px;">
                <div style="font-size:28px;font-weight:700;">
                    {{ $jumlahLampiran }}
                </div>
                <div>Lampiran</div>
            </div>

        </div>

    </div>

    {{-- TIM AUDIT --}}
    <div style="padding: 0 24px 24px;">

        <h3 style="margin: 0 0 15px;">
            Tim Audit
        </h3>

        <div class="table-wrapper">

            <table class="custom-table">

                <thead>
                    <tr>
                        <th width="65">No.</th>
                        <th>Nama Auditor</th>
                        <th>Email</th>
                        <th>Peran</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($periode->tim as $tim)

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{
                                    $tim->user->nama
                                    ?? $tim->user->name
                                    ?? '-'
                                }}
                            </td>

                            <td>
                                {{ $tim->user->email ?? '-' }}
                            </td>

                            <td>
                                {{
                                    $tim->peran
                                    ?? $tim->role
                                    ?? '-'
                                }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" style="text-align:center;">
                                Tim audit belum tersedia.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- JADWAL --}}
    <div style="padding: 0 24px 24px;">

        <h3 style="margin: 0 0 15px;">
            Jadwal Audit
        </h3>

        <div class="table-wrapper">

            <table class="custom-table">

                <thead>
                    <tr>
                        <th width="65">No.</th>
                        <th>Kegiatan</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($periode->jadwal as $jadwal)

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{
                                    $jadwal->kegiatan
                                    ?? $jadwal->agenda
                                    ?? '-'
                                }}
                            </td>

                            <td>
                                {{ $jadwal->tanggal ?? '-' }}
                            </td>

                            <td>
                                {{ $jadwal->waktu ?? '-' }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" style="text-align:center;">
                                Jadwal audit belum tersedia.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- PENERAPAN DAN TEMUAN --}}
    <div style="padding: 0 24px 24px;">

        <h3 style="margin: 0 0 15px;">
            Penerapan Standar dan Temuan Audit
        </h3>

        @forelse(
            $periode->standarMutuPeriode
            as $standarPeriode
        )

            <div
                class="card"
                style="margin-bottom:20px;padding:20px;"
            >

                <h4 style="margin:0 0 15px;">

                    {{
                        $standarPeriode
                            ->standarMutu
                            ->nama_standar_mutu
                        ?? $namaStandar
                    }}

                </h4>

                @forelse(
                    $standarPeriode->penerapanStandar
                    as $penerapan
                )

                    <div style="
                        margin-bottom:20px;
                        padding:18px;
                        border:1px solid #e4e7ec;
                        border-radius:12px;
                    ">

                        <table class="detail-table">

                            <tbody>

                                <tr>
                                    <th width="220">
                                        Penerapan Standar
                                    </th>

                                    <td>
                                        {{
                                            $penerapan
                                                ->indikator
                                                ->deskripsi
                                            ?? $penerapan
                                                ->indikator
                                                ->indikator
                                            ?? '-'
                                        }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Hasil Penerapan
                                    </th>

                                    <td>
                                        {!! nl2br(e(
                                            $penerapan
                                                ->deskripsi_hasil
                                            ?? '-'
                                        )) !!}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Auditee
                                    </th>

                                    <td>
                                        {{
                                            $penerapan
                                                ->user
                                                ->nama
                                            ?? $penerapan
                                                ->user
                                                ->name
                                            ?? '-'
                                        }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>
                                        Bukti Pendukung
                                    </th>

                                    <td>

                                        @if(
                                            filled(
                                                $penerapan->link_bukti
                                            )
                                        )

                                            <a
                                                href="{{ $penerapan->link_bukti }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn-detail"
                                            >
                                                <i class="bi bi-box-arrow-up-right"></i>
                                                Lihat Bukti
                                            </a>

                                        @else

                                            Belum ada bukti.

                                        @endif

                                    </td>
                                </tr>

                            </tbody>

                        </table>

                        <h4 style="margin:20px 0 12px;">
                            Temuan Auditor
                        </h4>

                        @forelse($penerapan->temuan as $temuan)

                            @php
                                $statusTemuan = strtolower(
                                    trim(
                                        $temuan->status_temuan
                                        ?? ''
                                    )
                                );
                            @endphp

                            <div style="
                                margin-bottom:15px;
                                padding:16px;
                                border:1px solid #d0d5dd;
                                border-radius:10px;
                            ">

                                <table class="detail-table">

                                    <tbody>

                                        <tr>
                                            <th width="220">
                                                Temuan
                                            </th>

                                            <td>
                                                {!! nl2br(e(
                                                    $temuan->temuan
                                                    ?? '-'
                                                )) !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Status</th>

                                            <td>

                                                @if(
                                                    $statusTemuan
                                                    === 'open'
                                                )

                                                    <span class="badge-open">
                                                        Open
                                                    </span>

                                                @elseif(
                                                    $statusTemuan
                                                    === 'closed'
                                                )

                                                    <span class="badge-close">
                                                        Closed
                                                    </span>

                                                @else

                                                    {{
                                                        ucfirst(
                                                            $statusTemuan
                                                            ?: '-'
                                                        )
                                                    }}

                                                @endif

                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Tanggapan Auditee
                                            </th>

                                            <td>

                                                @forelse(
                                                    $temuan->tanggapan
                                                    as $tanggapan
                                                )

                                                    <div style="margin-bottom:8px;">

                                                        <strong>
                                                            {{ $loop->iteration }}.
                                                        </strong>

                                                        {!! nl2br(e(
                                                            $tanggapan
                                                                ->tanggapan
                                                            ?? '-'
                                                        )) !!}

                                                    </div>

                                                @empty

                                                    Belum ada tanggapan.

                                                @endforelse

                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                Akar Masalah
                                            </th>

                                            <td>

                                                @forelse(
                                                    $temuan->akarMasalah
                                                    as $akar
                                                )

                                                    <div style="margin-bottom:8px;">

                                                        <strong>
                                                            {{ $loop->iteration }}.
                                                        </strong>

                                                        {!! nl2br(e(
                                                            $akar->deskripsi
                                                            ?? '-'
                                                        )) !!}

                                                    </div>

                                                @empty

                                                    Belum ada akar masalah.

                                                @endforelse

                                            </td>
                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        @empty

                            <div style="
                                padding:15px;
                                background:#f9fafb;
                                border-radius:8px;
                                text-align:center;
                            ">
                                Belum ada temuan untuk penerapan ini.
                            </div>

                        @endforelse

                        <h4 style="margin:20px 0 12px;">
                            Rekomendasi Peningkatan
                        </h4>

                        @forelse(
                            $penerapan->rekomendasi
                            as $rekomendasi
                        )

                            <div style="
                                margin-bottom:10px;
                                padding:14px;
                                border:1px solid #e4e7ec;
                                border-radius:8px;
                            ">

                                @if(filled($rekomendasi->aspek))

                                    <div style="margin-bottom:8px;">
                                        <strong>Aspek:</strong>

                                        {{ $rekomendasi->aspek }}
                                    </div>

                                @endif

                                @if(filled($rekomendasi->deskripsi))

                                    <div style="margin-bottom:8px;">
                                        <strong>Deskripsi:</strong>

                                        {{ $rekomendasi->deskripsi }}
                                    </div>

                                @endif

                                <div>
                                    <strong>Rekomendasi:</strong>

                                    {{
                                        $rekomendasi->rekomendasi
                                        ?? '-'
                                    }}
                                </div>

                            </div>

                        @empty

                            <div>
                                Belum ada rekomendasi.
                            </div>

                        @endforelse

                    </div>

                @empty

                    <div style="
                        padding:20px;
                        text-align:center;
                        background:#f9fafb;
                        border-radius:10px;
                    ">
                        Belum ada penerapan standar.
                    </div>

                @endforelse

            </div>

        @empty

            <div style="
                padding:20px;
                text-align:center;
                background:#f9fafb;
                border-radius:10px;
            ">
                Standar mutu belum dihubungkan dengan periode ini.
            </div>

        @endforelse

    </div>

    {{-- KESIMPULAN --}}
    <div style="padding: 0 24px 24px;">

        <h3 style="margin: 0 0 15px;">
            Kesimpulan Audit
        </h3>

        @forelse(
            $periode->kesimpulanAudit
            as $kesimpulan
        )

            <div style="
                margin-bottom:12px;
                padding:16px;
                border:1px solid #e4e7ec;
                border-radius:10px;
            ">

                <strong>
                    Kesimpulan {{ $loop->iteration }}
                </strong>

                <div style="margin-top:8px;">
                    {!! nl2br(e(
                        $kesimpulan->kesimpulan
                        ?? '-'
                    )) !!}
                </div>

            </div>

        @empty

            <div style="
                padding:20px;
                text-align:center;
                background:#f9fafb;
                border-radius:10px;
            ">
                Kesimpulan audit belum tersedia.
            </div>

        @endforelse

    </div>

    {{-- LAMPIRAN --}}
    <div style="padding: 0 24px 30px;">

        <h3 style="margin: 0 0 15px;">
            Lampiran Audit
        </h3>

        <div class="table-wrapper">

            <table class="custom-table">

                <thead>
                    <tr>
                        <th width="65">No.</th>
                        <th>Link Lampiran</th>
                        <th>Pengunggah</th>
                        <th width="130">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($periode->lampiran as $lampiran)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td style="word-break:break-all;">
                                {{ $lampiran->link_file ?? '-' }}
                            </td>

                            <td>
                                {{
                                    $lampiran->user->nama
                                    ?? $lampiran->user->name
                                    ?? '-'
                                }}
                            </td>

                            <td>

                                @if(filled($lampiran->link_file))

                                    <a
                                        href="{{ $lampiran->link_file }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="btn-detail"
                                    >
                                        <i class="bi bi-box-arrow-up-right"></i>
                                        Buka
                                    </a>

                                @else

                                    -

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" style="text-align:center;">
                                Lampiran belum tersedia.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection