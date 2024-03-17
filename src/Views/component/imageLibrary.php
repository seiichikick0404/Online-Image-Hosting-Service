<!-- Gallery -->
<div class="container mt-5">
    <div class="row">

        <?php if (empty($images)): ?>
            <p class="text-center">現在画像がありません。</p>
        <?php else: ?>
            <?php foreach ($images as $image): ?>
                <!-- Gallery Item -->
                <div class="col-md-4 gallery-item">
                    <a href="<?php echo htmlspecialchars($image['image_url']); ?>">
                        <img src="../../public/storage/<?php echo htmlspecialchars($image['image_path']); ?>" class="gallery-image" alt="<?php echo htmlspecialchars($image['title']); ?>">
                        <h5 class="mt-2"><?php echo htmlspecialchars($image['title']); ?></h5>
                        <p class="text-muted"><i class="fas fa-eye"></i>: <?php echo htmlspecialchars($image['view_count']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- More Gallery Items -->
        <!-- ... -->
    </div>
</div>

