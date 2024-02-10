document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("post-btn-before")
    .addEventListener("click", function () {
      document.getElementById("post-btn-before").style.display = "none";
      document.getElementById("post-btn-after").style.display = "inline-block";
    });
});
