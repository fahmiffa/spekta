@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush
@section('main')
    <div class="page-heading">

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between py-3">
                        <div class="p-2">
                            <h5 class="card-title">Penugasan {{ $data }}</h5>
                        </div>
                        <div class="p-2">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Registrasi</th>
                                    <th width="17%">Pemohon</th>
                                    <th>Nama Bangunan</th>
                                    <th width="17%">Lokasi Bangunan</th>
                                    <th>No. Dokumen</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($da as $item)
                                    @if ($item->task)
                                        @php
                                            $header = (array) json_decode($item->header);
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">
                                                {{ $item->reg }}
                                            </td>
                                            <td>
                                                <h6 class="mb-0">Nama :</h6>{{ $header ? $header[2] : null }}
                                                <h6 class="mb-0">Alamat :</h6>{{ $header ? $header[4] : null }}
                                            </td>
                                            <td class="text-center">
                                                {{ $header ? $header[5] : null }}
                                            </td>
                                            <td class="text-center">
                                                {{ $header ? $header[7] : null }}<br>
                                                {{ $item->region ? 'Desa/Kel. ' . $item->region->name : null }},
                                                {{ $item->region ? 'Kec. ' . $item->region->kecamatan->name : null }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item->nomor }}
                                            </td>
                                            <td class="text-center">
                                                {{ $item->dokumen }}
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    @if ($item->status == 1)
                                                        <a target="_blank"
                                                            href="{{ route('monitoring.doc', ['id' => md5($item->id)]) }}"
                                                            class="btn btn-sm btn-danger"><i
                                                                class="bi bi-file-pdf"></i></a>
                                                    @else

                                                        @if($item->open == 1)
                                                            <a href="{{ route('step.verifikasi', ['id' => md5($item->id)]) }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-original-title="Verifikasi"
                                                                class="btn btn-sm btn-primary me-1"><i class="bi bi-pencil"></i></a>
                                                        @endif

                                                        @if ($item->steps->count() > 0)
                                                            <a target="_blank"
                                                                href="{{ route('monitoring.doc', ['id' => md5($item->id)]) }}"
                                                                class="btn btn-sm btn-danger"><i
                                                                    class="bi bi-file-pdf"></i></a>
                                                        @endif
                                                    @endif
                                                    @if ($item->head->count() > 0)
                                                        <button class="btn btn-warning btn-sm ms-1" 
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#des{{ $item->id }}">
                                                            Perbaikan
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @foreach ($da as $item)
                @if ($item->head->count() > 0)
                    <div class="modal fade" id="des{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel{{ $item->id }}">Dokumen Ini di
                                        tolak
                                        verifikasi
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @if ($item->temp->whereNotNull('deleted_at')->count() > 0)
                                        <h6>Dokumen Perbaikan</h6>
                                        <ul>                     
                                            @foreach ($item->temp->whereNotNull('deleted_at') as $val)
                                                <li class="text-wrap text-break">{{ $val->reg }} ({{ $val->nomor }}) <a
                                                        target="_blank"
                                                        href="{{ route('monitoring.doc', ['id' => md5($val->id)]) }}"
                                                        class="btn btn-sm btn-danger mb-2"><i class="bi bi-file-pdf"></i></a>
                                                    <p>({{ $val->note }})</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        </section>
        <!-- Basic Tables end -->

    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>
@endpush
