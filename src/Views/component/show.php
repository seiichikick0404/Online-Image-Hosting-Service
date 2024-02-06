<?php
$syntaxes = [
    1 => "Plaintext",
    2 => "HTML",
    3 => "CSS",
    4 => "JavaScript",
    5 => "TypeScript",
    6 => "Python",
    7 => "Java",
    8 => "PHP",
    9 => "Go",
    10 => "Ruby",
    11 => "Swift",
    12 => "Kotlin",
    13 => "JSON",
    14 => "YAML",
    15 => "XML",
    16 => "Markdown",
    17 => "SQL",
    18 => "Bash",
]
?>


<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <?php if ($data['status']): ?>
                <!-- タイトル -->
                <h2><i class="fas fa-code"></i> <?php echo htmlspecialchars($data['title']) ?></h2>

                <!-- 有効期限と作成日 -->
                <p>
                    <i class="far fa-clock"></i>
                    Expiration:
                    <?php
                    echo $data['expiration'] ? htmlspecialchars($data['expiration']) : 'forever';
                    ?>
                </p>

                <p><i class="far fa-calendar-alt"></i> Created Date: <?php echo htmlspecialchars($data['created_at']) ?></p>

                <!-- シンタックス -->
                <p><i class="fas fa-cogs"></i> Syntax: <?php echo htmlspecialchars($syntaxes[$data['syntax_id']]) ?></p>

                <!-- コピーボタンとコピーメッセージ -->
                <div class="text-end mb-3">
                    <!-- コピーボタン -->
                    <button class="btn btn-dark" id="copy-btn">
                        <i class="fas fa-clipboard"></i>
                        <i class="fas fa-copy">Copy code</i>
                    </button>
                    <!-- コピーメッセージ -->
                    <span id="copy-message" class="copy-message-style" style="display: none;">Copied!</span>
                </div>
            <?php else: ?>
                <!-- スニペットが無効（期限切れ）の場合のメッセージ表示 -->
                <div class="container mt-4">
                    <div class="row justify-content-center">
                        <div class="col-md-8 text-center">
                            <h2>Expired Snippet</h2>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Monaco Editor Container -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div id="container" style="height: 600px; border: 1px solid grey;"
                data-content="<?php echo htmlspecialchars($data['content']); ?>"
                data-language="<?php echo htmlspecialchars(strtolower($syntaxes[$data['syntax_id']])); ?>">
            </div>
        </div>
    </div>
</div>

<!-- Monaco Editor Scripts -->
<?php if ($data['status']): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs/loader.min.js"></script>
    <script src="../../public/js/showEditor.js"></script>
<?php endif; ?>
