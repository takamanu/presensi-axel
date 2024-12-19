
@extends('layouts.authenticated')

@section('content')
<style>
    .lateness-badge {
    color: white !important; /* Ensures text is white */
    width: 200px; /* Fixed width for badges */
    text-align: center; /* Center-align the text */
    display: inline-block; /* Ensures proper alignment */
    overflow: hidden; /* Prevents overflow text */
    text-overflow: ellipsis; /* Truncates overflow text if needed */
    white-space: nowrap; /* Keeps text on a single line */
}
</style>
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
                        <!-- Date Picker for Tanggal Dari -->
                        <input type="date" class="form-control" name="tanggal_dari" value="{{ $tanggal_dari }}">

                        <!-- Date Picker for Tanggal Sampai -->
                        <input type="date" class="form-control" name="tanggal_sampai" value="{{ $tanggal_sampai }}">

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <p>
                Rekap Presensi Tanggal: {{ $formatted_date_range }}
            </p>
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

            @forelse ($presensi as $key => $data)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal_masuk)->translatedFormat('d F Y') }}</td>
                    <td class="text-center">{{ $data->jam_masuk }}</td>
                    <td class="text-center">{{ $data->jam_keluar }}</td>
                    <td class="text-center">
                        @if ($data->jam_keluar)
                        {{  \App\Helpers\TimeHelper::calculateTotalHours($data->jam_masuk, $data->jam_keluar) }}
                    @else
                        <span>0 Jam 0 Menit</span>
                    @endif
                </td>
                <td class="text-center">
                    @php
                        $lateMinutes = \App\Helpers\TimeHelper::calculateLateMinutes($data->jam_masuk, '08:00:00');
                        $lateHours = floor($lateMinutes / 60);
                        $remainingMinutes = $lateMinutes % 60;
                    @endphp

                    @if($lateMinutes > 0)
                        <span class="badge bg-danger lateness-badge">
                            Terlambat ({{ $lateHours }} Jam {{ $remainingMinutes }} Menit)
                        </span>
                    @else
                        <span class="badge bg-success lateness-badge">
                            On Time
                        </span>
                    @endif
                </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Data rekap presensi masih kosong.</td>
                </tr>
            @endforelse
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
            <form method="POST" action="{{route('supervisor.rekap-harian.export')}}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="">Tanggal Awal</label>
                        <input type="date" class="form-control" name="tanggal_dari" required>
                    </div>

                    <div class="mb-3">
                        <label for="">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tanggal_sampai" required>
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

<script>
    const now = new Date();

    // Format the date as dd M YYYY
    const options = { day: '2-digit', month: 'long', year: 'numeric' };
    const formattedDate = now.toLocaleDateString('id-ID', options);

    // Set the formatted date to the span element
    document.getElementById('rekap-date').textContent = formattedDate;

</script>
@endsection
