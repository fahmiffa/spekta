@extends('layout.base')
@push('css')
<link rel="stylesheet" href="{{ asset('assets/pdf/view.css') }}">
<style>
    .imgs {
        object-fit: cover;
        width: 50px;
        height: 50px;
    }

    #canvas-container {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    canvas {
        border: 1px solid black;
        background-color: white;
    }

    @media (max-width: 576px) {
        #canvas-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: auto;
        }

        canvas {
            width: 100%;
            height: auto;
            border: 1px solid black;
            background-color: white;
        }
    }
</style>
@endpush
@section('main')
    <div class="container">
        @php
            $header = json_decode($news->doc->header);
        @endphp
        <div class="card card-body">

            <div id="toolbar" class="d-flex mx-auto text-center mb-2 d-none">
                 <button class="btn btn-sm btn-dark" id="prev">Previous</button>
                     <div class="p-1">
                            <span>Page: <span id="page-num"></span> / <span id="page-count"></span></span>
                    </div>
                 <button class="btn btn-sm btn-dark" id="next">Next</button>
            </div>
            <div class="col-12 col-sm-12 mx-auto mb-3">
                <div id="loading" class="my-5 mx-auto text-center">Loading PDF...</div>
                <div id="con" class="d-none">
                    <div id="pdf-viewer" class="mb-3">
                        <div id="canvas-container">
                            <canvas id="pdf-canvas" style="display: none;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="divider">
                    <div class="divider-text">{{ $lead ? 'Ketua/Notulen' : 'Anggota' }}</div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-12 mx-auto">
                        <div class="card text-center border border-dark fw-bold">
                            <div class="card-header">{{ $sign->users->name }}</div>
                            <div class="card-body">
                                @if ($sign->barp)
                                    <img src="{{ $sign->barp }}" class="mx-auto d-block img-fluid">
                                @endif
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary btn-sm rounded-pill mx-auto d-block signs"
                                    data-id="{{ md5($sign->user) }}" type="button">Tanda Tangan</button>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($lead)
                    <form
                        action="{{ $doc == 'bak' ? route('pub.bak', ['id' => md5($news->id)]) : route('pub.barp', ['id' => md5($news->id)]) }}"
                        method="post">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm rounded-pill text-center">
                            <i class="bi bi-send"></i>
                            Publish
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="modal fade" id="signatureModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Tanda Tangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form
                            action="{{ $doc == 'bak' ? route('signed.news', ['id' => md5($news->id)]) : route('signed.meet', ['id' => md5($news->id)]) }}"
                            id="sign" method="post" enctype="multipart/form-data">
                            @csrf
                            <canvas id="signatureCanvas" class="border border-light mx-auto d-block mb-3" width="450"
                                height="200"></canvas>
                            <button type="button" id="clear"
                                class="btn btn-dark btn-sm my-3 rounded-pill">Clear</button>
                            <input type="file" class="d-none" name="sign" id="signed">
                            <input type="hidden" name="user" id="user">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary submit">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/pdf/lib.js') }}"></script>
    <script src="{{ asset('assets/pdf/pdf.min.js') }}"></script>
    <script src="{{ asset('assets/pdf/merges.js') }}"></script>

    <script>
        $(document).ready(function() {
            var canvas = document.getElementById('signatureCanvas');
            var signaturePad = new SignaturePad(canvas);

            $('#clear').on('click', function() {
                signaturePad.clear();
            });

            $('.submit').on('click', function(e) {

                if (signaturePad.isEmpty()) {
                    alert("Please provide a signature first.");
                    e.preventDefault();
                } else {
                    const dataURL = signaturePad.toDataURL('image/png');
                    console.log(dataURL);

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
                    document.getElementById('sign').submit();
                }

            });
        });

        $('.signs').on('click', function(e) {
            e.preventDefault();
            $('#user').val($(this).attr('data-id'));
            var myModal = new bootstrap.Modal(document.getElementById('signatureModal'));
            myModal.show();
        });

        const { PDFDocument } = PDFLib;
       let pdfDoc = null,
        pageNum = 1,
        pageRendering = false,
        pageNumPending = null,
        scale = 1.2,
        pdfDataUri,
        result = null,
        canvas = document.getElementById('pdf-canvas'),
        ctx = canvas.getContext('2d');

        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('assets/pdf/worker.min.js') }}";

        const pdfUrls = @json($uri);

        merges(pdfUrls).then((res) => {
            result = res;
            pdfjsLib.getDocument(res).promise.then(function(pdfDoc_) {
                pdfDoc = pdfDoc_;
                document.getElementById('page-count').textContent = pdfDoc.numPages;
                renderPage(pageNum);
                document.getElementById('loading').style.display = 'none';
                canvas.style.display = 'block';
                document.getElementById('con').classList.remove('d-none');;
                document.getElementById('toolbar').classList.remove('d-none');;
            }, function(reason) {
                document.getElementById('loading').textContent = "Failed to load PDF.";
            });

        }).catch((e) => {
            console.log(e);
        });

        document.getElementById('prev').addEventListener('click', onPrevPage);
        document.getElementById('next').addEventListener('click', onNextPage);
    </script>
@endpush
