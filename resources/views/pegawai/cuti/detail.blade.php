@extends('layouts.authenticated')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card col-md-6">
                <div class="card-body">
                    {{-- <form action="{{ route('admin.update-ketidakhadiran', $ketidakhadiran->id) }}" method="POST"> --}}
                        {{-- @csrf --}}
                        <div class="mb-3">
                            <label for="">Tanggal</label>
                            <input disabled type="date" class="form-control" name="tanggal" value="{{ $ketidakhadiran->tanggal }}"
                                >
                        </div>

                        <div class="mb-3">
                            <label for="">keterangan</label>
                            <input disabled type="text" class="form-control" name="tanggal"
                                value="{{ $ketidakhadiran->keterangan }}" >
                        </div>

                        <div class="mb-3">
                            <label for="">Status Pengajuan</label>
                            <select disabled name="status_pengajuan" class="form-control">
                                <option selected disabled value="">--Pilih Status--</option>
                                <option value="PENDING"
                                    {{ $ketidakhadiran->status_pengajuan == 'PENDING' ? 'selected' : '' }}>
                                    PENDING</option>

                                <option value="REJECTED"
                                    {{ $ketidakhadiran->status_pengajuan == 'REJECTED' ? 'selected' : '' }}>REJECTED
                                </option>

                                <option value="APPROVED"
                                    {{ $ketidakhadiran->status_pengajuan == 'APPROVED' ? 'selected' : '' }}>APPROVED
                                </option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-primary" name="update" onclick="window.location.href='{{ route('home-pegawai.ketidakhadiran') }}'">Back</button>
                    {{-- </form> --}}
                </div>
            </div>

        </div>
    </div>
@endsection
