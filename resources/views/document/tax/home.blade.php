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
                            <h5 class="card-title">Data {{ $data }}</h5>
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
                                    <th>Nilai Retribusi</th>
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
                                            {{ $item->reg }}
                                        </td>
                                        <td>
                                            <h6 class="mb-0">Nama</h6>{{ $header ? $header[2] : null }}
                                            <h6 class="mb-0">Alamat</h6>
                                            {{ $header ? $header[4] : null }}
                                        </td>
                                        <td class="text-center">
                                            {{ $header ? $header[5] : null }}
                                        </td>
                                        <td>
                                            {{ $header ? $header[7] : null }}<br>
                                            {{ $item->region ? 'Desa/Kel. ' . $item->region->name : null }},
                                            {{ $item->region ? 'Kec. ' . $item->region->kecamatan->name : null }}
                                        </td>
                                        <td class="text-center">
                                            @if ($item->tax)
                                                @php
                                                    $tax = (object) json_decode($item->tax->parameter);
                                                @endphp
                                                {{ number_format($tax->totRetri, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">                                 
                                                @if ($item->bak)

                                                    @if(in_array(auth()->user()->id, $item->sign->where('type','lead')->pluck('user')->toArray()))
                                                        <a class="btn btn-primary btn-sm me-1"
                                                            href="{{ route('step.tax', ['id' => md5($item->id)]) }}"
                                                            data-toggle="tooltip" data-placement="top" title="Submit Dokumen">
                                                            <i class="bi bi-send"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if($item->tax)
                                                        <a target="_blank"
                                                            href="{{ route('doc.tax', ['id' => md5($item->id)]) }}"
                                                            class="btn btn-sm btn-danger"><i class="bi bi-file-pdf"></i></a>                                                
                                                    @endif
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
