<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">商品列表</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/contact-us">聯絡我們</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/convert-excel">Excel轉換</a>
        </li>
      </ul>
    </div>
    <div>
        <input type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#notifications" value="通知">
    </div>
  </div>
</nav>
@include('layouts.modal')