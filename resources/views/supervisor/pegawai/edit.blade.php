@extends('layouts.authenticated')

@section('content')
<div class="page-body">
    <div class="container-xl">

    <form action="{{ route('pegawai.update.fixed')}}" method="POST" enctype="multipart/form-data">
        <div class="row">
            @csrf
        <div class="col-md-6">
        <div class="card">
            <div class="card-body">

                    <input type="text" class="form-control" name="id_pegawai" value="{{ $employee->id_pegawai }}">

                    <div class="mb-3">
                        <label for="">Nama</label>
                        <input type="text" class="form-control" name="nama" value="{{ $employee->nama}}">
                    </div>

                    <div class="mb-3">
                        <label for="">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="">--Pilih Jenis Kelamin--</option>
                            <option selected value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Alamat</label>
                        <input type="text" class="form-control" name="alamat" value="123 Main Street">
                    </div>

                    <div class="mb-3">
                        <label for="">No. Handphone</label>
                        <input type="text" class="form-control" name="no_handphone" value="08123456789">
                    </div>

                    <div class="mb-3">
                        <label for="">Jabatan</label>
                        <select name="jabatan" class="form-control">
                            <option value="">--Pilih Jabatan--</option>
                            <option selected value="Manager">Manager</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Status</label>
                        <select name="status" class="form-control">
                            <option value="">--Pilih Status--</option>
                            <option selected value="Aktif">Aktif</option>
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
                        <input type="text" class="form-control" name="username" value="johndoe">
                    </div>

                    <div class="mb-3">
                        <label for="">Password</label>
                        <input type="hidden" value="oldpassword123" name="password_lama">
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
                            <option selected value="pegawai">Pegawai</option>
                            <option value="supervisor">Supervisor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Lokasi Presensi</label>
                        <select name="lokasi_presensi" class="form-control">
                            <option value="">--Pilih Lokasi Presensi--</option>
                            <option selected value="Head Office">Head Office</option>
                            <option value="Branch Office">Branch Office</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="">Foto</label>
                        <input type="hidden" value="{{$employee->foto}}" name="foto_lama">
                        <input type="file" class="form-control" name="foto_baru">
                    </div>

                    <input type="hidden" value="1" name="id">

                    <button type="submit "class="btn btn-primary" name="edit">Update</button>

                </div>
            </div>
        </div>
        </form>
        </div>



        </div>

    </div>
</div>


@endsection
