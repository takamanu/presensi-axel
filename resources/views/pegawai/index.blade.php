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
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-header">Presensi Masuk</div>
                        <div class="card-body">
                            @php
                                $presensiMasuk = false; // Set true or false to simulate data
                            @endphp

                            @if (!$presensiMasuk)
                                <div class="parent_date">
                                    <div id="tanggal_masuk"></div>
                                    <div class="ms-2"></div>
                                    <div id="bulan_masuk"></div>
                                    <div class="ms-2"></div>
                                    <div id="tahun_masuk"></div>
                                </div>

                                <div class="parent_clock">
                                    <div id="jam_masuk"></div>
                                    <div>:</div>
                                    <div id="menit_masuk"></div>
                                    <div>:</div>
                                    <div id="detik_masuk"></div>
                                </div>

                                <form method="GET" action="/home-pegawai/masuk">
                                    @csrf
                                    <input type="hidden" name="latitude_pegawai" id="latitude_pegawai">
                                    <input type="hidden" name="longitude_pegawai" id="longitude_pegawai">
                                    <input type="hidden" value="-6.200000" name="latitude_kantor">
                                    <input type="hidden" value="106.816666" name="longitude_kantor">
                                    <input type="hidden" value="100" name="radius">
                                    <input type="hidden" value="WIB" name="zona_waktu">
                                    <input type="hidden" value="{{ now()->format('Y-m-d') }}" name="tanggal_masuk">
                                    <input type="hidden" value="{{ now()->format('H:i:s') }}" name="jam_masuk">

                                    <button type="submit" name="tombol_masuk" class="btn btn-primary mt-3">Masuk</button>
                                </form>
                            @else
                                <i class="fa-regular fa-circle-check fa-4x text-success"></i>
                                <h4 class="my-3">Anda telah melakukan <br> presensi masuk</h4>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-header">Presensi Pulang</div>
                        <div class="card-body">
                            @php
                                $jamPulang = '17:00:00';
                                $waktuSekarang = now()->format('H:i:s');
                                $presensiPulang = false; // Simulate presensi pulang
                            @endphp

                            @if ($waktuSekarang <= $jamPulang)
                                <i class="fa-regular fa-circle-xmark fa-4x text-danger"></i>
                                <h4 class="my-3">Belum waktunya pulang</h4>
                            @elseif($presensiMasuk && !$presensiPulang)
                                <div class="parent_date">
                                    <div id="tanggal_keluar"></div>
                                    <div class="ms-2"></div>
                                    <div id="bulan_keluar"></div>
                                    <div class="ms-2"></div>
                                    <div id="tahun_keluar"></div>
                                </div>

                                <div class="parent_clock">
                                    <div id="jam_keluar"></div>
                                    <div>:</div>
                                    <div id="menit_keluar"></div>
                                    <div>:</div>
                                    <div id="detik_keluar"></div>
                                </div>

                                <form method="POST" action="/masuk">
                                    {{-- href="{{ route('login') }}" --}}
                                    @csrf
                                    <input type="hidden" name="id" value="1"> <!-- Hardcoded example ID -->
                                    <input type="hidden" name="latitude_pegawai" id="latitude_pegawai">
                                    <input type="hidden" name="longitude_pegawai" id="longitude_pegawai">
                                    <input type="hidden" value="-6.200000" name="latitude_kantor">
                                    <input type="hidden" value="106.816666" name="longitude_kantor">
                                    <input type="hidden" value="100" name="radius">
                                    <input type="hidden" value="WIB" name="zona_waktu">
                                    <input type="hidden" value="{{ now()->format('Y-m-d') }}" name="tanggal_keluar">
                                    <input type="hidden" value="{{ now()->format('H:i:s') }}" name="jam_keluar">

                                    <button type="submit" name="tombol-keluar" class="btn btn-danger mt-3">Pulang</button>
                                </form>
                            @else
                                <i class="fa-regular fa-circle-check fa-4x text-success"></i>
                                <h4 class="my-3">Anda telah melakukan <br> presensi keluar</h4>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>

    <script>
        // Set waktu di card presensi masuk
        window.setTimeout("waktuMasuk()", 1000);
        const namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
            "Oktober", "November", "Desember"
        ];

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
