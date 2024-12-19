@extends('layouts.authenticated')

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
                                    <td>: John Doe</td>
                                </tr>

                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>: Laki-laki</td>
                                </tr>

                                <tr>
                                    <td>Alamat</td>
                                    <td>: Jl. Contoh No. 123, Jakarta</td>
                                </tr>

                                <tr>
                                    <td>No. Handphone</td>
                                    <td>: 081234567890</td>
                                </tr>

                                <tr>
                                    <td>Jabatan</td>
                                    <td>: Manager</td>
                                </tr>

                                <tr>
                                    <td>Username</td>
                                    <td>: johndoe123</td>
                                </tr>

                                <tr>
                                    <td>Role</td>
                                    <td>: Admin</td>
                                </tr>

                                <tr>
                                    <td>Lokasi Presensi</td>
                                    <td>: Jakarta Office</td>
                                </tr>

                                <tr>
                                    <td>Status</td>
                                    <td>: Aktif</td>
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
