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
                            <h5 class="card-title">{{ $data }}</h5>
                        </div>
                        <div class="p-2">
                            <a href="{{ route('consultation.create') }}" class="btn btn-primary btn-sm">Tambah Data</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th width="17%">No. Registrasi</th>
                                    <th width="17%">Pemohon</th>
                                    <th>Nama Bangunan</th>
                                    <th width="17%">Lokasi Bangunan</th>
                                    <th>No. Surat</th>
                                    <th>Tanggal Konsultasi</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($da as $item)
                                    @php
                                        $header = (array) json_decode($item->doc->header);
                                        $surat = $item->doc->links->where('ket', 'surat_undangan')->values();
                                        if($surat->count() > 0)
                                        {
                                            $uri = route('link', ['id' => $surat[0]->short]);

                                        }
                                        else
                                        {
                                            $uri = null;
                                        }
                                        $numb = '62' . ltrim($header[3], 0);
                                        $reg = $item->doc->reg;
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
                                        <td class="text-center">{{ $header ? $header[5] : null }}</td>
                                        <td>
                                            {{ $header ? $header[7] : null }}<br>
                                            {{ $item->doc->region ? 'Desa/Kel. ' . $item->doc->region->name . ', ' : null }}
                                            {!! $item->doc->region ? $item->doc->region->kecamatan->name . '<br>' : null !!} 
                                        </td>
                                        <td class="text-center">
                                            @if($item->doc->surat)
                                                {{ $item->doc->surat->number }}                                       
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($item->doc->surat)
                                                @php
                                                  $time = explode('#',$item->doc->surat->waktu);
                                                @endphp
                                                {{ $time[2] }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <form onsubmit="return myConfirm('rollback ?');"
                                                    action="{{ route('consultation.destroy', $item->id) }}" method="POST">
                                                    <a href="{{ route('consultation.edit', $item->id) }}"
                                                        class="btn btn-sm btn-primary mb-1"><i class="bi bi-pencil"></i></a>
                                                    @if ($item->bak == null)
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger mb-1"><i
                                                                class="bi bi-trash"></i></button>
                                                    @endif
                                      
                                                    <a target="_blank"
                                                        href="{{ $uri }}"
                                                        class="btn btn-sm btn-warning"><i
                                                            class="bi bi-file-pdf"></i></a>

                                                    @php
                                                        $msg = "Yth.%0ABapak%2FIbu%20$header[2]%20dengan%20Nomor%20Registrasi%20$reg%20%0APermohonan%20PBG%20dan%2Fatau%20SLF%20anda%20akan%20dilakukan%20Penjadwalan%20Konsultasi%20sesuai%0AUndangan%20Terlampir%20pada%20tautan%20berikut%20%3A%20%0A$uri%0A%0AMohon%20kirimkan%20Share%20Location%20anda%20untuk%20Konfirmasi.%0AAtas%20perhatiannya%2C%20disampaikan%20terima%20kasih.%0ADPUPR%20Kabupaten%20Tegal";
                                                    @endphp
                                                    @if($uri)
                                                        <a target="_blank"
                                                            href="https://wa.me/{{ $numb }}?text={{ $msg }}"
                                                            class="btn btn-sm btn-success my-1"><i class="bi bi-whatsapp"></i>
                                                        </a>
                                                    @endif

                                                    <button type="button" class="btn btn-dark btn-sm"
                                                        data-bs-toggle="modal" data-bs-target="#det{{ $item->id }}">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </form>
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
                    $header = (array) json_decode($item->doc->header);
                @endphp
                <div class="modal fade" id="det{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel{{ $item->id }}">Dokumen
                                    {{ $item->doc->nomor }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h6>Ketua/Notulen</h6>
                                &#9632; {!! ucfirst(implode('<br>&#9632; ', $item->notulens)) !!}
                                <h6>Anggota</h6>
                                &#9632; {!! ucfirst(implode('<br>&#9632; ', $item->kons)) !!}
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
