<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4 p-lg-5">
        <h1 class="h3 mb-3">Create your account</h1>
        <form method="post" action="/register" class="row g-3">
          <input type="hidden" name="_token" value="<?= csrf_token() ?>">
          <div class="col-md-6"><label class="form-label">Full name</label><input name="full_name" class="form-control" value="<?= e(old('full_name')) ?>" required></div>
          <div class="col-md-6"><label class="form-label">Email</label><input name="email" type="email" class="form-control" value="<?= e(old('email')) ?>" required></div>
          <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control" value="<?= e(old('phone')) ?>"></div>
          <div class="col-md-6"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="">Select</option><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
          <div class="col-12"><label class="form-label">Password</label><input name="password" type="password" class="form-control" required></div>
          <div class="col-12"><button class="btn btn-primary">Register</button></div>
        </form>
      </div>
    </div>
  </div>
</div>
