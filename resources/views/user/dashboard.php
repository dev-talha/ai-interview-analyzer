<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <h1 class="h3 mb-1">Hello, <?= e(auth()['name']) ?></h1>
    <p class="text-secondary mb-0">Choose a category and start your next mock interview.</p>
  </div>
  <a href="/interview/start" class="btn btn-primary">Start Interview</a>
</div>
<div class="row g-4">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body p-4">
        <h2 class="h5 mb-3">Interview Categories</h2>
        <div class="row g-3">
          <?php foreach ($categories as $category): ?>
          <div class="col-md-6">
            <div class="border rounded-4 p-3 h-100">
              <h3 class="h6"><?= e($category['category_name']) ?></h3>
              <p class="small text-secondary mb-3"><?= e($category['description']) ?></p>
              <a href="/interview/start?category_id=<?= $category['id'] ?>" class="btn btn-outline-primary btn-sm">Practice now</a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body p-4">
        <h2 class="h5 mb-3">Recent Sessions</h2>
        <?php if (!$sessions): ?>
          <p class="text-secondary mb-0">No interviews completed yet.</p>
        <?php else: foreach ($sessions as $session): ?>
          <a href="/history/view?id=<?= $session['id'] ?>" class="text-decoration-none text-reset d-block border rounded-4 p-3 mb-3 hover-card">
            <div class="d-flex justify-content-between">
              <strong><?= e($session['category_name']) ?></strong>
              <span class="badge text-bg-light"><?= e($session['performance_level'] ?: 'Started') ?></span>
            </div>
            <div class="small text-secondary mt-2"><?= formatDate($session['created_at']) ?></div>
          </a>
        <?php endforeach; endif; ?>
      </div>
    </div>
  </div>
</div>
