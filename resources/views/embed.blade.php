<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
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

    <div id="toolbar">
        <button id="prev">Previous</button>
        <span>Page: <span id="page-num"></span> / <span id="page-count"></span></span>
        <button id="next">Next</button>
    </div>

    <div id="pdf-viewer">
        <div id="canvas-container">
            <canvas id="pdf-canvas"></canvas>
        </div>
    </div>

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.3.122/pdf.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.3.122/pdf_viewer.min.js"></script> -->
    <script src="{{ asset('assets/pdf/pdf.min.js') }}"></script>
    <script>
        @isset($head)
        const url = "{{ route('req.doc',['id'=>md5($head->id)]) }}";
        @else
            const url = "{{ asset('storage/' . $link->files) }}";
        @endif
        let pdfDoc = null,
            pageNum = 1,
            pageRendering = false,
            pageNumPending = null,
            scale = 1.5,
            canvas = document.getElementById('pdf-canvas'),
            ctx = canvas.getContext('2d');

        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('assets/pdf/worker.min.js') }}";

        function renderPage(num) {
            pageRendering = true;
            pdfDoc.getPage(num).then(function(page) {
                const viewport = page.getViewport({
                    scale: scale
                });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport
                };
                const renderTask = page.render(renderContext);

                renderTask.promise.then(function() {
                    pageRendering = false;
                    if (pageNumPending !== null) {
                        renderPage(pageNumPending);
                        pageNumPending = null;
                    }
                });
            });

            document.getElementById('page-num').textContent = num;
        }

        function queueRenderPage(num) {
            if (pageRendering) {
                pageNumPending = num;
            } else {
                renderPage(num);
            }
        }

        function onPrevPage() {
            if (pageNum <= 1) {
                return;
            }
            pageNum--;
            queueRenderPage(pageNum);
        }

        function onNextPage() {
            if (pageNum >= pdfDoc.numPages) {
                return;
            }
            pageNum++;
            queueRenderPage(pageNum);
        }

        document.getElementById('prev').addEventListener('click', onPrevPage);
        document.getElementById('next').addEventListener('click', onNextPage);

        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            document.getElementById('page-count').textContent = pdfDoc.numPages;

            renderPage(pageNum);
        });

    </script>

</body>

</html>
