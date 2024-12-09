@extends('layouts.admin-header')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="card col-md-6">
                    <div class="card-body">
                        <form action="{{ route('admin.update-lokasi', $lokasi->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="">Nama Lokasi</label>
                                <input type="text" class="form-control" name="nama_lokasi"
                                    value="{{ $lokasi->nama_lokasi }}">
                            </div>

                            <div class="mb-3">
                                <label for="">Alamat Lokasi</label>
                                <input type="text" class="form-control" name="alamat_lokasi"
                                    value="{{ $lokasi->alamat_lokasi }}">
                            </div>

                            <div class="mb-3">
                                <label for="">Tipe Lokasi</label>
                                <select name="tipe_lokasi" class="form-control">
                                    <option selected disabled value="">--Pilih Tipe Lokasi--</option>
                                    <option value="Pusat" {{ $lokasi->tipe_lokasi == 'Pusat' ? 'selected' : '' }}>Pusat
                                    </option>
                                    <option value="Cabang" {{ $lokasi->tipeLokasi == 'Cabang' ? 'selected' : '' }}>Cabang
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Latitude</label>
                                <input type="text" class="form-control" id="latitude" name="latitude"
                                    value="{{ $lokasi->latitude }}">
                            </div>

                            <div class="mb-3">
                                <label for="">Longitude</label>
                                <input type="text" class="form-control" id="longitude" name="longitude"
                                    value="{{ $lokasi->longitude }}">
                            </div>

                            <div class="mb-3">
                                <label for="">Radius</label>
                                <input value="{{ $lokasi->radius }}" type="number" class="form-control" id="radius"
                                    name="radius">
                            </div>

                            <div class="mb-3">
                                <label for="">Zona Waktu</label>
                                <select name="zona_waktu" class="form-control">
                                    <option selected disabled value="">--Pilih Zona Waktu--</option>
                                    <option value="WIB" {{ $lokasi->zona_waktu == 'WIB' ? 'selected' : '' }}>WIB
                                    </option>

                                    <option value="WITA" {{ $lokasi->zona_waktu == 'WITA' ? 'selected' : '' }}>WITA
                                    </option>

                                    <option value="WIT" {{ $lokasi->zona_waktu == 'WIT' ? 'selected' : '' }}>WIT
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">Jam Masuk</label>
                                <input type="time" class="form-control" name="jam_masuk"
                                    value="{{ $lokasi->jam_masuk }}">
                            </div>

                            <div class="mb-3">
                                <label for="">Jam Pulang</label>
                                <input type="time" class="form-control" name="jam_pulang"
                                    value="{{ $lokasi->jam_pulang }}">
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
        const map = L.map('map').setView([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        const marker = L.marker([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], {
            draggable: true
        }).addTo(map);

        let circle = L.circle([{{ $lokasi->latitude }}, {{ $lokasi->longitude }}], {
            color: 'blue',
            fillColor: '#add8e6',
            fillOpacity: 0.5,
            radius: {{ $lokasi->radius }}
        }).addTo(map);

        marker.on('dragend', function() {
            const position = marker.getLatLng();
            document.getElementById('latitude').value = position.lat;
            document.getElementById('longitude').value = position.lng;

            circle.setLatLng(position);
        });

        document.getElementById('radius').addEventListener('input', function() {
            const newRadius = parseFloat(this.value);
            circle.setRadius(newRadius);
        });

        document.getElementById('latitude').value = {{ $lokasi->latitude }};
        document.getElementById('longitude').value = {{ $lokasi->longitude }};
    </script>
@endsection
