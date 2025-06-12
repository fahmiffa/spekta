@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
@endpush
@section('main')
    <div class="page-heading">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="p-1">
                            <h5 class="card-title">{{ $data }}</h5>
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
                                    <th>Status</th>
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
                                            {{ $item->nomor }}
                                        </td>
                                        <td class="text-center">
                                            {{ $item->dokumen }}
                                        </td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn {{ $item->head->count() > 0 ? 'btn-warning' : 'btn-dark' }} btn-sm"
                                                data-toggle="tooltip" data-placement="top" title="Dokumen Detail"
                                                data-bs-toggle="modal" data-bs-target="#det{{ $item->id }}">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @foreach ($da as $item)
                        @php
                            $header = (array) json_decode($item->header);
                        @endphp
                        <div class="modal fade" id="det{{ $item->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel{{ $item->id }}">Dokumen
                                            {{ $item->nomor }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                         <div class="row mb-3">                                
                                            <div class="col-4">
                                                <h6>Nomor HP Pemohon</h6>
                                                {{ $header ? $header[3] : null }}
                                            </div>
                                            <div class="col-4 mb-3">
                                                <h6>Email Pemohon</h6>
                                                {{ $item->email}}
                                            </div> 
                                            <div class="col-4">
                                                <h6>{{ $item->type == 'umum' ? 'Fungsi' : 'Koordinat' }}</h6>
                                                {{ $item->type == 'umum' ? ucfirst($header[6]) : $header[8] }}
                                            </div> 
                                            <div class="col-4 mb-3">
                                                <h6>No. Dokumen Tanah</h6>
                                                {{ $header && isset($header[9]) ? $header[9] : null }}
                                            </div>                                                                                  
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-4 ">
                                                <h6>Jenis Verifikasi</h6>
                                                {{ $item->step }} Tahap
                                            </div>
                                            <div class="col-4 ">
                                                <h6>Verifikator</h6>
                                                &#9632; {!! ucfirst(implode('<br>&#9632; ', $item->verif)) !!}
                                            </div>
                                            <div class="col-4 ">
                                                <h6>Jenis Permohonan</h6>
                                                {{ ucfirst($item->type) }}
                                            </div>
                                        </div>
                                        @if ($item->head->count() > 0)
                                            <h6>Dokumen Perbaikan</h6>
                                            <ul>
                                                @if ($item->parents)
                                                    <li>{{ $item->parents->reg }} ({{ $item->parents->nomor }}) <a
                                                            target="_blank"
                                                            href="{{ route('monitoring.doc', ['id' => md5($item->parents->id)]) }}"
                                                            class="btn btn-sm btn-danger mb-2"><i
                                                                class="bi bi-file-pdf"></i></a>
                                                        ({{ $item->parents->note }})
                                                    </li>
                                                @endif
                                                @foreach ($item->parents->tmp->whereNotNull('deleted_at') as $val)
                                                    <li>{{ $val->reg }} ({{ $val->nomor }}) <a target="_blank"
                                                            href="{{ route('monitoring.doc', ['id' => md5($val->id)]) }}"
                                                            class="btn btn-sm btn-danger mb-2"><i
                                                                class="bi bi-file-pdf"></i></a>
                                                        ({{ $val->note }})
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        <h6>Catatan Pending</h6>
                                        <p>{{$item->hold_note}}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </diV>
            </div>

        </section>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>
@endpush
