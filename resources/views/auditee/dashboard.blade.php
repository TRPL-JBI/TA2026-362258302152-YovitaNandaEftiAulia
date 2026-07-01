@extends('layouts.auditee')

@section('content')

<h3 class="breadcrumb">
    Dashboard Auditee
</h3>

<div class="welcome-card">

    <h4>
        Selamat datang di Sistem Informasi Penjaminan Mutu Internal Poliwangi.
    </h4>

    <p>
    Halo,
    {{ session('user')['nama'] ?? '-' }}
    </p>

    <p>
        Anda berhasil masuk sebagai Auditee
    </p>

</div>


<div class="dashboard-stats">

    <div class="stat-card blue">

        <h5>Total Standar</h5>

        <span>
            {{ $totalStandar }}
        </span>

    </div>

    <div class="stat-card green">

        <h5>Periode AMI Aktif</h5>

        <span>
            {{ $periodeAktif }}
        </span>

    </div>

    <div class="stat-card red">

        <h5>Jumlah Temuan</h5>

        <span>
            {{ $jumlahTemuan }}
        </span>

    </div>

</div>


<div class="dashboard-row">

    <div class="chart-card">

        <h4>
            Statistik AMI Tahun {{ date('Y') }}
        </h4>

        <canvas id="amiChart"></canvas>

    </div>

</div>


<div class="table-card">

    <h4>
        Periode AMI Berjalan
    </h4>

    <table class="custom-table">

        <thead>

        <tr>

            <th>No.</th>
            <th>Tahun AMI</th>
            <th>Standar Mutu</th>
            <th>Unit</th>
            <th>Tanggal</th>

        </tr>

        </thead>

        <tbody>

        @forelse($periodeBerjalan as $item)

        <tr>

            <td>
                {{ $loop->iteration }}
            </td>

            <td>
                {{ $item->tahun }}
            </td>

            <td>
                {{ $item->standarMutu->nama_standar_mutu }}
            </td>

            <td>
                {{ $item->unitKerja->nama }}
            </td>

            <td>
                {{ $item->tanggal_buka_ami }}
                -
                {{ $item->tanggal_tutup_ami }}
            </td>

        </tr>

        @empty

        <tr>

            <td colspan="5">
                Tidak ada periode berjalan
            </td>

        </tr>

        @endforelse

        </tbody>

        <script>

document.querySelectorAll('.dropdown-btn')
.forEach(function(btn){

    btn.addEventListener('click', function(e){

        e.preventDefault();

        let submenu =
            this.nextElementSibling;

        if(submenu.style.display === 'block'){

            submenu.style.display = 'none';

        }else{

            submenu.style.display = 'block';

        }

    });

});

</script>

    </table>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

new Chart(
    document.getElementById('amiChart'),
    {
        type: 'bar',

        data: {

            labels: [
                'Sesuai',
                'Observasi',
                'Tidak Sesuai'
            ],

            datasets: [{

                data: [18,13,8]

            }]
        }
    }
);

</script>

@endsection