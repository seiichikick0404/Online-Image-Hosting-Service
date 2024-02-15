document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("post-btn-before")
    .addEventListener("click", function () {
      // UIをローディング状態に切り替え
      document.getElementById("post-btn-before").style.display = "none";
      document.getElementById("post-btn-after").style.display = "inline-block";

      // フォームデータの準備
      const formData = new FormData();
      const title = document.querySelector("#imageTitle").value;
      const image = document.querySelector("#formFile").files[0];

      formData.append("title", title);
      formData.append("image", image);

      // データをPOST
      postData("api/json/save", formData)
        .then((data) => {
          console.log(data);
          document.getElementById("post-btn-after").style.display = "none";
          document.getElementById("post-btn-before").style.display =
            "inline-block";
        })
        .catch((error) => {
          console.error("Error:", error);
          document.getElementById("post-btn-after").style.display = "none";
          document.getElementById("post-btn-before").style.display =
            "inline-block";
        });
    });
});

async function postData(url = "", formData) {
  const response = await fetch(url, {
    method: "POST",
    body: formData,
  });

  if (!response.ok) {
    throw new Error("Network response was not ok");
  }

  return response.json();
}
