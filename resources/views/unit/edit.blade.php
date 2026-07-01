@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <h3 class="breadcrumb">Dashboard / Edit Unit Kerja</h3>

    <div class="form-card">

        <h2 class="form-title">Form Edit Unit Kerja</h2>

        <form action="{{ route('unit-kerja.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Unit Kerja</label>
                <input type="text" name="nama" value="{{ $data->nama }}" required>
            </div>

            <div class="form-group">
                <label>Kategori Unit Kerja</label>
                <select name="kategori_unit_kerja" required>
                    <option value="Akademik" {{ $data->kategori_unit_kerja == 'Akademik' ? 'selected' : '' }}>
                        Akademik
                    </option>
                    <option value="Non Akademik" {{ $data->kategori_unit_kerja == 'Non Akademik' ? 'selected' : '' }}>
                        Non Akademik
                    </option>
                </select>
            </div>

            <div class="form-action">
                <button type="submit" class="btn-save">Simpan</button>
                <a href="{{ route('unit-kerja.index') }}" class="btn-cancel">Batal</a>
            </div>

        </form>

    </div>

</div>

@endsection@extends('layouts.app')

@section('content')

<div class="content-wrapper">

    <h3 class="breadcrumb">Dashboard / Edit Unit Kerja</h3>

    <div class="form-card">

        <h2 class="form-title">Form Edit Unit Kerja</h2>

        <form action="{{ route('unit-kerja.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Unit Kerja</label>
                <input type="text" name="nama" value="{{ $data->nama }}" required>
            </div>

            <div class="form-group">
                <label>Kategori Unit Kerja</label>
                <select name="kategori_unit_kerja" required>
                    <option value="Akademik" {{ $data->kategori_unit_kerja == 'Akademik' ? 'selected' : '' }}>
                        Akademik
                    </option>
                    <option value="Non Akademik" {{ $data->kategori_unit_kerja == 'Non Akademik' ? 'selected' : '' }}>
                        Non Akademik
                    </option>
                </select>
            </div>

            <div class="form-action">
                <button type="submit" class="btn-save">Simpan</button>
                <a href="{{ route('unit-kerja.index') }}" class="btn-cancel">Batal</a>
            </div>

        </form>

    </div>

</div>

@endsection

