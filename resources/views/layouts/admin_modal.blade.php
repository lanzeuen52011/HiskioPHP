<div class="modal fade" id="upload-image" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">上傳圖片</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/admin/products/upload-image" method="POST" enctype="multipart/form-data">
        @csrf
            <input type="hidden" id="product_id" name="product_id">
            <input type="file" id="product_image" name="product_image">
            <input type="submit" value="送出">
        </form>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="import" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">匯入 Excel</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/admin/products/excel/import" method="POST" enctype="multipart/form-data">
        @csrf
            <input type="file" id="excel" name="excel">
            <input type="submit" value="送出">
        </form>
      </div>
    </div>
  </div>
</div>