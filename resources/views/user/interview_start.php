<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4 p-lg-5">
        <h1 class="h3 mb-3">Start a new interview</h1>
        <form method="post" action="/interview/start">
          <input type="hidden" name="_token" value="<?= csrf_token() ?>">
          <div class="mb-3">
            <label class="form-label">Interview Category</label>
            <select name="category_id" class="form-select" required>
              <option value="">Select a category</option>
              <?php foreach ($categories as $category): ?>
              <option value="<?= $category['id'] ?>" <?= (isset($_GET['category_id']) && $_GET['category_id'] == $category['id']) ? 'selected' : '' ?>><?= e($category['category_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button class="btn btn-primary">Load Questions</button>
        </form>
      </div>
    </div>
  </div>
</div>
