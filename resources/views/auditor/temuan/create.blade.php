@extends('layouts.auditor')

@section('content')

<h3 class="breadcrumb">
    Dashboard / Audit Mutu Internal / Tambah Temuan Audit
</h3>

<div class="card">

    <div class="card-header periode-header">

        <div class="header-left">

            <h4>Tambah Temuan Audit</h4>

        </div>

    </div>

    <form action="{{ route('auditor.temuan.store') }}"
          method="POST">

        @csrf

        <div class="form-group">

            <label>Pilih Pertanyaan AMI</label>

            <select
                name="id_pertanyaan"
                class="form-control"
                required>

                <option value="">
                    -- Pilih Pertanyaan AMI --
                </option>

                @foreach($pertanyaan as $item)

                <option value="{{ $item->id }}">

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
                placeholder="Masukkan Temuan Audit..."
                required></textarea>

        </div>

        <div class="form-group">

            <label>Status Temuan</label>

            <select
                name="status_temuan"
                class="form-control">

                <option value="open">

                    Open

                </option>

                <option value="closed">

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

                Simpan

            </button>

        </div>

    </form>

</div>

@endsection