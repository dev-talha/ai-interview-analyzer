<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="/">AI Interview Analyzer</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <?php if (auth()): ?>
          <?php if (isAdmin()): ?>
            <li class="nav-item"><a class="nav-link" href="/admin">Admin</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="/history">History</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="/profile">Profile</a></li>
          <li class="nav-item">
            <form action="/logout" method="post" class="d-inline">
              <input type="hidden" name="_token" value="<?= csrf_token() ?>">
              <button class="btn btn-dark btn-sm">Logout</button>
            </form>
          </li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
          <li class="nav-item"><a class="btn btn-primary btn-sm" href="/register">Get Started</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
