<div class="container">
    <form>
        <div class="mb-3">
            <label for="imageTitle" class="form-label">タイトル</label>
            <input type="text" class="form-control" id="imageTitle" placeholder="Enter image title">
        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">ファイルを選択(png, jpg, gif)</label>
            <input class="form-control" type="file" id="formFile">
        </div>
        <div class="mb-3">
            <label class="form-label">プレビュー</label>
            <div id="preview" class="border p-2">No image selected</div>
        </div>
        <button type="submit" class="btn btn-dark">POST</button>
    </form>
</div>



