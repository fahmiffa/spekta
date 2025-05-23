@extends('layout.base')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/pdf/view.css') }}">
    <style>
        .imgs {
            object-fit: cover;
            width: 50px;
            height: 50px;
        }
    </style>
@endpush
@section('main')
    <div class="container">
        <div class="row">
            @php
                $uri = [];
                if ($head->bak && $head->bak->status == 1) {
                    array_push($uri, 0);
                }
                if ($head->barp && $head->barp->status == 1) {
                    array_push($uri, 1);
                }
            @endphp

            @foreach ($uri as $val)
                <div class="col-md-6 col-sm-12">
                    <div class="mx-auto text-center my-1" id="toolbar{{ $val }}">
                        <button id="prev{{ $val }}" class="btn btn-dark btn-sm">Previous</button>
                        <span>Page: <span id="page-num{{ $val }}"></span> / <span
                                id="page-count{{ $val }}"></span></span>
                        <button id="next{{ $val }}" class="btn btn-dark btn-sm">Next</button>
                    </div>
                    <div id="loading{{ $val }}" class="text-center my-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <canvas id="pdf-canvas{{ $val }}" style="display: none;" class="border border-light w-100 mb-3"></canvas>

                    @if ($head->do == 0)
                        <button type="button" data-val="{{ $val }}"
                            class="btn btn-primary btn-sm rounded-pill mx-auto d-block my-3 signs">
                           {{$head->bak->primary == 'TPT' ? 'Tanda Tangan' : 'Verifikasi'}}</button>
                    @endif
                </div>
            @endforeach
        
            <div class="modal fade sign" id="signature" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">{{$head->bak->primary == 'TPT' ? 'Tanda Tangan' : 'Verifikasi'}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('ba.signed', ['id' => md5($head->id)]) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                    <canvas class="border border-light mx-auto d-block canvas {{$head->bak->primary == 'TPT' ? null : 'd-none'}}" width="450"
                                        height="200"></canvas>
                                <input type="file" class="d-none" name="sign" id="signed">
                                <input type="hidden" name="type" id="type">

                                @if($head->bak->primary == 'TPT')
                                    <button type="button" id="clear"
                                        class="btn btn-dark btn-sm my-3 rounded-pill clear">Hapus</button>
                                        <button type="submit" class="btn btn-success rounded-pill btn-sm save">Disetujui</button>
                                @else
                                    <button type="submit" class="btn btn-success rounded-pill btn-sm">Disetujui</button>
                                @endif
                                <button type="button" class="btn btn-danger rounded-pill btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#reject">Ditolak</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>      

            <div class="modal fade" id="verifikasi">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Verifikasi Dokumen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('ba.ver', ['id' => md5($head->id)]) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-success rounded-pill btn-sm">Approve</button>
                                {{-- <button type="button" class="btn btn-danger rounded-pill btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#reject">Reject</button> --}}
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="reject" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Menolak Dokumen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('ba.reject', ['id' => md5($head->id)]) }}" method="post">
                                @csrf
                                <p class="mb-3">Anda akan menolak dokumen ini ?
                                <p>
                                    <label>Catatan : </label>
                                    <textarea class="form-control" name="noted" required></textarea>
                                    <input type="hidden" name="type" id="typer">
                                    <button class="btn btn-success rounded-pill mt-3">Submit</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/pdf/lib.js') }}"></script>
    <script src="{{ asset('assets/pdf/pdf.min.js') }}"></script>
    <script src="{{ asset('assets/pdf/merge.js') }}"></script>

    <script>
        let pilePDF = [];
        const canvas = document.querySelector(".canvas");
        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('assets/pdf/worker.min.js') }}";

        window.addEventListener('DOMContentLoaded', function() {

            let pdfUrls;

            @if ($head->bak && $head->bak->status == 1)
                    @php
                        if($head->bak->files)
                        {
                            $uri = [
                                        route('bak.doc', ['id' => md5($head->bak->id)]), 
                                        asset('storage/' . $head->bak->files),
                                        route('doc.attach', ['id' => md5($head->id)]),
                                        route('doc.tax', ['id' => md5($head->id)])
                                    ];
                        }   
                        else
                        {
                            $uri = [
                                        route('bak.doc', ['id' => md5($head->bak->id)]), 
                                        route('doc.attach', ['id' => md5($head->id)]),
                                        route('doc.tax', ['id' => md5($head->id)])
                                    ];
                        }                  
                    @endphp

                    pdfUrls = @json($uri);
            @endif

            pile(pdfUrls).then((res)=>{

                @if ($head->barp && $head->barp->status == 1)
                     res.push("{{ route('barp.doc', ['id' => md5($head->barp->id)]) }}");
                @endif

                res.map(function(val, i) {
                    genPDF(val, i);
                });
            }).catch((e)=>{
                console.log(e);
            });

        });
             
        var signaturePad = new SignaturePad(canvas);

        $('.signs').on('click', function() {
            var id = $(this).attr('data-val');
            $('#type').val(id);
            $('#typer').val(id);
            var myModal = new bootstrap.Modal(document.getElementById('signature'));
            myModal.show();
            signaturePad.clear();
        });

        $('.ver').on('click', function() {
            var id = $(this).attr('data-val');
            var myModal = new bootstrap.Modal(document.getElementById('verifikasi'));
            myModal.show();
        });

        $('.clear').on('click', function() {
            signaturePad.clear();
        });

        $('.save').on('click', function(e) {
            if (signaturePad.isEmpty()) {
                alert("Please provide a signature first.");
                e.preventDefault();
            } else {
                const dataURL = signaturePad.toDataURL('image/png');

                const byteString = atob(dataURL.split(',')[1]);
                const ab = new ArrayBuffer(byteString.length);
                const ia = new Uint8Array(ab);
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                const blob = new Blob([ab], { type: 'image/png' });
                const file = new File([blob], 'signature.png', { type: 'image/png' });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                document.getElementById('signed').files = dataTransfer.files;
            }
        });     

    </script>
@endpush
