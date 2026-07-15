@extends('layouts.auditee')

@section('content')

<div class="breadcrumb">

    Dashboard / Penerapan Standar / Detail

</div>

<div class="card">

    <div class="card-header">

        <h2 class="card-title">

            Detail Penerapan Standar

        </h2>

    </div>

    <table class="detail-table">

        <tbody>

            <tr>

                <th width="250">

                    Standar Mutu

                </th>

                <td>

                    {{ $data->standarmutuPeriode->standarMutu->nama_standar_mutu }}

                </td>

            </tr>

            <tr>

                <th>

                    Deskripsi Hasil

                </th>

                <td>

                    {!! nl2br(e($data->deskripsi_hasil)) !!}

                </td>

            </tr>

            <tr>

                <th>

                    Link Bukti

                </th>

                <td>

                    @if($data->link_bukti)

                        <a href="{{ $data->link_bukti }}"
                           target="_blank">

                            {{ $data->link_bukti }}

                        </a>

                    @else

                        -

                    @endif

                </td>

            </tr>

            <tr>

                <th>

                    Dibuat Oleh

                </th>

                <td>

                    {{ $data->user->nama }}

                </td>

            </tr>

        </tbody>

    </table>

    <div class="form-footer">

        <a href="{{ route('auditee.penerapan.index',$data->standarmutuPeriode->id_periode_ami) }}"
           class="btn-secondary">

            <i class="bi bi-arrow-left"></i>

            Kembali

        </a>

    </div>

</div>

@endsection