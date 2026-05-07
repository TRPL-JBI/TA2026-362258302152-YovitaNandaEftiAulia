@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <h3 class="breadcrumb">Dashboard / Detail Standar Mutu</h3>

    <div class="card">

        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Standar Mutu</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $data->nama_standar_mutu }}</td>
                </tr>
            </tbody>

        </table>

    </div>

</div>

@endsection