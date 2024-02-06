<div class="list-group">
    <?php foreach ($snippets as $snippet): ?>
        <a href="./show?uniqueKey=<?php echo(htmlspecialchars($snippet['url'])) ?>" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?php echo htmlspecialchars($snippet['title']); ?></h5>
                <small><?php echo htmlspecialchars($snippet['created_at']); ?></small> <!-- 経過時間の表示方法は調整が必要 -->
            </div>
            <p class="mb-1"><?php echo htmlspecialchars($snippet['name']); ?></p> <!-- 言語の表示 -->
        </a>
    <?php endforeach; ?>
</div>

