<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | {{ env('APP_TAG') }}</title>

    <meta content="{{ env('APP_DES') }}" name="description">
    <meta content="{{ env('APP_NAME') }}" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
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

        #loading {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            color: #333;
            z-index: 100;
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
</head>

<body>
    <nav class="navbar navbar-light">
        <div class="container d-block">
            <h6>Tanda Tangan Pemohon</h6>
        </div>
    </nav>
    <div class="container">
        <div class="card card-body">
            @if ($vals)
                <div id="toolbar" class="d-flex mx-auto text-center mb-2">
                    <button class="btn btn-sm btn-dark" id="prev">Previous</button>
                    <div class="p-1">
                        <span>Page: <span id="page-num"></span> / <span id="page-count"></span></span>
                    </div>
                    <button class="btn btn-sm btn-dark" id="next">Next</button>
                </div>
                <div class="col-12 col-sm-12 mx-auto">
                    <div id="loading" class="my-5">Loading PDF...</div>
                    <div id="con" class="d-none">
                        <div id="pdf-viewer" class="mb-3">
                            <div id="canvas-container">
                                <canvas id="pdf-canvas" style="display: none;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 mx-auto">
                         <div class="card border border-dark fw-bold">
                            @php
                                $header = (array) json_decode($gen->reff->doc->header);
                            @endphp
                            <div class="card-header text-center">{{ $header[2] }}</div>
                            <div class="card-body">
                                <form action="{{ route('ttdp', ['id' => md5($gen->bak)]) }}" id="ttd" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <canvas id="signatureCanvas" class="border border-light mx-auto d-block mb-3 w-100"></canvas>
                                    <input type="file" class="d-none" name="sign" id="signed">
                                    <div class="form-check d-flex-inline">
                                        <input class="form-check-input" type="checkbox" id="check">
                                        <span class="d-block mx-1"></span>
                                        <label class="form-check-label small" for="flexCheckChecked">
                                            Saya telah membaca dan menyetujui hasil konsultasi
                                        </label>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" id="clear" class="btn btn-dark btn-sm my-3 rounded-pill">Hapus</button>
                                        <button type="submit" id="submit" class="btn btn-primary rounded-pill btn-sm" disabled>Simpan</button>
                                    </div>
                                </form>
                                <div id="countdown" class="countdown text-center">{{ $val }}</div>
                            </div>
                        </div>
                </div>
                @else
                    <h4 class="text-center text-danger">Link tidak valid, silahkan hubungi petugas terkait</h4>
                @endif
        </div>
    </div>
</body>
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/signature_pad.js') }}"></script>
<script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
<script src="{{ asset('assets/compiled/js/app.js') }}"></script>
<script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/pdf/lib.js') }}"></script>
<script src="{{ asset('assets/pdf/pdf.min.js') }}"></script>
<script src="{{ asset('assets/pdf/merges.js') }}"></script>

<script>
    const checkbox = document.getElementById('check');
    const submitButton = document.getElementById('submit');

    checkbox.addEventListener('change', function() {
        submitButton.disabled = !this.checked;
    });
    let load = false;
    let countdown;
    let timeLeft = {{ $val }};
    var canva = document.getElementById('signatureCanvas');
    var signaturePad = new SignaturePad(canva);
    @if ($gen->reff->signs)
        signaturePad.fromDataURL("{{ $gen->reff->signs }}");
    @endif

    document.getElementById('clear').addEventListener('click', function(e) {
        signaturePad.clear();
    });

    document.getElementById('submit').addEventListener('click', function(e) {
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
            const blob = new Blob([ab], {
                type: 'image/png'
            });
            const file = new File([blob], 'signature.png', {
                type: 'image/png'
            });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('signed').files = dataTransfer.files;
        }
    });

    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }

    function runs() {
        clearInterval(countdown);
        timeLeft = {{ $val }}; // Reset waktu
        document.getElementById('countdown').textContent = formatTime(timeLeft);

        countdown = setInterval(function() {
            timeLeft--;
            document.getElementById('countdown').textContent = formatTime(timeLeft);

            if (timeLeft <= 0) {
                clearInterval(countdown);
                location.reload();
            }
        }, 1000);
    }

    const {
        PDFDocument
    } = PDFLib;
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
            runs();
        }, function(reason) {
            document.getElementById('loading').textContent = "Failed to load PDF.";
        });

    }).catch((e) => {
        console.log(e);
    });

    document.getElementById('prev').addEventListener('click', onPrevPage);
    document.getElementById('next').addEventListener('click', onNextPage);
</script>

</html>
