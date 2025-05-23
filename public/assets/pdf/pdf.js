function genPDF(val, i) {

    let pdfDoc = null,
        pageNum = 1,
        pageRendering = false,
        pageNumPending = null,
        scale = 1.5;

    document.getElementById('prev' + i).addEventListener('click', onPrevPage);
    document.getElementById('next' + i).addEventListener('click', onNextPage);
    document.getElementById('loading' + i).style.display = 'block';

    var canvas = document.getElementById('pdf-canvas' + i);
    var ctx = canvas.getContext('2d');


    pdfjsLib.getDocument(val).promise.then(function(pdfDoc_) {
        pdfDoc = pdfDoc_;
        document.getElementById('page-count' + i).textContent = pdfDoc.numPages;

        renderPage(pageNum, canvas, i, ctx);

        document.getElementById('loading' + i).style.display = 'none';
        if (pdfDoc.numPages < 2) {
            document.getElementById('prev' + i).style.display = 'none';
            document.getElementById('next' + i).style.display = 'none';

        }
        canvas.style.display = 'block';
    }, function(reason) {
        console.log(reason);
        document.getElementById('loading' + i).textContent = "Failed to load Data.";
    });

    async function renderPage(num, canvas, i, ctx) {

        try {
            pageRendering = true;
            const page = await pdfDoc.getPage(num); 

            const viewport = page.getViewport({ scale: scale });

            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            const renderTask = page.render(renderContext);
            await renderTask.promise;

            pageRendering = false;
            if (pageNumPending !== null) {
                renderPage(pageNumPending, canvas, i, ctx);
                pageNumPending = null;
            }

            document.getElementById('page-num' + i).textContent = num;
        } catch (error) {
            console.error(`Error rendering page ${num} for index ${i}:`, error);
        }
    }

    function queueRenderPage(num, canvas, i, ctx) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num, canvas, i, ctx);
        }
    }

    function onPrevPage() {
        if (pageNum <= 1) {
            return;
        }
        pageNum--;
        queueRenderPage(pageNum, canvas, i, ctx);
    }

    function onNextPage() {
        if (pageNum >= pdfDoc.numPages) {
            return;
        }
        pageNum++;
        queueRenderPage(pageNum, canvas, i, ctx);
    }
}