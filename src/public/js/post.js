document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("post-btn-before")
    .addEventListener("click", function () {
      document.getElementById("post-btn-before").style.display = "none";
      document.getElementById("post-btn-after").style.display = "inline-block";

      // 2秒間（2000ミリ秒）待機
      setTimeout(function () {
        const formData = new FormData();
        const title = document.querySelector("#imageTitle").value;
        const image = document.querySelector("#formFile").files[0];

        formData.append("title", title);
        formData.append("image", image);

        // 入力値を送信
        postData("api/json/save", formData)
          .then((data) => {
            console.log(data);
            displayErrors(data.response.errors);
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
      }, 2000);
    });
});

async function displayErrors(errors) {
  // 既存のエラーメッセージをクリア
  document.getElementById("titleError").innerText = "";
  document.getElementById("imageError").innerText = "";

  // タイトルのエラーメッセージがある場合
  if (errors.title && errors.title.length > 0) {
    document.getElementById("titleError").innerText = errors.title.join(", ");
  }

  // 画像のエラーメッセージがある場合
  if (errors.image && errors.image.length > 0) {
    document.getElementById("imageError").innerText = errors.image.join(", ");
  }
}

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
