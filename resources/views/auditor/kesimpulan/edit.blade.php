@extends('layouts.auditor')

@push('styles')

<link
    rel="stylesheet"
    href="{{ asset('css/app/18-auditor-kesimpulan.css') }}"
>

@endpush


@section('content')


<div class="audit-form-page">


    {{-- =====================================================
         BREADCRUMB
    ====================================================== --}}

    <div class="audit-form-breadcrumb">


        <span>
            Dashboard
        </span>


        <i class="bi bi-chevron-right"></i>


        <span>
            Audit Mutu Internal
        </span>


        <i class="bi bi-chevron-right"></i>


        <span>
            Kesimpulan Audit
        </span>


        <i class="bi bi-chevron-right"></i>


        <strong>
            Edit
        </strong>


    </div>




    {{-- =====================================================
         CARD FORM
    ====================================================== --}}

    <section class="audit-form-card">


        <div class="audit-form-header">


            <div>


                <span class="audit-form-label">
                    KESIMPULAN AUDIT
                </span>


                <h2>
                    Edit Kesimpulan Audit
                </h2>


                <p>
                    Perbarui informasi kesimpulan hasil pelaksanaan
                    Audit Mutu Internal.
                </p>


            </div>



            <div class="audit-form-header-icon">

                <i class="bi bi-pencil-square"></i>

            </div>


        </div>





        {{-- =================================================
             ERROR VALIDASI
        ================================================== --}}


        @if($errors->any())


            <div class="audit-form-alert">


                <i class="bi bi-exclamation-circle-fill"></i>


                <div>


                    <strong>
                        Data belum dapat diperbarui.
                    </strong>


                    <ul>


                        @foreach($errors->all() as $error)


                            <li>
                                {{ $error }}
                            </li>


                        @endforeach


                    </ul>


                </div>


            </div>


        @endif





        {{-- =================================================
             FORM
        ================================================== --}}


        <form
            action="{{ route(
                'auditor.kesimpulan.update',
                $kesimpulan->id
            ) }}"
            method="POST"
        >


            @csrf

            @method('PUT')




            {{-- PERIODE AMI --}}


            <div class="audit-form-group">


                <label for="id_periode_ami">


                    Periode AMI


                    <span class="required-mark">
                        *
                    </span>


                </label>




                <select

                    name="id_periode_ami"

                    id="id_periode_ami"

                    class="audit-form-control"

                    required

                >


                    <option value="">

                        -- Pilih Periode AMI --

                    </option>



                    @foreach($periodeAmi as $periode)



                        <option

                            value="{{ $periode->id }}"

                            {{
                                (string) old(
                                    'id_periode_ami',
                                    $kesimpulan->id_periode_ami
                                )
                                ===
                                (string) $periode->id
                                    ? 'selected'
                                    : ''
                            }}

                        >


                            Periode {{ $periode->tahun }}


                            @if(
                                !empty($periode->status)
                            )

                                -
                                {{ ucfirst($periode->status) }}

                            @endif


                        </option>



                    @endforeach



                </select>




                @error('id_periode_ami')


                    <small class="audit-form-error">

                        {{ $message }}

                    </small>


                @enderror



            </div>






            {{-- KESIMPULAN --}}


            <div class="audit-form-group">


                <label for="kesimpulan">


                    Kesimpulan Audit


                    <span class="required-mark">
                        *
                    </span>


                </label>




                <textarea

                    name="kesimpulan"

                    id="kesimpulan"

                    rows="7"

                    class="audit-form-control"

                    placeholder="Tuliskan kesimpulan hasil Audit Mutu Internal..."

                    required

                >{{ old(
                    'kesimpulan',
                    $kesimpulan->kesimpulan
                ) }}</textarea>





                @error('kesimpulan')


                    <small class="audit-form-error">

                        {{ $message }}

                    </small>


                @enderror



            </div>






            {{-- BUTTON --}}


            <div class="audit-form-actions">



                <a

                    href="{{ route(
                        'auditor.kesimpulan.show',
                        $kesimpulan->id
                    ) }}"

                    class="audit-form-button button-back"

                >


                    <i class="bi bi-arrow-left"></i>


                    Kembali


                </a>





                <button

                    type="submit"

                    class="audit-form-button button-save"

                >


                    <i class="bi bi-check-lg"></i>


                    Simpan Perubahan


                </button>



            </div>




        </form>



    </section>



</div>



@endsection