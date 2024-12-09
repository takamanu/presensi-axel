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

            <tbody>
                @if ($pegawai->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">Data kosong, silahkan tambahkan data baru</td>
                    </tr>
                @else
                    @foreach ($pegawai as $index => $item)
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->username }}</td>
                            <td>{{ $item->jabatan }}</td>
                            <td>{{ $item->role }}</td>
                            <td>
                                <a href="{{ route('supervisor.data_pegawai.show', $item->id) }}" class="badge bg-primary badge-pill">Detail</a>
                                <a href="" class="badge bg-primary badge-pill">Edit</a>
                                <a href="{{ route('supervisor.data_pegawai.destroy', $item->id) }}" class="badge badge-pill bg-danger tombol-hapus">Hapus</a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>

        </table>

    </div>
</div>


@endsection
