@extends('layouts.admin-header')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <td>Nama Lokasi</td>
                                    <td>: {{ $lokasi->nama_lokasi }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat Lokasi</td>
                                    <td>: {{ $lokasi->alamat_lokasi }} </td>
                                </tr>
                                <tr>
                                    <td>Tipe Lokasi</td>
                                    <td>: {{ $lokasi->tipe_lokasi }} </td>
                                </tr>
                                <tr>
                                    <td>Latitude</td>
                                    <td>: {{ $lokasi->latitude }} </td>
                                </tr>
                                <tr>
                                    <td>Longitude</td>
                                    <td>: {{ $lokasi->longitude }} </td>
                                </tr>
                                <tr>
                                    <td>Radius</td>
                                    <td>: {{ $lokasi->radius }} </td>
                                </tr>
                                <tr>
                                    <td>Zona Waktu</td>
                                    <td>: {{ $lokasi->zona_waktu }}</td>
                                </tr>
                                <tr>
                                    <td>Jam Masuk</td>
                                    <td>: {{ $lokasi->jam_masuk }}</td>
                                </tr>
                                <tr>
                                    <td>Jam Pulang</td>
                                    <td>: {{ $lokasi->jam_pulang }}</td>
                                </tr>

                            </table>
                            <a href="{{ route('admin.lokasi-presensi') }}" class="btn btn-danger">Back</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="map" style="width: 100%; height: 450px; border: 0px;" loading="lazy"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        const marker = L.marker([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], {
            draggable: false
        }).addTo(map);

        let circle = L.circle([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], {
            color: 'blue',
            fillColor: '#add8e6',
            fillOpacity: 0.5,
            radius: {{ $lokasi->radius }}
        }).addTo(map);
    </script>
@endsection
