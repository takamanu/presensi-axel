@extends('layouts.admin-header')

@section('content')
    <div class="page-body">
        <div class="container-xl">

            <a href="{{ route('admin.add-lokasi') }}" class="btn btn-primary"><span class="text"><i
                        class="fa-solid fa-circle-plus"></i> Tambah
                    Data</span></a>
            <div class="row row-deck row-cards mt-2">
                <table class="table-bordered mt -3 table">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Lokasi</th>
                            <th>Tipe Lokasi</th>
                            <th>Latitude/longitude</th>
                            <th>Radius</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    @if ($lokasi->isEmpty())
                        <tr>
                            <td colspan="3">Data masih kosong, silahkan tambahkan data baru</td>
                        </tr>
                    @else
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($lokasi as $i)
                                <tr class="text-center">
                                    <td><?= $no++ ?></td>
                                    <td>{{ $i->nama_lokasi }}</td>
                                    <td>{{ $i->tipe_lokasi }}</td>
                                    <td>{{ $i->latitude }} / {{ $i->longitude }}</td>
                                    <td>{{ $i->radius }}</td>
                                    <td>
                                        <a href="" class="badge bg-primary badge-pill">Detail</a>

                                        <a href="" class="badge bg-primary badge-pill">Edit</a>

                                        <a href="" class="badge badge-pill bg-danger tombol-hapus">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection