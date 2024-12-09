@extends('layouts.admin-header')

@section('content')
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            <a href="{{ route('admin.add-jabatan') }}" class="btn btn-primary"><span class="text"><i
                        class="fa-solid fa-circle-plus"></i> Tambah
                    Data</span></a>
            <div class="row row-deck row-cards mt-2">

                <table class="table-bordered table">
                    <thead>
                        <tr class="text-center">
                            <th>No.</th>
                            <th>nama Jabatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($jabatans->isEmpty())
                            <tr>
                                <td colspan="3">Data masih kosong, silahkan tambahkan data baru</td>
                            </tr>
                        @else
                            @php $no = 1; @endphp
                            @foreach ($jabatans as $i)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ $i->jabatan }}</td>
                                    <td class="items- text-center">
                                        <div style="display: flex; justify-content: center; gap: 5px;">
                                            <a href="{{ route('admin.edit-jabatan', $i->id) }}"
                                                class="badge bg-primary badge-pill">Edit</a>
                                            <form action="{{ route('admin.destroy-jabatan', $i->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="color: aliceblue"
                                                    class="badge bg-danger badge-pill">Hapus</button>
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
    </div>
@endsection
