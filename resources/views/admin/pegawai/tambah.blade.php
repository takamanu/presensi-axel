@extends('layouts.admin-header')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="">Nama</label>
                                    <input type="text" class="form-control" name="nama" value="">
                                </div>

                                <div class="mb-3">
                                    <label for="">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value="">--Pilih Jenis Kelamin--</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Alamat</label>
                                    <input type="text" class="form-control" name="alamat" value="">
                                </div>

                                <div class="mb-3">
                                    <label for="">No. Handphone</label>
                                    <input type="text" class="form-control" name="no_handphone" value="">
                                </div>

                                <div class="mb-3">
                                    <label for="">Jabatan</label>
                                    <select name="jabatan" class="form-control">
                                        <option value="">--Pilih Jabatan--</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Supervisor">Supervisor</option>
                                        <option value="Pegawai">Pegawai</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">--Pilih Status--</option>
                                        <option value="Aktif">Aktif</option>
                                        <option value="Tidak Aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">

                                <div class="mb-3">
                                    <label for="">Username</label>
                                    <input type="text" class="form-control" name="username" value="">
                                </div>

                                <div class="mb-3">
                                    <label for="">Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>

                                <div class="mb-3">
                                    <label for="">Ulangi Password</label>
                                    <input type="password" class="form-control" name="ulangi_password">
                                </div>

                                <div class="mb-3">
                                    <label for="">Role</label>
                                    <select name="role" class="form-control">
                                        <option value="">--Pilih Role--</option>
                                        <option value="admin">Admin</option>
                                        <option value="pegawai">Pegawai</option>
                                        <option value="supervisor">Supervisor</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Lokasi Presensi</label>
                                    <select name="lokasi_presensi" class="form-control">
                                        <option value="">--Pilih Lokasi Presensi--</option>
                                        <option value="Kantor Pusat">Kantor Pusat</option>
                                        <option value="Cabang A">Cabang A</option>
                                        <option value="Cabang B">Cabang B</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Foto</label>
                                    <input type="file" class="form-control" name="foto">
                                </div>

                                <button type="submit" class="btn btn-primary" name="submit">Simpan</button>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
