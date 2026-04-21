<div class="row justify-content-center">
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4 p-lg-5">
        <h1 class="h3 mb-3">Welcome back</h1>
        <form method="post" action="/login">
          <input type="hidden" name="_token" value="<?= csrf_token() ?>">
          <div class="mb-3"><label class="form-label">Email</label><input name="email" type="email" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">Password</label><input name="password" type="password" class="form-control" required></div>
          <button class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
