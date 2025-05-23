async function pile(a) {
  let e = [],
    { PDFDocument: t } = PDFLib,
    i = await t.create();
  for (let r of a) {
    let s = await fetch(r).then((a) => a.arrayBuffer()),
      c = await t.load(s),
      f = await i.copyPages(c, c.getPageIndices());
    f.forEach((a) => i.addPage(a));
  }
  let l = await i.saveAsBase64({ dataUri: !0 });
  return e.push(l), e;
}
function genPDF(e, t) {
  let n = null,
    l = 1,
    a = !1,
    i = null;
  document.getElementById("prev" + t).addEventListener("click", function e() {
    !(l <= 1) && y(--l, o, t, d);
  }),
    document.getElementById("next" + t).addEventListener("click", function e() {
      !(l >= n.numPages) && y(++l, o, t, d);
    }),
    (document.getElementById("loading" + t).style.display = "block");
  var o = document.getElementById("pdf-canvas" + t),
    d = o.getContext("2d");
  async function g(e, t, l, o) {
    try {
      a = !0;
      let d = await n.getPage(e),
        y = d.getViewport({ scale: 1.5 });
      (t.height = y.height), (t.width = y.width);
      let c = d.render({ canvasContext: o, viewport: y });
      await c.promise,
        (a = !1),
        null !== i && (g(i, t, l, o), (i = null)),
        (document.getElementById("page-num" + l).textContent = e);
    } catch (r) {
      console.error(`Error rendering page ${e} for index ${l}:`, r);
    }
  }
  function y(e, t, n, l) {
    a ? (i = e) : g(e, t, n, l);
  }
  pdfjsLib.getDocument(e).promise.then(
    function (e) {
      (n = e),
        (document.getElementById("page-count" + t).textContent = n.numPages),
        g(l, o, t, d),
        (document.getElementById("loading" + t).style.display = "none"),
        n.numPages < 2 &&
          ((document.getElementById("prev" + t).style.display = "none"),
          (document.getElementById("next" + t).style.display = "none")),
        (o.style.display = "block");
    },
    function (e) {
      console.log(e),
        (document.getElementById("loading" + t).textContent =
          "Failed to load Data.");
    }
  );
}
