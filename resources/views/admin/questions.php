<div class="row g-4">
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm"><div class="card-body p-4">
      <h1 class="h5 mb-3">Add Question</h1>
      <form method="post" action="/admin/questions/store">
        <input type="hidden" name="_token" value="<?= csrf_token() ?>">
        <div class="mb-3"><label class="form-label">Category</label><select name="category_id" class="form-select"><?php foreach ($categories as $category): ?><option value="<?= $category['id'] ?>"><?= e($category['category_name']) ?></option><?php endforeach; ?></select></div>
        <div class="mb-3"><label class="form-label">Question</label><textarea name="question_text" class="form-control" rows="4"></textarea></div>
        <div class="row g-2"><div class="col"><label class="form-label">Difficulty</label><select name="difficulty" class="form-select"><option>easy</option><option selected>medium</option><option>hard</option></select></div><div class="col"><label class="form-label">Status</label><select name="status" class="form-select"><option selected>active</option><option>inactive</option></select></div></div>
        <button class="btn btn-primary mt-3">Save Question</button>
      </form>
    </div></div>
  </div>
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm"><div class="card-body p-4">
      <h2 class="h5 mb-3">All Questions</h2>
      <?php foreach ($questions as $question): ?>
      <div class="border rounded-4 p-3 mb-3">
        <form method="post" action="/admin/questions/update" class="row g-2">
          <input type="hidden" name="_token" value="<?= csrf_token() ?>">
          <input type="hidden" name="id" value="<?= $question['id'] ?>">
          <div class="col-md-4"><label class="form-label">Category</label><select name="category_id" class="form-select"><?php foreach ($categories as $category): ?><option value="<?= $category['id'] ?>" <?= $question['category_id']==$category['id']?'selected':'' ?>><?= e($category['category_name']) ?></option><?php endforeach; ?></select></div>
          <div class="col-md-4"><label class="form-label">Difficulty</label><select name="difficulty" class="form-select"><option <?= $question['difficulty']==='easy'?'selected':'' ?>>easy</option><option <?= $question['difficulty']==='medium'?'selected':'' ?>>medium</option><option <?= $question['difficulty']==='hard'?'selected':'' ?>>hard</option></select></div>
          <div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option <?= $question['status']==='active'?'selected':'' ?>>active</option><option <?= $question['status']==='inactive'?'selected':'' ?>>inactive</option></select></div>
          <div class="col-12"><label class="form-label">Question</label><textarea name="question_text" class="form-control" rows="3"><?= e($question['question_text']) ?></textarea></div>
          <div class="col-12"><button class="btn btn-outline-primary">Update</button></div>
        </form>
        <form method="post" action="/admin/questions/delete" class="mt-2">
          <input type="hidden" name="_token" value="<?= csrf_token() ?>">
          <input type="hidden" name="id" value="<?= $question['id'] ?>">
          <button class="btn btn-outline-danger btn-sm">Delete</button>
        </form>
      </div>
      <?php endforeach; ?>
    </div></div>
  </div>
</div>
