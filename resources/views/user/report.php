<?php if (!$session): ?>
<p>Report not found.</p>
<?php else: ?>
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body p-4">
        <div class="text-secondary small mb-2">Session #<?= $session['id'] ?></div>
        <h1 class="h3 mb-1"><?= e($session['category_name']) ?></h1>
        <p class="text-secondary"><?= formatDate($session['created_at']) ?></p>
        <div class="score-ring mb-3"><span><?= e((string) $session['overall_score']) ?>%</span></div>
        <div class="badge text-bg-primary mb-3"><?= e($session['performance_level']) ?></div>
        <p><?= e($session['final_feedback']) ?></p>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body p-4">
        <h2 class="h5">Strengths</h2>
        <pre class="report-pre"><?= e($session['strengths']) ?></pre>
        <h2 class="h5">Weaknesses</h2>
        <pre class="report-pre"><?= e($session['weaknesses']) ?></pre>
        <h2 class="h5">Suggestions</h2>
        <pre class="report-pre mb-0"><?= e($session['suggestions']) ?></pre>
      </div>
    </div>
    <?php foreach ($answers as $index => $answer): ?>
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body p-4">
        <h3 class="h6 mb-3">Question <?= $index + 1 ?>: <?= e($answer['question_text']) ?></h3>
        <p><strong>Your answer:</strong> <?= e($answer['answer_text']) ?></p>
        <div class="row g-2 mb-3 small">
          <div class="col-md-3"><div class="stat-chip">Relevance: <?= e((string) $answer['relevance_score']) ?>/10</div></div>
          <div class="col-md-3"><div class="stat-chip">Clarity: <?= e((string) $answer['clarity_score']) ?>/10</div></div>
          <div class="col-md-3"><div class="stat-chip">Confidence: <?= e((string) $answer['confidence_score']) ?>/10</div></div>
          <div class="col-md-3"><div class="stat-chip">Professionalism: <?= e((string) $answer['professionalism_score']) ?>/10</div></div>
        </div>
        <p class="mb-1"><strong>Feedback:</strong> <?= e($answer['feedback']) ?></p>
        <p class="mb-0"><strong>Suggestion:</strong> <?= e($answer['suggestion']) ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
