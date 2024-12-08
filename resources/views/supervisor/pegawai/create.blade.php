@extends('layouts.authenticated')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <!-- Add Data Button -->
        <a href="#" class="btn btn-primary"><span class="text"><i class="fa-solid fa-circle-plus"></i> Tambah Data </span></a>

        <!-- Table displaying hardcoded employee data -->
        <table class="table table-bordered mt-3">
            <tr class="text-center">
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Jabatan</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>

            <!-- Hardcoded Data (for demonstration) -->
            <tr class="text-center">
                <td>1</td>
                <td>123456789</td>
                <td>John Doe</td>
                <td>johndoe123</td>
                <td>Manager</td>
                <td>Admin</td>
                <td>
                    <a href="#" class="badge bg-primary badge-pill">Detail</a>
                    <a href="#" class="badge bg-primary badge-pill">Edit</a>
                    <a href="#" class="badge badge-pill bg-danger">Hapus</a>
                </td>
            </tr>

            <tr class="text-center">
                <td>2</td>
                <td>987654321</td>
                <td>Jane Smith</td>
                <td>janesmith456</td>
                <td>Supervisor</td>
                <td>User</td>
                <td>
                    <a href="#" class="badge bg-primary badge-pill">Detail</a>
                    <a href="#" class="badge bg-primary badge-pill">Edit</a>
                    <a href="#" class="badge badge-pill bg-danger">Hapus</a>
                </td>
            </tr>

            <tr class="text-center">
                <td>3</td>
                <td>112233445</td>
                <td>Robert Brown</td>
                <td>robertbrown789</td>
                <td>Staff</td>
                <td>Employee</td>
                <td>
                    <a href="#" class="badge bg-primary badge-pill">Detail</a>
                    <a href="#" class="badge bg-primary badge-pill">Edit</a>
                    <a href="#" class="badge badge-pill bg-danger">Hapus</a>
                </td>
            </tr>

        </table>

    </div>
</div>


@endsection
