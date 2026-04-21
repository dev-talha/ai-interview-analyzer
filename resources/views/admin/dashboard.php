<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h3 mb-1">Admin Dashboard</h1>
    <p class="text-secondary mb-0">Manage users, content, reports, and Ollama integration.</p>
  </div>
</div>
<div class="row g-3 mb-4">
  <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body p-4"><div class="text-secondary small">Users</div><div class="display-6 fw-bold"><?= $userCount ?></div></div></div></div>
  <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body p-4"><div class="text-secondary small">Categories</div><div class="display-6 fw-bold"><?= $categoryCount ?></div></div></div></div>
  <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body p-4"><div class="text-secondary small">Questions</div><div class="display-6 fw-bold"><?= $questionCount ?></div></div></div></div>
  <div class="col-md-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body p-4"><div class="text-secondary small">Sessions</div><div class="display-6 fw-bold"><?= $sessionCount ?></div></div></div></div>
</div>
<div class="row g-4">
  <div class="col-lg-4">
    <div class="list-group shadow-sm rounded-4 overflow-hidden">
      <a class="list-group-item list-group-item-action" href="/admin/categories">Manage Categories</a>
      <a class="list-group-item list-group-item-action" href="/admin/questions">Manage Questions</a>
      <a class="list-group-item list-group-item-action" href="/admin/users">View Users</a>
      <a class="list-group-item list-group-item-action" href="/admin/reports">View Reports</a>
      <a class="list-group-item list-group-item-action" href="/admin/settings">AI Settings</a>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4">
        <h2 class="h5 mb-3">Recent Sessions</h2>
        <div class="table-responsive">
          <table class="table align-middle">
            <thead><tr><th>User</th><th>Category</th><th>Score</th><th>Status</th></tr></thead>
            <tbody>
            <?php foreach ($sessions as $session): ?>
              <tr>
                <td><?= e($session['full_name']) ?></td>
                <td><?= e($session['category_name']) ?></td>
                <td><?= e((string) $session['overall_score']) ?>%</td>
                <td><?= e($session['session_status']) ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
