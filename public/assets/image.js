let cropper, selectedImage, uri, parent;

$(document).ready(function () {
  let imagePreview = $(".image-preview");
  const imageInput = $("#imageInput");

  $(document).on("paste", function (event) {
    let clipboardData = event.originalEvent.clipboardData;
    if (clipboardData && clipboardData.items) {
      let items = clipboardData.items;
      for (let i = 0; i < items.length; i++) {
        let item = items[i];
        if (item.type.indexOf("image") !== -1) {
          let file = item.getAsFile();
          handleFiles([file]);
        }
      }
    }
  });

  function handleFiles(files) {
    for (let i = 0; i < files.length; i++) {
      let file = files[i];
      let reader = new FileReader();
      reader.onload = function (e) {
        imagePreview.append(
          '<img onclick="crop(this)" src="' + e.target.result + '">'
        );
      };
      reader.readAsDataURL(file);
    }
  }

  $("#cropModal").on("shown.bs.modal", function () {
    const cropImage = document.getElementById("cropImage");
    cropper = new Cropper(cropImage, {
      aspectRatio: 16 / 9,
      viewMode: 2,
    });
  });

  $("#cropModal").on("hidden.bs.modal", function () {
    cropper.destroy();
    cropper = null;
  });

  $("#cropSave").on("click", function () {
    const canvas = cropper.getCroppedCanvas();
    canvas.toBlob(function (blob) {
      const name = Date.now();
      const dataTransfer = new DataTransfer();
      const input = document.getElementById("imageInput");
      const files = input.files;
      const file = new File([blob], name + ".png", {
        type: "image/png",
      });

      dataTransfer.items.add(file);
      input.files = dataTransfer.files;
      console.log(file);
      const croppedImg = $(parent).attr("src", URL.createObjectURL(blob));
      imagePreview.append(croppedImg);
      $("#cropModal").modal("hide");
    });
  });
});

function crop(e) {
  parent = e;
  $("#cropImage").attr("src", e.src);
  $("#cropModal").modal("show");
}

function previewImage(event) {
  const files = event.target.files;
  const input = event.target;
  let imagePreview = $(".image-preview");

  if (files.length > 0) {
    Array.from(files).forEach((file) => {
      const reader = new FileReader();
      reader.onload = function (e) {
        imagePreview.append(
          '<img onclick="crop(this)" src="' + e.target.result + '">'
        );
        imagePreview.append(
          '<input type="hidden" name="images[]"  value="' +
            e.target.result +
            '">'
        );
      };
      reader.readAsDataURL(file);
    });
  }
}

function validateImageFile(file) {
  let res;

  const validTypes = ["image/jpeg", "image/jpg", "image/png"];
  const validExtensions = [".jpg", ".jpeg", ".png"];

  const fileExtension = file.name
    .substring(file.name.lastIndexOf("."))
    .toLowerCase();

  if (!validExtensions.includes(fileExtension)) {
    console.log("ext");
    res = false;
  }

  if (!validTypes.includes(file.type)) {
    console.log("type");
    res = false;
  }

  const fileReader = new FileReader();
  fileReader.onload = function (event) {
    const img = new Image();
    img.src = event.target.result;

    img.onload = function () {
      res = true;
      console.log("run");
    };

    img.onerror = function (e) {
      console.log(e);
      res = false;
    };
  };
  fileReader.readAsDataURL(file);

  return res;
}
