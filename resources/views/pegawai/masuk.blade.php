@extends('layouts.authenticated')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"
        integrity="sha512-dQIiHSl2hr3NWKKLycPndtpbh5iaHLo6MwrXm7F0FM5e+kL2U16oE9uIwPHUl6fQBeCthiEuV/rzP3MiAB8Vfw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!--- Include leaflet js: -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        #map {
            height: 300px;
        }
    </style>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card text-center">
                        <div class="card-body" style="margin: auto;">
                            <input type="hidden" id="id" value="{{ $authUserId }}">

                            <input type="hidden" id="tanggal_masuk" value="2024-12-07">
                            <input type="hidden" id="jam_masuk" value="09:00:00">
                            <div id="my_camera"></div>
                            <div id="my_result"></div>
                            {{-- <div id="display-datetime">07 December 2024 - 09:00:00</div> --}}
                            {{-- <div>07 December 2024 - 09:00:00</div> --}}
                            <div id="display-datetime">Loading...</div>

                            <button class="btn btn-primary mt-2" id="ambil-foto">Masuk</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        Webcam.set({
            width: 320,
            height: 240,
            dest_width: 320,
            dest_height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90,
            force_flash: false
        });


        Webcam.attach('#my_camera');

        function updateDateTime() {
            const today = new Date();

            // Format date as DD MMMM YYYY
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            const date = today.toLocaleDateString('id-ID', options);

            // Format time as HH:MM:SS
            const time = today.toTimeString().split(' ')[0];

            // Update the hidden inputs
            document.getElementById('tanggal_masuk').value = today.toISOString().split('T')[0];
            document.getElementById('jam_masuk').value = time;

            // Update the displayed date and time
            document.getElementById('display-datetime').textContent = `${date} - ${time}`;
        }

        // Update immediately and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);


        document.getElementById('ambil-foto').addEventListener('click', function() {

            let id = document.getElementById('id').value;
            let tanggal_masuk = document.getElementById('tanggal_masuk').value;
            let jam_masuk = document.getElementById('jam_masuk').value;

            console.log(id, tanggal_masuk, jam_masuk);

            Webcam.snap(function(data_uri) {
                let xhttp = new XMLHttpRequest();

                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        console.log(xhttp.responseText);
                        window.location.href = '/home-pegawai';
                    } else if (xhttp.readyState == 4) {
                        alert(`Terjadi kesalahan, silakan coba lagi kontol ${xhttp.status}, `);
                    }

                };

                xhttp.open("POST", "/presensi/masuk", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.setRequestHeader("X-CSRF-TOKEN", document.querySelector('meta[name="csrf-token"]')
                    .getAttribute('content'));

                // alert('&id=' + encodeURIComponent(id) +
                //     '&tanggal_masuk=' + encodeURIComponent(tanggal_masuk) +
                //     '&jam_masuk=' + encodeURIComponent(jam_masuk))

                xhttp.send(
                    'photo=' + encodeURIComponent(data_uri) +
                    '&id=' + encodeURIComponent(id) +
                    '&tanggal_masuk=' + encodeURIComponent(tanggal_masuk) +
                    '&jam_masuk=' + encodeURIComponent(jam_masuk)
                );
            });

        });

        // Leaflet map
        const urlParams = new URLSearchParams(window.location.search);

        // Get latitude and longitude from the query parameters
        let latitude_ktr = parseFloat(urlParams.get('latitude_kantor')) || -
            6.200000; // Default value if 'lat' is not provided
        let longitude_ktr = parseFloat(urlParams.get('longitude_kantor')) ||
            106.816666; // Default value if 'lng' is not provided // Hardcoded longitude for the office

        // alert(longitude_ktr)
        let latitude_peg = -6.175110; // Hardcoded latitude for the employee
        let longitude_peg = 106.865036; // Hardcoded longitude for the employee

        const options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0,
        };

        function success(pos) {
            const crd = pos.coords;

            console.log("Your current position is:");
            console.log(`Latitude : ${crd.latitude}`);
            //   console.log(typeof crd.latitude);
            //   console.log(typeof crd.longitude);
            console.log(`Longitude: ${crd.longitude}`);
            latitude_peg = crd.latitude;
            longitude_peg = crd.longitude;

            // let info = `${latitude_peg} - ${longitude_peg}`
            // alert(info)
            console.log(`More or less ${crd.accuracy} meters.`);
        }

        function error(err) {
            console.warn(`ERROR(${err.code}): ${err.message}`);
        }

        let map = L.map('map').setView([latitude_ktr, longitude_ktr], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker = L.marker([latitude_ktr, longitude_ktr]).addTo(map);

        var circle = L.circle([latitude_peg, longitude_peg], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 500
        }).addTo(map).bindPopup("Lokasi Anda saat ini").openPopup();
    </script>
@endsection
