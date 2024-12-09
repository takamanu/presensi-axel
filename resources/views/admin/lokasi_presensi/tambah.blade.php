@extends('layouts.admin-header')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="card col-md-6">
                    <div class="card-body">
                        <form action="{{ route('admin.store-lokasi') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="">Nama Lokasi</label>
                                <input type="text" class="form-control" name="nama_lokasi" value="">
                            </div>

                            <div class="mb-3">
                                <label for="">Alamat Lokasi</label>
                                <input type="text" class="form-control" name="alamat_lokasi" value="">
                            </div>

                            <div class="mb-3">
                                <label for="">Tipe Lokasi</label>
                                <select name="tipe_lokasi" class="form-control">
                                    <option selected disabled value="">--Pilih Tipe Lokasi--</option>
                                    <option value="Pusat">Pusat</option>
                                    <option value="Cabang">Cabang</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude" value="">
                            </div>

                            <div class="mb-3">
                                <label for="">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude" value="">
                            </div>

                            <div class="mb-3">
                                <label for="">Radius</label>
                                <input value="500" type="number" class="form-control" id="radius" name="radius">
                            </div>

                            <div class="mb-3">
                                <label for="">Zona Waktu</label>
                                <select name="zona_waktu" class="form-control">
                                    <option selected disabled value="">--Pilih Zona Waktu--</option>
                                    <option value="WIB">WIB</option>

                                    <option value="WITA">WITA</option>

                                    <option value="WIT">WIT</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Jam Masuk</label>
                                <input type="time" class="form-control" name="jam_masuk" value="">
                            </div>

                            <div class="mb-3">
                                <label for="">Jam Pulang</label>
                                <input type="time" class="form-control" name="jam_pulang" value="">
                            </div>

                            <button type="submit "class="btn btn-primary" name="submit">Simpan</button>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div id="map" style="width: 100%; height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const map = L.map('map').setView([-6.200000, 106.816666], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        // Add draggable marker
        const marker = L.marker([-6.200000, 106.816666], {
            draggable: true
        }).addTo(map);

        // Add circle with default radius
        let circle = L.circle([-6.200000, 106.816666], {
            color: 'blue',
            fillColor: '#add8e6',
            fillOpacity: 0.5,
            radius: 500 // Default radius in meters
        }).addTo(map);

        // Update marker and circle when marker is dragged
        marker.on('dragend', function() {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;

            // Update circle position
            circle.setLatLng(position);
        });

        // Update circle radius when user changes the input value
        document.getElementById('radius').addEventListener('input', function() {
            const newRadius = parseFloat(this.value);
            circle.setRadius(newRadius);
        });

        // Set initial values
        document.getElementById('latitude').value = -6.200000;
        document.getElementById('longitude').value = 106.816666;
    </script>
@endsection
