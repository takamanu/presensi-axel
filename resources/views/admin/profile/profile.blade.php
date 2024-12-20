@extends('layouts.admin-header')

@section('content')
    <div class="page-body">
        <div class="container-xl">

            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <center>
                                <!-- Hardcoded employee photo -->
                                <img style="border-radius: 100%; width: 50%"
                                    src="{{ asset('storage/' . auth()->user()->pegawai->foto) }}" alt="Foto Profil">
                            </center>

                            <table class="mt-4 table">
                                <tr>
                                    <td>Nama</td>
                                    <td>: {{ $user->pegawai->nama }}</td>
                                </tr>

                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>: {{ $user->pegawai->jenis_kelamin }}</td>
                                </tr>

                                <tr>
                                    <td>Alamat</td>
                                    <td>: {{ $user->pegawai->alamat }}</td>
                                </tr>

                                <tr>
                                    <td>No. Handphone</td>
                                    <td>: {{ $user->pegawai->no_handphone }}</td>
                                </tr>

                                <tr>
                                    <td>Jabatan</td>
                                    <td>: {{ $user->pegawai->jabatan }}</td>
                                </tr>

                                <tr>
                                    <td>Username</td>
                                    <td>: {{ $user->username }}</td>
                                </tr>

                                <tr>
                                    <td>Role</td>
                                    <td>: {{ $user->role }}</td>
                                </tr>

                                <tr>
                                    <td>Lokasi Presensi</td>
                                    <td>: {{ $user->pegawai->lokasi_presensi }}</td>
                                </tr>

                                <tr>
                                    <td>Status</td>
                                    <td>: {{ $user->status }}</td>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>

                <div class="col-md-4"></div>
            </div>

        </div>
    </div>
@endsection
