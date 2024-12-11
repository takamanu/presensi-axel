@extends('layouts.admin-header')

@section('content')
    <div class="page-body">
        <div class="container-xl">

            @if (Session::has('berhasil'))
                <div class="alert alert-success">
                    {{ Session::get('berhasil') }}
                </div>
            @endif

            @if (Session::has('validasi'))
                <div class="alert alert-danger">
                    {{ Session::get('validasi') }}
                </div>
            @endif

            <form action="{{ route('admin.update-password') }}" method="POST">
                @csrf
                <div class="card col-md-6">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="">Password Baru</label>
                            <input type="password" name="password_baru"
                                class="form-control @error('password_baru') is-invalid @enderror">
                            @error('password_baru')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="">Ulangi Password Baru</label>
                            <input type="password" name="ulangi_password_baru"
                                class="form-control @error('ulangi_password_baru') is-invalid @enderror">
                            @error('ulangi_password_baru')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <input type="hidden" name="id" value="">

                        <button type="submit" class="btn btn-primary" name="update">Update</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection
