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
                        <div class="p-1 d-flex justify-content-between">
                            <a href="{{ route('verifikasi.create') }}" class="btn btn-primary btn-sm">Tambah
                                {{ $data }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-5">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table1" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>No. Registrasi</th>
                                    <th width="17%">Pemohon</th>
                                    <th>Nama Bangunan</th>
                                    <th width="17%">Lokasi Bangunan</th>
                                    <th>No. Dokumen</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($da as $item)
                                    @php
                                        $header = (array) json_decode($item->header);
                                        $numb = '62' . ltrim($header[3], 0);                                                                                
                                        $link = $item->old ? $item->old->links->where('ket', 'verifikasi')->first() : $item->links->where('ket', 'verifikasi')->first();                                        
                                        if($link && $link->short)
                                        {
                                            $uri = route('link', ['id' => $link->short]);
                                        }
                                        else
                                        {
                                            $uri = null;
                                        }
                                        $reg = $item->reg;
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
                                            @if ($item->tang)
                                                {{ date('d-m-Y', strtotime($item->tang)) }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{ $item->dokumen }} 
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center">
                                                @if ($item->open == 0 && $item->grant == 0)
                                                    <form onsubmit="return confirm('Anda akan mengirim formulir ke Verifikator ?');"
                                                        action="{{ route('doc.status', md5($item->id)) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary me-1"
                                                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Kirim Dokumen">
                                                            <i class="bi bi-send"></i>
                                                        </button>
                                                    </form>

                                                    
                                                    @if($uri)
                                                        @php
                                                            $psn = "Yth. Bapak/Ibu $header[2] dengan Nomor Registrasi $reg Kelengkapan Dokumen Permohonan PBG dan/atau SLF anda telah dilakukan verifikasi dan terdapat Perbaikan Dokumen. Detail catatan dan perbaikan dokumen dapat dilihat melalui tautan berikut : $uri \nTerima Kasih #DPUPRKabTegal";
                                                        @endphp
                                                        <a target="_blank"
                                                            href="https://wa.me/{{ $numb }}?text={{ urlencode($psn) }}"
                                                            class="btn btn-sm btn-success my-1 me-1"><i class="bi bi-whatsapp"></i>
                                                        </a>
                                                    @endif
                                                @endif

                                                @if ($item->status == 5 && $item->parent == null)
                                                    <a href="{{ route('verifikasi.edit', $item->id) }}"                                                        
                                                        data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Edit Dokumen"
                                                        class="btn btn-sm btn-primary me-1">Edit</a>
                                                @endif

                                                @if ($item->status == 1)
                                                    @if ($item->grant == 0)
                                                        <button type="button" class="btn btn-info btn-sm mx-2"                                            
                                                            data-bs-placement="top" data-bs-original-title="Dokumen belum diverifikasi"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#ver{{ $item->id }}">
                                                            Verifikasi
                                                        </button>
                                                    @endif
                                                @endif

                                                <button type="button"
                                                    class="btn {{ $item->head->count() > 0 ? 'btn-warning' : 'btn-dark' }} btn-sm"                                                    
                                                    data-bs-placement="bottom" data-bs-original-title="Dokumen Detail"
                                                    data-bs-toggle="modal" data-bs-target="#det{{ $item->id }}">
                                                    Detail
                                                </button>

                                                @if ($item->status == 5 && auth()->user()->roles->kode == 'SU')
                                                    <form onsubmit="return myConfirm('hapus');"
                                                        action="{{ route('verifikasi.destroy', $item->id) }}"
                                                        method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger mx-2"                                                            
                                                            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Hapus Dokumen">
                                                            
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if ($item->status == 1)
                                                    <a target="_blank" 
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Lihat Dokumen"
                                                        href="{{ route('monitoring.doc', ['id' => md5($item->id)]) }}"
                                                        class="btn btn-sm btn-danger mx-2"><i
                                                            class="bi bi-file-pdf"></i></a>
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
                @php
                    $header = (array) json_decode($item->header);
                @endphp
                <div class="modal fade" id="det{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                        {{ $item->type == 'umum' ? str_replace('_',' ',ucfirst($header[6])) : $header[8] }}
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

                <div class="modal fade" id="ver{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel{{ $item->id }}">Verifikasi Dokumen
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('doc.approve', ['id' => md5($item->id)]) }}" method="post">
                                    @csrf
                                    <p>Anda akan menerima dokumen ini dan melanjutkan ke Penunjukan TPA/TPT ?
                                    <p>
                                        <button class="btn btn-success rounded-pill">Lanjutkan</button>
                                        <button type="button" class="btn btn-danger rounded-pill" data-bs-toggle="modal"
                                            data-bs-target="#re{{ $item->id }}">Perbaikan</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="re{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel{{ $item->id }}">Perbaikan Dokumen Verifikasi
                                    </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('doc.reject', ['id' => md5($item->id)]) }}" method="post">
                                    @csrf
                                    <p class="mb-3">Anda akan melakukan perbaikan dokumen ini ?
                                    <p>
                                        <label>Catatan : </label>
                                        <textarea class="form-control" name="noted" required></textarea>
                                        <button class="btn btn-success rounded-pill mt-3">Submit</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/datatables.js') }}"></script>
@endpush
