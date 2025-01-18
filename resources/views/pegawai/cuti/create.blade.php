@extends('layouts.authenticated')

@section('content')
<div class="page-body">
    <div class="container-xl">

        <!-- Card untuk Form -->
        <div class="card col-md-6 mx-auto">
            <div class="card-body">
                <form action="{{ route('home-pegawai.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf <!-- CSRF Token for security -->

                    <!-- Input Hidden -->
                    <input type="hidden" value="123" name="id_pegawai"> <!-- Temporary hardcoded ID -->

                    <!-- Keterangan -->
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <select name="keterangan" id="keterangan" class="form-control" required>
                            <option value="">--Pilih Keterangan--</option>
                            <option value="Cuti" selected>Cuti</option>
                            <option value="Izin">Izin</option>
                            <option value="Sakit">Sakit</option>
                        </select>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" cols="30" rows="5" placeholder="Isi keterangan ..." required></textarea>
                    </div>

                    <!-- Tanggal -->
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" id="tanggal" class="form-control" name="tanggal" required>
                    </div>

                    <!-- Surat Keterangan -->
                    <div class="mb-3">
                        <label for="file" class="form-label">Surat Keterangan</label>
                        <input type="file" class="form-control" name="file" required>
                    </div>

                    <!-- Hidden Status Pengajuan -->
                    <input type="hidden" name="status_pengajuan" value="Pending">

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">Ajukan</button>
                </form>

            </div>
        </div>

    </div>
</div>

<script>
    // Get today's date
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
    const dd = String(today.getDate()).padStart(2, '0');

    // Format today's date as YYYY-MM-DD
    const todayStr = `${yyyy}-${mm}-${dd}`;

    // Calculate the date one week from today
    const nextWeek = new Date(today);
    nextWeek.setDate(today.getDate() + 7);
    const yyyyNext = nextWeek.getFullYear();
    const mmNext = String(nextWeek.getMonth() + 1).padStart(2, '0');
    const ddNext = String(nextWeek.getDate()).padStart(2, '0');
    const nextWeekStr = `${yyyyNext}-${mmNext}-${ddNext}`;

    // Set the min, max, and default value for the input
    const tanggalInput = document.getElementById('tanggal');
    tanggalInput.min = todayStr;
    tanggalInput.max = nextWeekStr;
    tanggalInput.value = todayStr; // Set default value to today's date
</script>
@endsection


