@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <h3 class="breadcrumb">Dashboard / Detail Unit Kerja</h3>

    <div class="card">

        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Unit Kerja</th>
                    <th>Kategori</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->kategori_unit_kerja }}</td>
                </tr>
            </tbody>

        </table>

    </div>

</div>

@endsection