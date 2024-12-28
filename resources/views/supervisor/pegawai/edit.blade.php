@extends('layouts.authenticated')

@section('content')
    <div class="page-body">
        <div class="container-xl">

            <form action="{{ route('pegawai.update.fixed') }}" method="POST" enctype="multipart/form-data">
                <div class="row">
                    @csrf
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <label for="">ID Pegawai</label>
                                <input type="text" class="form-control" name="id_pegawai" value="{{ $employee->id }}" disabled>

                                <div class="mb-3">
                                    <label for="">Nama</label>
                                    <input type="text" class="form-control" name="nama" value="{{ $employee->nama }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="">Jenis Kelamin</label>
                                    <select required name="jenis_kelamin" class="form-control">
                                        <option selected disabled value="">--Pilih Jenis Kelamin--</option>
                                        <option value="Laki-laki"
                                            {{ $employee->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                            Laki-laki
                                        </option>
                                        <option value="Perempuan"
                                            {{ $employee->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                            Perempuan
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Alamat</label>
                                    <input type="text" class="form-control" name="alamat" value="{{ $employee->alamat }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="">No. Handphone</label>
                                    <input type="text" class="form-control" name="no_handphone" value="{{ $employee->no_handphone }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="">Jabatan</label>
                                    <select required name="jabatan" class="form-control">
                                        <option selected disabled value="">--Pilih Jabatan--</option>
                                        <option value="admin" {{ $employee->jabatan == 'admin' ? 'selected' : '' }}>Admin
                                        </option>
                                        <option value="supervisor"
                                            {{ $employee->jabatan == 'supervisor' ? 'selected' : '' }}>
                                            Supervisor</option>
                                        <option value="marketing" {{ $employee->jabatan == 'marketing' ? 'selected' : '' }}>
                                            Marketing</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Status</label>
                                    <select required name="status" class="form-control">
                                        <option selected disabled  value="">--Pilih Status--</option>
                                        <option value="Aktif" {{ $employee->status == 'Aktif' ? 'selected' : '' }}>
                                            Aktif</option>
                                        <option value="Tidak Aktif"
                                            {{ $employee->status == 'Tidak Aktif' ? 'selected' : '' }}>
                                            Tidak Aktif</option>
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
                                    <input type="text" class="form-control" name="username"
                                        value="{{ $employee->username }}" required>
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
                                    <select required name="role" class="form-control">
                                        <option selected disabled value="">--Pilih Role--</option>
                                        <option value="admin" {{ $employee->role == 'admin' ? 'selected' : '' }}>
                                            Admin</option>
                                        <option value="pegawai" {{ $employee->status == 'pegawai' ? 'selected' : '' }}>
                                            Pegawai</option>
                                        <option value="supervisor"
                                            {{ $employee->status == 'supervisor' ? 'selected' : '' }}>
                                            Supervisor</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Lokasi Presensi</label>
                                    <select required name="lokasi_presensi" class="form-control">
                                        <option selected disabled value="">--Pilih Lokasi Presensi--</option>
                                        @foreach ($lokasi as $i)
                                            <option value="{{ $i->nama_lokasi }}"
                                                {{ $employee->lokasi_presensi == $i->nama_lokasi ? 'selected' : '' }}>
                                                {{ $i->nama_lokasi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Foto</label>
                                    <input type="hidden" value="{{ $employee->foto }}" name="foto_lama">
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
