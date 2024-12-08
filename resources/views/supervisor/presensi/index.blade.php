@extends('layouts.authenticated')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <div class="row">
            <div class="col-md-2">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Export Excel
                </button>
            </div>

            <div class="col-md-10">
                <form method="GET">
                    <div class="input-group">
                        <input type="date" class="form-control" name="tanggal_dari">
                        <input type="date" class="form-control" name="tanggal_sampai">
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>


        <table class="table table-bordered">
            <tr class="text-center">
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Jam</th>
                <th>Total Terlambat</th>
            </tr>

                <tr>
                    <td colspan="6">Data rekap presensi masih kosong.</td>
                </tr>

                    <tr>
                        <td></td>
                        <td></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">
                                <span>0 Jam 0 Menit</span>
                        </td>
                        <td class="text-center">
                                <span class="badge bg-success">On Time</span>
                        </td>
                    </tr>

        </table>
    </div>
</div>

<div class="modal" id="exampleModal" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Excel Rekap Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="">Tanggal Awal</label>
                        <input type="date" class="form-control" name="tanggal_dari">
                    </div>

                    <div class="mb-3">
                        <label for="">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_sampai">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
