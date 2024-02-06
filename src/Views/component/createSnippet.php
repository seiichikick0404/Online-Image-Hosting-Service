<div class="container mt-4">
    <h2>Text Snippet Sharing Service</h2>
    <div class="row">
        <!-- Monaco Editor Container -->
        <div class="col-md-8">
            <div id="container" style="height: 600px; border: 1px solid grey;"></div>
        </div>

        <!-- Form Elements Container -->
        <div class="col-md-4">
            <!-- Form start -->
            <form action="save" method="post" id="snippetForm">
                <!-- タイトル入力フォーム -->
                <div class="mb-3">
                    <label for="snippetTitle" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" id="snippetTitle" placeholder="Enter title">
                    <?php if (isset($errors['title'])): ?>
                        <div class="text-danger"><?php echo htmlspecialchars($errors['title']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- 有効期限選択フォーム -->
                <div class="mb-3">
                    <label for="expiration" class="form-label">Expiration</label>
                    <select name="expiration" class="form-select" id="expiration">
                        <option value="10min">10min</option>
                        <option value="1hour">1h</option>
                        <option value="1day">1day</option>
                        <option value="forever">forever</option>
                    </select>
                    <?php if (isset($errors['expiration'])): ?>
                        <div class="text-danger"><?php echo htmlspecialchars($errors['expiration']); ?></div>
                    <?php endif; ?>
                </div>

                <!-- 構文選択フォーム -->
                <div class="mb-3">
                    <label for="syntax" class="form-label">Syntax</label>
                    <select name="syntax" class="form-select" id="syntax">
                        <?php foreach ($syntaxes as $syntax): ?>
                            <option value="<?php echo htmlspecialchars($syntax['id']); ?>" <?php echo ($syntax['name'] === 'javascript') ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($syntax['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['syntax'])): ?>
                        <div class="text-danger"><?php echo htmlspecialchars($errors['syntax']); ?></div>
                    <?php endif; ?>
                </div>

                <input type="hidden" id="content" name="content">

                <!-- アップロードボタン -->
                <div class="mb-3">
                    <button type="submit" class="btn btn-dark">Upload</button>
                </div>
            </form>
            <!-- Form end -->
        </div>
    </div>
</div>

<!-- Monaco Editor Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.20.0/min/vs/loader.min.js"></script>
<script src="../../public/js/editor.js"></script>
