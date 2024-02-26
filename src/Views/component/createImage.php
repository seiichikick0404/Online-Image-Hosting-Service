<div class="container">
    <form>
        <div class="mb-3">
            <label for="imageTitle" class="form-label">タイトル</label>
            <input type="text" class="form-control" id="imageTitle" placeholder="Enter image title">
            <div id="titleError" class="text-danger"></div> 
        </div>
        <div class="mb-3">
            <label for="formFile" class="form-label">ファイルを選択(png, jpg, gif)</label>
            <input class="form-control" type="file" id="formFile" accept="image/png, image/jpeg, image/gif">
            <div id="imageError" class="text-danger"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">プレビュー</label>
            <img id="previewImage" src="#" alt="No image selected" style="display: none; max-width: 100%; height: auto;"/>
        </div>
        <button id="post-btn-before" type="button" class="btn btn-dark">POST</button>
        <button id="post-btn-after" class="btn btn-dark" type="button" disabled>
            <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
            Posting...
        </button>

        <!-- 通信完了後の情報を表示する領域 -->
        <div id="successMessageContainer" class="mt-3 d-none">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">登録完了</h4>
                <p id="successText">画像が正常に登録されました。</p>
                <hr>
                <p class="mb-0" id="viewUrl">閲覧用URL: <a href="#" target="_blank">こちら</a></p>
                <p class="mb-0" id="deleteUrl">削除用URL: <a href="#" target="_blank">こちら</a></p>
            </div>
        </div>

</div>
    </form>
</div>


<script src="../../public/js/preview.js"></script>
<script src="../../public/js/post.js"></script>


