@extends('layouts.admin-header')

@section('content')
    <div class="page-body">
        <div class="container-xl">

            <div class="card col-md-6">
                <div class="card-body">

                    <form action="{{ route('admin.update-jabatan', $jabatans->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="">Nama Jabatan</label>
                            <input type="text" value="{{ $jabatans->jabatan }}" class="form-control" name="jabatan">
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
