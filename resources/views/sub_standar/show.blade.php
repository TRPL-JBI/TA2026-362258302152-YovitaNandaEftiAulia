@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">

Dashboard / Detail Sub Standar

</h3>

<div class="card">

    <table class="table-detail">

        <tr>

            <th width="220">

                Nama Sub Standar

            </th>

            <td>

                {{ $subStandar->nama_standar }}

            </td>

        </tr>

        <tr>

            <th>

                Jumlah Indikator

            </th>

            <td>

                {{ $subStandar->indikator->count() }}

            </td>

        </tr>

    </table>

</div>

@endsection