@extends('layouts.auditor')

@section('content')

<div class="dashboard-container">

    <!-- WELCOME -->
    <div class="welcome-card">

        <h3>
            Selamat Datang Auditor Kepala Di Sistem Informasi SPMI
        </h3>

        <p class="welcome-user">

            {{ session('user')['nama'] }}

        </p>

        <p>
            Politeknik Negeri Banyuwangi
        </p>

    </div>

    <!-- STATISTIK -->
    <div class="statistik-wrapper">

        <div class="stat-card blue">

            <h5>Total Standar</h5>

            <h2>{{ $totalStandar }}</h2>

        </div>

        <div class="stat-card green">

            <h5>Periode AMI Aktif</h5>

            <h2>{{ $periodeAktif }}</h2>

        </div>

        <div class="stat-card red">

            <h5>Jumlah Temuan</h5>

            <h2>{{ $jumlahTemuan }}</h2>

        </div>

    </div>

    <!-- GRAFIK -->
    <div class="chart-card">

        <h4>Statistik AMI Tahun {{ date('Y') }}</h4>

        <canvas id="amiChart"></canvas>

    </div>

    <!-- TABEL -->
    <div class="table-card">

        <div class="table-title">
            Periode AMI Berjalan
        </div>

        <table class="dashboard-table">

            <thead>

                <tr>

                    <th>No.</th>
                    <th>Tahun AMI</th>
                    <th>Standar Mutu</th>
                    <th>Unit Kerja</th>
                    <th>Tanggal Audit</th>

                </tr>

            </thead>

            <tbody>

                @forelse($periodeBerjalan as $item)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $item->tahun }}</td>

                    <td>
                        {{ $item->standarMutu->nama_standar_mutu ?? '-' }}
                    </td>

                    <td>
                        {{ $item->unitKerja->nama ?? '-' }}
                    </td>

                    <td>
                        {{ $item->tanggal_buka_ami }}
                        -
                        {{ $item->tanggal_tutup_ami }}
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" style="text-align:center;padding:25px;">

                        Belum ada Periode AMI yang sedang berjalan.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

const ctx = document.getElementById('amiChart');

new Chart(ctx,{

    type:'bar',

    data:{

        labels:[
            'Sesuai',
            'Observasi',
            'Tidak Sesuai'
        ],

        datasets:[{

            data:[
                {{ $grafik['sesuai'] }},
                {{ $grafik['observasi'] }},
                {{ $grafik['tidak_sesuai'] }}
            ],

            backgroundColor:[
                '#4A86E8',
                '#39FF14',
                '#E53935'
            ],

            borderRadius:6,
            barThickness:45

        }]

    },

    options:{

        responsive:true,

        plugins:{
            legend:{
                display:false
            }
        },

        scales:{

            y:{

                beginAtZero:true,

                ticks:{
                    stepSize:5
                }

            },

            x:{

                grid:{
                    display:false
                }

            }

        }

    }

});

</script>

@endsection