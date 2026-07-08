@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">

    Dashboard /

    Audit Mutu Internal /

    Edit Temuan Audit

</h3>

<div class="form-container">

    <div class="form-card">

        <h3 class="form-title">

            Edit Temuan Audit

        </h3>

        <form
            action="{{ route('auditor.temuan.update',$temuan->id) }}"
            method="POST">

            @csrf
            @method('PUT')

            <!-- ==========================================
                PERTANYAAN AUDIT
            =========================================== -->

            <div class="form-group">

                <label>

                    Pertanyaan Audit

                </label>

                <select
                    name="id_pertanyaan"
                    required>

                    @foreach($pertanyaan as $item)

                        <option
                            value="{{ $item->id }}"
                            {{ $temuan->id_pertanyaan==$item->id ? 'selected' : '' }}>

                            {{ $item->pertanyaan }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- ==========================================
                TEMUAN AUDIT
            =========================================== -->

            <div class="form-group">

                <label>

                    Temuan Audit

                </label>

                <textarea
                    name="temuan"
                    rows="6"
                    required>{{ old('temuan',$temuan->temuan) }}</textarea>

            </div>

            <!-- ==========================================
                STATUS
            =========================================== -->

            <div class="form-group">

                <label>

                    Status Temuan

                </label>

                <select
                    name="status_temuan"
                    required>

                    <option
                        value="Open"
                        {{ $temuan->status_temuan=='Open' ? 'selected' : '' }}>

                        Open

                    </option>

                    <option
                        value="Closed"
                        {{ $temuan->status_temuan=='Closed' ? 'selected' : '' }}>

                        Closed

                    </option>

                </select>

            </div>

            <!-- ==========================================
                BUTTON
            =========================================== -->

            <div class="form-action">

                <button
                    type="submit"
                    class="btn-save">

                    <i class="bi bi-check-circle"></i>

                    Update

                </button>

                <a
                    href="{{ route('auditor.temuan.index') }}"
                    class="btn-cancel">

                    <i class="bi bi-arrow-left"></i>

                    Batal

                </a>

            </div>

        </form>

    </div>

</div>

@endsection