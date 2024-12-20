@extends('layouts.admin-header')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            @if (session('pesan'))
                <div class="alert alert-success">
                    {{ session('pesan') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Add Data Button -->
            <a href="{{ route('admin.add-pegawai') }}" class="btn btn-primary"><span class="text"><i
                        class="fa-solid fa-circle-plus"></i> Tambah Data
                </span></a>

            <!-- Table displaying hardcoded employee data -->
            <table class="table-bordered mt-3 table">
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
                                <td>{{ $item->pegawai->nip }}</td>
                                <td>{{ $item->pegawai->nama }}</td>
                                <td>{{ $item->username }}</td>
                                <td>{{ $item->pegawai->jabatan }}</td>
                                <td>{{ $item->role }}</td>
                                <td>
                                    <div style="display: flex; justify-content: center; gap: 5px;">
                                        <a href="{{ route('admin.detail-pegawai', $item->id) }}"
                                            class="badge bg-primary badge-pill">Detail</a>
                                        <a href="{{ route('admin.edit-pegawai', $item->id_pegawai) }}"
                                            class="badge bg-primary badge-pill">Edit</a>
                                        <form action="{{ route('admin.destroy-pegawai', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="color: aliceblue"
                                                class="badge badge-pill bg-danger tombol-hapus">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>

            </table>

        </div>
    </div>


@endsection
