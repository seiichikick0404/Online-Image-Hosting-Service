require.config({ paths: { vs: "../../library/monaco-editor/min/vs" } });

require(["vs/editor/editor.main"], function () {
  const syntaxes = {
    1: "plaintext",
    2: "html",
    3: "css",
    4: "javascript",
    5: "typescript",
    6: "python",
    7: "java",
    8: "php",
    9: "go",
    10: "ruby",
    11: "swift",
    12: "kotlin",
    13: "json",
    14: "yaml",
    15: "xml",
    16: "markdown",
    17: "sql",
    18: "shell",
  };

  // 初期言語設定
  var initialLanguage = "javascript";

  // エディタの初期化
  var editor = monaco.editor.create(document.getElementById("container"), {
    value: ["function x() {", '\tconsole.log("Hello world!");', "}"].join("\n"),
    language: initialLanguage,
  });

  // 構文選択フォームの要素を取得
  var syntaxSelect = document.getElementById("syntax");

  // 構文選択の変更を監視
  syntaxSelect.addEventListener("change", function () {
    const langId = Number(syntaxSelect.value);
    monaco.editor.setModelLanguage(editor.getModel(), syntaxes[langId]);
  });

  // フォームの送信イベントを監視
  document
    .getElementById("snippetForm")
    .addEventListener("submit", function () {
      // エディタの現在の内容を取得
      var editorContent = editor.getValue();
      // 隠れた入力フィールドにエディタの内容を設定
      document.getElementById("content").value = editorContent;
    });
});
