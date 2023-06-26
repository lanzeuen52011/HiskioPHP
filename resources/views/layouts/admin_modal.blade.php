<div class="modal fade" id="upload-image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">上傳圖片</h1>
        <!-- 以下button為關閉的按鈕 -->
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/admin/products/upload-image" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- enctype="multipart/form-data"，才有辦法把圖片傳送到後端 -->
            <input type="hidden" id="product_id" name="product_id">
            <input type="file" id="product_image" name="product_image">
            <input type="submit" value="送出">
        </form>
      </div>
    </div>
  </div>
</div>