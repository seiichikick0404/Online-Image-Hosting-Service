// JavaScript for image preview functionality
document
  .getElementById("formFile")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById("preview");
    const previewImage = document.getElementById("previewImage");

    if (file) {
      const reader = new FileReader();

      reader.onload = function (e) {
        previewImage.src = e.target.result;
        previewImage.style.display = "block";
        previewImage.alt = file.name;
      };

      reader.readAsDataURL(file);
    } else {
      previewImage.src = "#";
      previewImage.style.display = "none";
      previewImage.alt = "No image selected";
    }
  });
