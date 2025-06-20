function pilihNilaine(i) {
  var e = i.options[i.selectedIndex].value,
    t = i.options[i.selectedIndex].text;
  if ("Pilih" === t) return $(i).addClass("is-invalid"), !1;
  if (($(i).removeClass("is-invalid"), "if" == i.id)) {
    let a = indeks.fungsi.filter((i) => i.name === t);
    $("#view-ilo").html(a[0].ilo + " %"),
      $("#index-ilo").html(a[0].ilo),
      $("#ilo").val(a[0].ilo);
  }
  $("#view-" + i.id).html(e),
    $("#index-" + i.id).val(e),
    $("#name-" + i.id).val(t),
    it(),
    sumWide();
}
function pilihUraian(i) {
  let e, t, a;
  var l = $(i).attr("data-id");
  i.options[i.selectedIndex].value;
  var n = i.options[i.selectedIndex].text;
  if ("Pilih" === n) $(i).addClass("is-invalid"), (e = 0), (t = 0);
  else {
    $(i).removeClass("is-invalid");
    let o = prasarana.filter((i) => i.nama === n);
    (t = o[0].hargaSatuan),
      "m1" === o[0].satuan
        ? (e = "m")
        : "m2" === o[0].satuan
        ? (e = "m<sup>2</sup>")
        : "m3" === o[0].satuan
        ? (e = "m<sup>3</sup>")
        : ("Rp" === o[0].satuan &&
            (t = (t = o[0].hargaSatuan / 100).toFixed(2)),
          (e = o[0].satuan)),
      (a = o[0].satuan);
  }
  $("#view-sat" + l).html(e),
    $("#sat" + l).val(a),
    $("#price" + l).val(t),
    $("#view-price" + l).html(t.toLocaleString("id-ID"));
}
function it() {
  let i = parseFloat($("#if").val()),
    e = parseFloat($("#ik").val()),
    t = parseFloat($("#ip").val()),
    a = parseFloat($("#il").val()),
    l = parseFloat($("#fm").val()),
    n = 0;
  (n = isNaN((n = Math.ceil(1e3 * (n = i * (e + t + a) * l)) / 1e3)) ? 0 : n),
    $("#it").val(n.toFixed(3)),
    $("#view-it").html(n.toFixed(3));
}
function sumWide() {
  let i = parseFloat($("#it").val()),
    e = parseFloat($("#ilo").val()),
    t = parseFloat($("#ibg").val()),
    a = parseFloat(settings.shst),
    l = 0,
    n = 0;
  document.querySelectorAll(".float-input").forEach(function (i) {
    l += parseFloat(i.value);
  }),
    $("#llt").val(l),
    (n = isNaN((n = Math.ceil((n = (n = i * t * e * a * l).toFixed(0)) / 100)))
      ? 0
      : n),
    $("#view-retri").html(n.toLocaleString("id-ID")),
    $("#retri").val(n);
}
function praSum(i) {
  let e = 0;
  var t = $(i).attr("data-id");
  let a = $("#price" + t).val();
  (e = i.value * a),
    $("#sum" + t).val(e),
    $("#view-sum" + t).html(e.toLocaleString("id-ID")),
    sumRetri();
}
function sumRetri() {
  let i = 0,
    e = 0;
  document.querySelectorAll(".sum").forEach(function (e) {
    i += parseFloat(e.value);
  }),
    console.log(i),
    $("#sumPra").val(i),
    $("#view-sumPra").html(i.toLocaleString("id-ID"));
  let t = $("#retri").val();
  (e = parseFloat(t) + parseFloat(i)),
    $("#totRetri").val(e),
    $("#view-totRetri").html(e.toLocaleString("id-ID"));
}
$(document).ready(function () {
  $.each(indeks.fungsi, function (i, e) {
    $("#if").append($("<option>", { value: e.index, text: e.name }));
  }),
    $.each(indeks.kompleksitas, function (i, e) {
      $("#ik").append(
        $("<option>", { value: e.index * settings.kompleksitas, text: e.name })
      );
    }),
    $.each(indeks.permanensi, function (i, e) {
      $("#ip").append(
        $("<option>", { value: e.index * settings.permanensi, text: e.name })
      );
    }),
    $.each(indeks.ketinggian, function (i, e) {
      $("#il").append(
        $("<option>", { value: e.index * settings.ketinggian, text: e.name })
      );
    }),
    $.each(indeks.kepemilikan, function (i, e) {
      $("#fm").append($("<option>", { value: e.index, text: e.name }));
    }),
    $.each(indeks.kegiatan, function (i, e) {
      $("#ibg").append($("<option>", { value: e.index, text: e.name }));
    }),
    $.each(prasarana, function (i, e) {
      $(".ur").append($("<option>", { value: e.nama, text: e.nama }));
    });
  let i = settings.shst.toLocaleString("id-ID");
  $("#shst").val(i);
});
