@extends('layouts.app')

@section('content')

<h3>Edit Standar</h3>

<form action="{{ route('isi.update', $data->id) }}" method="POST">
    @csrf
    @method('PUT')

    <input type="text" name="nama_standar" value="{{ $data->nama_standar }}">

    <button type="submit">Update</button>
</form>

@endsection