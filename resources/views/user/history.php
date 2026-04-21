<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="h3 mb-1">Interview History</h1>
    <p class="text-secondary mb-0">Track your previous sessions and improvement over time.</p>
  </div>
</div>
<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead><tr><th>ID</th><th>Category</th><th>Score</th><th>Level</th><th>Date</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($sessions as $session): ?>
        <tr>
          <td>#<?= $session['id'] ?></td>
          <td><?= e($session['category_name']) ?></td>
          <td><?= e((string) $session['overall_score']) ?>%</td>
          <td><?= e($session['performance_level']) ?></td>
          <td><?= formatDate($session['created_at']) ?></td>
          <td><a href="/history/view?id=<?= $session['id'] ?>" class="btn btn-sm btn-outline-primary">View</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
