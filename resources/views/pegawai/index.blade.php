@extends('layouts.authenticated')

@section('content')
<style>
  .parent_date {
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 20px;
    text-align: center;
    justify-content: center;
  }

  .parent_clock {
    display: grid;
    grid-template-columns: auto auto auto auto auto;
    font-size: 30px;
    text-align: center;
    font-weight: bold;
    justify-content: center;
  }
</style>

<div class="page-body">
    <div class="container-xl">

        <!-- Add Data Button -->
        <a href="#" class="btn btn-primary"><span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data </span></a>

        <!-- Table displaying hardcoded employee data -->
        <table class="table table-bordered mt-3">
            <tr class="text-center">
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Jabatan</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>

            <!-- Hardcoded Data (for demonstration) -->
            <tr class="text-center">
                <td>1</td>
                <td>123456789</td>
                <td>John Doe</td>
                <td>johndoe123</td>
                <td>Manager</td>
                <td>Admin</td>
                <td>
                    <a href="#" class="badge bg-primary badge-pill">Detail</a>
                    <a href="#" class="badge bg-primary badge-pill">Edit</a>
                    <a href="#" class="badge badge-pill bg-danger">Hapus</a>
                </td>
            </tr>

            <tr class="text-center">
                <td>2</td>
                <td>987654321</td>
                <td>Jane Smith</td>
                <td>janesmith456</td>
                <td>Supervisor</td>
                <td>User</td>
                <td>
                    <a href="#" class="badge bg-primary badge-pill">Detail</a>
                    <a href="#" class="badge bg-primary badge-pill">Edit</a>
                    <a href="#" class="badge badge-pill bg-danger">Hapus</a>
                </td>
            </tr>

            <tr class="text-center">
                <td>3</td>
                <td>112233445</td>
                <td>Robert Brown</td>
                <td>robertbrown789</td>
                <td>Staff</td>
                <td>Employee</td>
                <td>
                    <a href="#" class="badge bg-primary badge-pill">Detail</a>
                    <a href="#" class="badge bg-primary badge-pill">Edit</a>
                    <a href="#" class="badge badge-pill bg-danger">Hapus</a>
                </td>
            </tr>

        </table>

    </div>
</div>


<script>
    // Set waktu di card presensi masuk
    window.setTimeout("waktuMasuk()", 1000);
    const namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    function waktuMasuk() {
      const waktu = new Date();
      setTimeout("waktuMasuk()", 1000);
      document.getElementById("tanggal_masuk").innerHTML = waktu.getDate().toString().padStart(2, '0');
      document.getElementById("bulan_masuk").innerHTML = namaBulan[waktu.getMonth()];
      document.getElementById("tahun_masuk").innerHTML = waktu.getFullYear();
      document.getElementById("jam_masuk").innerHTML = waktu.getHours().toString().padStart(2, '0');
      document.getElementById("menit_masuk").innerHTML = waktu.getMinutes().toString().padStart(2, '0');
      document.getElementById("detik_masuk").innerHTML = waktu.getSeconds().toString().padStart(2, '0');
    }

    // Set waktu di card presensi pulang
    window.setTimeout("waktuKeluar()", 1000);

    function waktuKeluar() {
      const waktu = new Date();
      setTimeout("waktuKeluar()", 1000);
      document.getElementById("tanggal_keluar").innerHTML = waktu.getDate().toString().padStart(2, '0');
      document.getElementById("bulan_keluar").innerHTML = namaBulan[waktu.getMonth()];
      document.getElementById("tahun_keluar").innerHTML = waktu.getFullYear();
      document.getElementById("jam_keluar").innerHTML = waktu.getHours().toString().padStart(2, '0');
      document.getElementById("menit_keluar").innerHTML = waktu.getMinutes().toString().padStart(2, '0');
      document.getElementById("detik_keluar").innerHTML = waktu.getSeconds().toString().padStart(2, '0');
    }

    // Geolocation for checking employee position
    getLocation();

    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
      } else {
        alert("Browser Anda tidak mendukung");
      }
    }

    function showPosition(position) {
      document.getElementById('latitude_pegawai').value = position.coords.latitude;
      document.getElementById('longitude_pegawai').value = position.coords.longitude;
    }
  </script>

@endsection
