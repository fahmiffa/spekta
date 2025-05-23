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
                        <div class="p-1">
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
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($da as $item)
                                    @php

                                        $header = (array) json_decode($item->doc->header);
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">
                                            {{ $item->doc->reg }}
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
                                            {{ $item->doc->region ? 'Desa/Kel. ' . $item->doc->region->name . ', ' : null }}
                                            {{ $item->doc->region ? 'Kec. ' . $item->doc->region->kecamatan->name : null }}
                                        </td>
                                        <td class="text-center">
                                            {{ $item->doc->numbDoc('bak') }}
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                @if ($item->type == 'lead')
                                                    @if ($item->doc->bak)
                                                        @if ($item->doc->bak->status == 2)
                                                            <a class="btn btn-{{ $item->doc->bak->status == 2 ? 'dark' : 'success' }} btn-sm"
                                                                href="{{ route('step.news', ['id' => md5($item->head)]) }}"
                                                         data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Dokumen Draft"
                                                                >
                                                                <i
                                                                    class="bi bi-{{ $item->doc->bak->status == 2 ? 'archive' : 'send' }}"></i>
                                                            </a>

                                                            <button 
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Tanda Tangan Dokumen"
                                                                onclick="location.href='{{ route('sign.news', ['id' => md5($item->doc->bak->id)]) }}'"
                                                                class="btn {{$item->doc->bak->signs ? 'btn-success' : 'btn-primary'}} btn-sm mx-2"><i
                                                                    class="bi bi-vector-pen"></i></button>
                                                        @endif

                                                        <a class="btn {{ $item->bak ? 'btn-success' : 'btn-danger' }} btn-sm"
                                                            target="_blank"
                                                            href="{{ route('preview', ['id' => md5($item->doc->bak->id), 'par' => 'bak']) }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Lihat Dokumen">
                                                            <i class="bi bi-file-pdf"></i>
                                                        </a>
                                                    @else
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('step.news', ['id' => md5($item->head)]) }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Submit Dokumen">
                                                            <i class="bi bi-send"></i>
                                                        </a>
                                                    @endif
                                                @else
                                                    @if ($item->doc->bak)
                                                        @if ($item->doc->bak->grant == 0 && $item->doc->bak->status == 2 && auth()->user()->roles->kode != 'SU')
                                                            <button data-bs-toggle="tooltip" data-bs-placement="top"
                                                            data-bs-original-title="Tanda Tangan Dokumen"
                                                                onclick="location.href='{{ route('sign.news', ['id' => md5($item->doc->bak->id)]) }}'"
                                                                class="btn btn-primary btn-sm mx-2"><i
                                                                    class="bi bi-vector-pen"></i></button>
                                                        @endif                                      

                                                        <a class="btn {{ $item->doc->bak->grant == 1 ? 'btn-success' : 'btn-danger' }} btn-sm"
                                                                target="_blank"
                                                                href="{{ route('preview', ['id' => md5($item->doc->bak->id), 'par' => 'bak']) }}"
                                                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Lihat Dokumen">
                                                                <i class="bi bi-file-pdf"></i>
                                                            </a>
                                                    @endif
                                                @endif

                                                @if (auth()->user()->roles->kode == 'SU')
                                                    <a class="btn btn-dark btn-sm ms-1"
                                                        href="{{ route('super.bak', ['id' => md5($item->head)]) }}"                                                        
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Edit Dokumen">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>

                                                    <form onsubmit="return myConfirm('hapus');"
                                                        action="{{ route('super.bak.destroy', md5($item->id)) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger ms-1"
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Hapus Dokumen">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
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

            @foreach ($da as $item)
                @if ($item->doc->bakTemp && $item->doc->bakTemp->count() > 0)
                    <div class="modal fade" id="des{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel{{ $item->id }}">Catatan Dokumen
                                        Ini di tolak</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <ul>
                                        @foreach ($item->doc->bakTemp as $val)
                                            <li>{{ $val->reason }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

        </section>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>
@endpush
