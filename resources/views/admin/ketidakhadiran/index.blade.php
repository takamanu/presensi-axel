@extends('layouts.admin-header')

@section('content')
    <div class="page-body">
        <div class="container-xl">

            <table class="table-bordered mt-3 table">
                <thead>
                    <tr class="text-center">
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Deskripsi</th>
                        <th>File</th>
                        <th>Status Pengajuan</th>
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
                                    <a target="_blank" href="{{ route('admin.download-ketidakhadiran', $i->id) }}"
                                        class="badge badge-pill bg-primary">Download</a>
                                </td>
                                <td class="text-center">
                                    @if ($i->status_pengajuan == 'PENDING')
                                        <a class="badge badge-pill bg-warning"
                                            href="{{ route('admin.detail-ketidakhadiran', $i->id) }}">PENDING</a>
                                    @elseif($i->status_pengajuan == 'REJECTED')
                                        <a class="badge badge-pill bg-danger"
                                            href="{{ route('admin.detail-ketidakhadiran', $i->id) }}">REJECTED</a>
                                    @else
                                        <a class="badge badge-pill bg-success"
                                            href="{{ route('admin.detail-ketidakhadiran', $i->id) }}">APPROVED</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection
