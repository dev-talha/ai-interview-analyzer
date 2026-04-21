<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <h1 class="h4 mb-0">Interview Reports</h1>
    <form method="get" action="/admin/reports" class="d-flex w-100" style="max-width: 300px;">
        <input type="text" name="search" class="form-control me-2" placeholder="Search reports..." value="<?= e($search ?? '') ?>">
        <button type="submit" class="btn btn-dark">Search</button>
    </form>
</div>

<div class="card border-0 shadow-sm mb-4">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table align-middle mb-0 text-nowrap">
        <thead class="table-light"><tr><th class="ps-4">User</th><th>Category</th><th>Score</th><th>Level</th><th>Date</th><th class="text-end pe-4">Actions</th></tr></thead>
        <tbody>
          <?php if(!$sessions): ?>
             <tr><td colspan="6" class="text-center py-4 text-muted">No reports found.</td></tr>
          <?php else: foreach ($sessions as $session): ?>
          <tr>
            <td class="ps-4"><?= e($session['full_name']) ?></td>
            <td><?= e($session['category_name']) ?></td>
            <td><?= e((string) $session['overall_score']) ?>%</td>
            <td><span class="badge text-bg-light border"><?= e($session['performance_level'] ?: 'Started') ?></span></td>
            <td><?= formatDate($session['created_at']) ?></td>
            <td class="text-end pe-4">
              <div class="d-flex justify-content-end gap-1">
                <a href="/admin/reports/view?id=<?= $session['id'] ?>" class="btn btn-sm btn-outline-primary">Open</a>
                <form method="post" action="/admin/reports/delete" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this report? This action cannot be undone.');">
                  <input type="hidden" name="_token" value="<?= csrf_token() ?>">
                  <input type="hidden" name="id" value="<?= $session['id'] ?>">
                  <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php if (isset($last_page) && $last_page > 1): ?>
<nav>
  <ul class="pagination justify-content-center">
    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($search ?? '') ?>">Previous</a>
    </li>
    <?php for($i=1; $i<=$last_page; $i++): ?>
    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
      <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>"><?= $i ?></a>
    </li>
    <?php endfor; ?>
    <li class="page-item <?= $page >= $last_page ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($search ?? '') ?>">Next</a>
    </li>
  </ul>
</nav>
<?php endif; ?>
