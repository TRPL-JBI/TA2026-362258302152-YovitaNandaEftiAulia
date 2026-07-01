@extends('layouts.app')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Periode AMI / Tim AMI / Tambah
</h3>

<div class="form-container">

    <div class="form-card">

        <h3 class="form-title">
            Tambah Tim AMI
        </h3>

        <form action="{{ route('tim-ami.store',$periodeAmi->id) }}"
              method="POST">

            @csrf

            <div class="form-group">

                <label>
                    Nama Auditor
                </label>

                <select name="id_user" required>

                    <option value="">
                        -- Pilih Auditor --
                    </option>

                    @foreach($users as $user)

                        <option value="{{ $user->id }}">

                            {{ $user->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label>
                    Role
                </label>

                <select name="role" required>

                    <option value="">
                        -- Pilih Role --
                    </option>

                    <option value="ketua auditor">
                        Ketua Auditor
                    </option>

                    <option value="auditor">
                        Auditor
                    </option>

                    <option value="auditee">
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

                <a href="{{ route('tim-ami.index',$periodeAmi->id) }}"
                   class="btn-cancel">

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection