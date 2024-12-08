@extends('layouts.authenticated')

@section('content')
<div class="page-body">
    <div class="container-xl">

    <form action="" method="POST">

    <div class="card col-md-6">
        <div class="card-body">
        <div class="mb-3">
            <label for="">Password Baru</label>
            <input type="password" name="password_baru" class="form-control">
        </div>

        <div class="mb-3">
            <label for="">Ulangi Password Baru</label>
            <input type="password" name="ulangi_password_baru" class="form-control">
        </div>

        <input type="hidden" name="id" value="">

        <button type="submit" class="btn btn-primary" name="update">Update</button>
        </div>
    </div>

    </form>

    </div>
</div>
@endsection
