@extends('layouts.authenticated')

@section('content')

    <div class="page-body">
        <div class="container-xl">

            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('home-pegawai.create-ketidakhadiran') }}" class="btn btn-primary">
                    Tambah Cuti
                </a>
            </div>

            <table class="table-bordered mt-3 table">
                <thead>
                    <tr class="text-center">
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Deskripsi</th>
                        <th>File</th>
                        <th>Status Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($ketidakhadiran->isEmpty())
                        <tr>
                            <td colspan="7">Data Cuti masih kosong</td>
                        </tr>
                    @else
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($ketidakhadiran as $i)
                            <tr class="text-center">
                                <td>{{ $no++ }}</td>
                                <td>{{ date('d F Y', strtotime($i->tanggal)) }}</td>
                                <td>{{ $i->keterangan }}</td>
                                <td>{{ $i->deskripsi }}</td>
                                <td class="text-center">
                                    {{-- {{ $i->file }} --}}
                                    <a target="_blank" href="{{ route('pegawai.download-ketidakhadiran', $i->id) }}"
                                        class="badge badge-pill bg-primary">Download</a>
                                </td>
                                <td class="text-center">
                                    @if ($i->status_pengajuan == 'PENDING')
                                        <a class="badge badge-pill bg-warning"
                                            href="{{ route('pegawai.detail-ketidakhadiran', $i->id) }}">PENDING</a>
                                    @elseif($i->status_pengajuan == 'REJECTED')
                                        <a class="badge badge-pill bg-danger"
                                            href="{{ route('pegawai.detail-ketidakhadiran', $i->id) }}">REJECTED</a>
                                    @else
                                        <a class="badge badge-pill bg-success"
                                            href="{{ route('pegawai.detail-ketidakhadiran', $i->id) }}">APPROVED</a>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Update Button -->
                                    {{-- <form action="{{ route('pegawai.update-ketidakhadiran', $i->id) }}" method="GET" style="display: inline;">
                                        <button type="submit" class="btn btn-warning btn-sm">Update</button>
                                    </form> --}}

                                    <!-- Hapus (Delete) Button -->
                                    <form action="{{ route('pegawai.delete-ketidakhadiran', $i->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah kamu ingin hapus ketidak hadiran kamu?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
