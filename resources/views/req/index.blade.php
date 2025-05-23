@extends('layout.base')
@push('css')
<link rel="stylesheet" href="{{asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/compiled/css/table-datatable-jquery.css')}}">
@endpush
@section('main')
<div class="page-heading">
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div class="p-1">
                        <h5 class="card-title">{{$data}}</h5>
                    </div>
                    <div class="p-1">
                    </div>
                </div>
            </div>
            <div class="card-body p-5">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Pengajuan</th>
                                <th>No. Registrasi</th>
                                <th width="17%">Pemohon</th>
                                <th>Nama Bangunan</th>
                                <th width="17%">Lokasi Bangunan</th>
                                <th>No. Dokumen</th>
                                <th>Upload SIMBG</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($da as $item)
                                @php
                                    $header = (array) json_decode($item->header);
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        {{ $header ? strtoupper($header[1]) : null }}
                                    </td>
                                    <td class="text-center">{{ $item->reg }}</td>
                                    <td>
                                        <h6 class="mb-0">Nama :</h6>{{ $header ? $header[2] : null }}
                                        <h6 class="mb-0">Alamat :</h6>{{ $header ? $header[4] : null }}
                                    </td>
                                    <td class="text-center">
                                        {{ $header ? $header[5] : null }}
                                    </td>
                                    <td class="text-center">
                                        {{ $header ? $header[7] : null }}<br>
                                        {{ $item->region ? 'Ds. ' . $item->region->name : null }},
                                        {{ $item->region ? 'Kec. ' . $item->region->kecamatan->name : null }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item->numbDoc('barp') }}
                                    </td>
                                    <td class="text-center">
                                        @if ($item->simbg == 1)
                                            <button type="button" class="btn btn-success btn-sm"
                                                data-bs-placement="top" data-bs-original-title="Aktif"
                                                data-bs-toggle="modal" data-bs-target="#re{{ $item->id }}">
                                                Sudah
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm"
                                                data-bs-placement="top" data-bs-original-title="Tidak Aktif"
                                                data-bs-toggle="modal" data-bs-target="#re{{ $item->id }}">
                                                Belum
                                            </button>
                                        @endif
                                    </td>
                                    <td>                        
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a target="_blank" href="{{ route('req.doc', ['id'=>md5($item->id)]) }}" class="btn btn-sm btn-danger"><i class="bi bi-file-pdf"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </diV>
        </div>

        @if (auth()->user()->ijin('bak') && auth()->user()->roles->kode != 'SU')
            @foreach ($da as $item)
                <div class="modal fade" id="re{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">
                                STATUS SIMBG
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('simbg', ['id' => md5($item->id)]) }}" method="post">
                                    @csrf
                                    <p class="mb-3">Anda akan Mengubah Status SIMBG {{ $item->name }} </p>
                                    <button class="btn btn-success btn-sm">Ya</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </section>
</div>
@endsection

@push('js')
<script src="{{asset('assets/extensions/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/static/js/pages/datatables.js')}}"></script>
@endpush
