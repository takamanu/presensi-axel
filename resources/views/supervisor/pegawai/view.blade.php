@extends('layouts.authenticated')

@section('content')
    <div class="page-body">
        <div class="container-xl">

            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">

                            <!-- Hardcoded Employee Data -->
                            <table class="table">
                                <tr>
                                    <td>Nama</td>
                                    <td>: {{ $pegawai->nama }}</td>
                                </tr>

                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>: {{ $pegawai->jenis_kelamin }}</td>
                                </tr>

                                <tr>
                                    <td>Alamat</td>
                                    <td>: {{ $pegawai->alamat }}</td>
                                </tr>

                                <tr>
                                    <td>No. Handphone</td>
                                    <td>: {{ $pegawai->no_handphone }}</td>
                                </tr>

                                <tr>
                                    <td>Jabatan</td>
                                    <td>: {{ $pegawai->jabatan }}</td>
                                </tr>

                                <tr>
                                    <td>Username</td>
                                    <td>: {{ $pegawai->username }}</td>
                                </tr>

                                <tr>
                                    <td>Role</td>
                                    <td>: {{ $pegawai->role }}</td>
                                </tr>

                                <tr>
                                    <td>Lokasi Presensi</td>
                                    <td>: {{ $pegawai->lokasi_presensi }}</td>
                                </tr>

                                <tr>
                                    <td>Status</td>
                                    <td>: {{ $pegawai->status }}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Hardcoded Image -->
                    <img style="width: 350px; border-radius: 10px" src="{{ asset('storage/' . $pegawai->foto) }}"
                        alt="Foto">
                </div>
            </div>

        </div>
    </div>
@endsection
