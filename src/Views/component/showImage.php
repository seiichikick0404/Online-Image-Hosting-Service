<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 img-detail">
            <h1 class="text-center"><?php echo(htmlspecialchars($imageData['title'])) ?></h1>
            <img src="../../public/images/522f1fa3-100f-4f90-8d81-d8b59ec39c2c.jpeg" alt="Image Title" class="img-fluid mt-3">
            <p class="text-muted text-center mt-3">
                <p class="icon-text text-muted"><i class="fas fa-eye"></i> <?php echo(htmlspecialchars($imageData['view_count'])) ?></p>
                <p class="icon-text text-muted"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                    </svg></i> <?php echo(htmlspecialchars($imageData['created_at'])) ?>
                </p>
            </p>
        </div>
    </div>
</div>