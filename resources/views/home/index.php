<section class="hero-card p-4 p-lg-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <span class="badge text-bg-primary mb-3">AI + Ollama</span>
            <h1 class="display-5 fw-bold mb-3">Practice smarter interviews with instant AI feedback.</h1>
            <p class="lead text-secondary mb-4">A modern, fully responsive mock interview platform built with raw PHP,
                Bootstrap, MySQL, and admin-managed Ollama configuration.</p>
            <div class="d-flex flex-wrap gap-2">
                <?php if (auth()): ?>
                    <a href="<?= isAdmin() ? '/admin' : '/dashboard' ?>" class="btn btn-primary btn-lg">Go to Dashboard</a>
                <?php else: ?>
                    <a href="/register" class="btn btn-primary btn-lg">Create Account</a>
                    <a href="/login" class="btn btn-outline-dark btn-lg">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="glass-panel p-4">
                <h5 class="fw-semibold">What you get</h5>
                <ul class="mb-0 text-secondary">
                    <li>Predefined interview categories and questions</li>
                    <li>Per-answer AI scoring and suggestions</li>
                    <li>Session history and performance tracking</li>
                    <li>Dedicated admin panel with Ollama settings</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">Featured categories</h2>
        <a href="<?= auth() ? (isAdmin() ? '/admin' : '/dashboard') : '/register' ?>" class="small-link">Start
            practicing</a>
    </div>
    <div class="row g-3">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="h5"><?= e($category['category_name']) ?></h3>
                        <p class="text-secondary mb-0"><?= e($category['description']) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>