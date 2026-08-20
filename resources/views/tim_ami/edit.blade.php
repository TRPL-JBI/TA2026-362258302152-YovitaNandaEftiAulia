@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Periode AMI / Tim AMI / Edit
</h3>

<div class="form-container">

    <div class="form-card">

        <h3 class="form-title">
            Edit Tim AMI
        </h3>

        <form action="{{ route('tim-ami.update', $tim->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="form-group">

                <label>Nama Tim AMI</label>

                <select name="id_user" required>

                    @foreach($users as $user)

                        <option
                            value="{{ $user->id }}"
                            {{ $tim->id_user == $user->id ? 'selected' : '' }}>

                            {{ $user->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label>Role</label>

                <select name="role" required>

                    <option
                        value="ketua auditor"
                        {{ $tim->role == 'ketua auditor' ? 'selected' : '' }}>

                        Ketua Auditor

                    </option>

                    <option
                        value="auditor"
                        {{ $tim->role == 'auditor' ? 'selected' : '' }}>

                        Auditor

                    </option>

                    <option
                        value="auditee"
                        {{ $tim->role == 'auditee' ? 'selected' : '' }}>

                        Auditee

                    </option>

                </select>

            </div>

            <div class="form-action">

                <button
                    type="submit"
                    class="btn-save">

                    Simpan

                </button>

                <a
                    href="{{ route('tim-ami.index', $tim->id_periode_ami) }}"
                    class="btn-cancel">

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection
