<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOKUMEN {{ $head->nomor }}</title>
    <meta content="{{ env('APP_DES') }}" name="description">
    <meta content="{{ env('APP_NAME') }}" name="keywords">
    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/pdf/view.css') }}">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        #toolbar {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }

        #pdf-viewer {
            position: relative;
            width: 100%;
            height: calc(100vh - 50px);
            overflow: auto;
            background-color: #eee;
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
            #pdf-viewer {
                position: relative;
                width: 100%;
                max-height: 100vh;
                overflow: auto;
                background-color: #eee;
            }

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

    <div id="loading">Loading PDF...</div>

    <div id="toolbar">
        <button id="prev">Previous</button>
        <span>Page: <span id="page-num"></span> / <span id="page-count"></span></span>
        <button id="next">Next</button>
        <button id="download">Download</button>
    </div>

    <div id="pdf-viewer">
        <div id="canvas-container">
            <canvas id="pdf-canvas" style="display: none;"></canvas>
        </div>
    </div>

    <script src="{{ asset('assets/pdf/lib.js') }}"></script>
    <script src="{{ asset('assets/pdf/pdf.min.js') }}"></script>
    <script src="{{ asset('assets/pdf/merges.js') }}"></script>

    <script>
        const { PDFDocument } = PDFLib;
        let pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 1,
            pdfDataUri,
            result = null,
            canvas = document.getElementById('pdf-canvas'),
            ctx = canvas.getContext('2d');

            const pdfjsLib = window['pdfjs-dist/build/pdf'];
            pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('assets/pdf/worker.min.js') }}";
            
            @php
                if($head->bak->files)
                {
                    $uri = [
                                route('req.dok', ['id' => md5($head->id), 'par'=>'barp']),
                                route('req.dok', ['id' => md5($head->id), 'par'=>'bak']),
                                asset('storage/' . $head->bak->files),
                                route('req.dok', ['id' => md5($head->id), 'par'=>'attach']),
                                route('req.dok', ['id' => md5($head->id), 'par'=>'tax'])
                            ];
                }   
                else
                {
                    $uri = [
                                route('req.dok', ['id' => md5($head->id), 'par'=>'barp']),
                                route('req.dok', ['id' => md5($head->id), 'par'=>'bak']),
                                route('req.dok', ['id' => md5($head->id), 'par'=>'attach']),
                                route('req.dok', ['id' => md5($head->id), 'par'=>'tax'])
                            ];
                }                  
            @endphp

            const pdfUrls = @json($uri);
            
            merges(pdfUrls).then((res)=>{
                result = res;
                pdfjsLib.getDocument(res).promise.then(function(pdfDoc_) {
                pdfDoc = pdfDoc_;
                document.getElementById('page-count').textContent = pdfDoc.numPages;    
                renderPage(pageNum);    
                document.getElementById('loading').style.display = 'none';
                canvas.style.display = 'block';
                }, function(reason) {
                    document.getElementById('loading').textContent = "Failed to load PDF.";
                });  
            }).catch((e)=>{
                console.log(e);
            });
                    
            document.getElementById('prev').addEventListener('click', onPrevPage);
            document.getElementById('next').addEventListener('click', onNextPage);
            document.getElementById('download').addEventListener('click', downloadPdf);  
    </script>
</body>

</html>
