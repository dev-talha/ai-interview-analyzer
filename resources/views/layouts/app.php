<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(env('APP_NAME', 'AI Interview Analyzer')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body>
<?php require basePath('resources/views/partials/navbar.php'); ?>
<main class="py-4">
    <div class="container">
        <?php if ($msg = getFlash('success')): ?><div class="alert alert-success shadow-sm"><?= e($msg) ?></div><?php endif; ?>
        <?php if ($msg = getFlash('error')): ?><div class="alert alert-danger shadow-sm"><?= e($msg) ?></div><?php endif; ?>
        <?php require $view; ?>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>
