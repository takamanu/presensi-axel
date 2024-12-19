@extends('layouts.admin-header')

@section('content')
    <style>
        .lateness-badge {
            color: white !important;
            /* Ensures text is white */
            width: 200px;
            /* Fixed width for badges */
            text-align: center;
            /* Center-align the text */
            display: inline-block;
            /* Ensures proper alignment */
            overflow: hidden;
            /* Prevents overflow text */
            text-overflow: ellipsis;
            /* Truncates overflow text if needed */
            white-space: nowrap;
            /* Keeps text on a single line */
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
                            <!-- Dropdown for month -->
                            <select class="form-select" name="bulan">
                                @php
                                    $months = [
                                        'Januari',
                                        'Februari',
                                        'Maret',
                                        'April',
                                        'Mei',
                                        'Juni',
                                        'Juli',
                                        'Agustus',
                                        'September',
                                        'Oktober',
                                        'November',
                                        'Desember',
                                    ];
                                @endphp
                                @foreach ($months as $index => $month)
                                    <option value="{{ $index + 1 }}" {{ $index + 1 == $bulan ? 'selected' : '' }}>
                                        {{ $month }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Dropdown for year -->
                            <select class="form-select" name="tahun">
                                @php
                                    $currentYear = \Carbon\Carbon::now()->year;
                                    $maxYear = $currentYear + 5;
                                @endphp
                                @for ($year = $currentYear; $year <= $maxYear; $year++)
                                    <option value="{{ $year }}" {{ $year == $tahun ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>

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

            <table class="table-bordered table">
                <tr class="text-center">
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Total Jam</th>
                    <th>Total Terlambat</th>
                </tr>

                @forelse ($presensi as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $data->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($data->tanggal_masuk)->translatedFormat('d F Y') }}</td>
                        <td class="text-center">{{ $data->jam_masuk }}</td>
                        <td class="text-center">{{ $data->jam_keluar }}</td>
                        <td class="text-center">
                            @if ($data->jam_keluar)
                                {{ \App\Helpers\TimeHelper::calculateTotalHours($data->jam_masuk, $data->jam_keluar) }}
                            @else
                                <span>0 Jam 0 Menit</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $lateMinutes = \App\Helpers\TimeHelper::calculateLateMinutes(
                                    $data->jam_masuk,
                                    '08:00:00',
                                );
                                $lateHours = floor($lateMinutes / 60);
                                $remainingMinutes = $lateMinutes % 60;
                            @endphp

                            @if ($lateMinutes > 0)
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
                <form method="POST" action="{{ route('admin.rekap-bulanan-export') }}">
                    @csrf
                    <div class="modal-body">
                        <!-- Month Selection -->
                        <div class="mb-3">
                            <label for="bulan" class="form-label">Pilih Bulan</label>
                            <select class="form-control" name="bulan" id="bulan" required>
                                <option value="">Pilih Bulan</option>
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>

                        <!-- Year Selection -->
                        <div class="mb-3">
                            <label for="tahun" class="form-label">Pilih Tahun</label>
                            <select class="form-control" name="tahun" id="tahun" required>
                                <option value="">Pilih Tahun</option>
                                @php
                                    $currentYear = now()->year;
                                    $endYear = $currentYear + 5;
                                @endphp
                                @for ($year = $currentYear; $year <= $endYear; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Export</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
