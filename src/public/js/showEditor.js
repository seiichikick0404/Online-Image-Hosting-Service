require.config({ paths: { vs: "../../library/monaco-editor/min/vs" } });

require(["vs/editor/editor.main"], function () {
  const container = document.getElementById("container");

  const content = container.getAttribute("data-content");
  const language = container.getAttribute("data-language");
  var editor = monaco.editor.create(document.getElementById("container"), {
    value: content,
    language: language,
    readOnly: true,
  });

  document.querySelector("#copy-btn").addEventListener("click", function () {
    navigator.clipboard.writeText(editor.getValue()).then(
      () => {
        // コピー成功時の処理
        const message = document.getElementById("copy-message");
        message.style.display = "block";
        setTimeout(() => {
          message.style.display = "none";
        }, 2000); // 2秒後にメッセージを隠す
      },
      () => {
        // コピー失敗時の処理
        console.error("クリップボードへのコピーに失敗しました。");
      }
    );
  });
});
