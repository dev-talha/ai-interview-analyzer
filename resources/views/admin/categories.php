<div class="row g-4">
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm"><div class="card-body p-4">
      <h1 class="h5 mb-3">Add Category</h1>
      <form method="post" action="/admin/categories/store">
        <input type="hidden" name="_token" value="<?= csrf_token() ?>">
        <div class="mb-3"><label class="form-label">Name</label><input name="category_name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"></textarea></div>
        <button class="btn btn-primary">Save Category</button>
      </form>
    </div></div>
  </div>
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm"><div class="card-body p-4">
      <h2 class="h5 mb-3">All Categories</h2>
      <?php foreach ($categories as $category): ?>
      <div class="border rounded-4 p-3 mb-3">
        <form method="post" action="/admin/categories/update" class="row g-2 align-items-end">
          <input type="hidden" name="_token" value="<?= csrf_token() ?>">
          <input type="hidden" name="id" value="<?= $category['id'] ?>">
          <div class="col-md-4"><label class="form-label">Name</label><input name="category_name" class="form-control" value="<?= e($category['category_name']) ?>"></div>
          <div class="col-md-5"><label class="form-label">Description</label><input name="description" class="form-control" value="<?= e($category['description']) ?>"></div>
          <div class="col-md-3"><button class="btn btn-outline-primary w-100">Update</button></div>
        </form>
        <form method="post" action="/admin/categories/delete" class="mt-2">
          <input type="hidden" name="_token" value="<?= csrf_token() ?>">
          <input type="hidden" name="id" value="<?= $category['id'] ?>">
          <button class="btn btn-outline-danger btn-sm">Delete</button>
        </form>
      </div>
      <?php endforeach; ?>
    </div></div>
  </div>
</div>
