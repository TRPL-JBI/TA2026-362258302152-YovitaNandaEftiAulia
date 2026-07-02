@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Audit Mutu Internal / Edit Temuan Audit
</h3>

<!-- TAB MENU -->
<div class="tab-menu">

    <a href="{{ route('auditor.temuan.index') }}"
       class="active">
        Temuan Audit
    </a>

    <a href="#">
        Tanggapan Auditee
    </a>

    <a href="#">
        Akar Masalah
    </a>

    <a href="#">
        Rekomendasi
    </a>

    <a href="#">
        Kesimpulan
    </a>

    <a href="#">
        Lampiran
    </a>

</div>

<div class="card">

    <div class="card-header periode-header">

        <div class="header-left">

            <h4>Edit Temuan Audit</h4>

        </div>

    </div>

    <form action="{{ route('auditor.temuan.update',$data->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="form-group">

            <label>Pilih Pertanyaan AMI</label>

            <select
                name="id_pertanyaan"
                class="form-control"
                required>

                @foreach($pertanyaan as $item)

                <option value="{{ $item->id }}"
                    {{ $item->id == $data->id_pertanyaan ? 'selected' : '' }}>

                    {{ $item->penerapanStandar->standarMutuPeriodeAmi->periodeAmi->tahun }}

                    |

                    {{ $item->penerapanStandar->standarMutuPeriodeAmi->periodeAmi->unitKerja->nama }}

                    |

                    {{ $item->penerapanStandar->standarMutuPeriodeAmi->standarMutu->nama_standar_mutu }}

                    |

                    {{ Str::limit($item->pertanyaan,60) }}

                </option>

                @endforeach

            </select>

        </div>

        <div class="form-group">

            <label>Temuan Audit</label>

            <textarea
                name="temuan"
                rows="7"
                class="form-control"
                required>{{ $data->temuan }}</textarea>

        </div>

        <div class="form-group">

            <label>Status Temuan</label>

            <select
                name="status_temuan"
                class="form-control">

                <option value="open"
                    {{ $data->status_temuan=='open' ? 'selected' : '' }}>
                    Open
                </option>

                <option value="closed"
                    {{ $data->status_temuan=='closed' ? 'selected' : '' }}>
                    Closed
                </option>

            </select>

        </div>

        <div
            style="
                display:flex;
                justify-content:end;
                gap:10px;
                margin-top:25px;">

            <a href="{{ route('auditor.temuan.index') }}"
               class="btn-cancel">

                Batal

            </a>

            <button
                type="submit"
                class="btn-save">

                Update

            </button>

        </div>

    </form>

</div>

@endsection