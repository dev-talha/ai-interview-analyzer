<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4 p-lg-5">
        <h1 class="h3 mb-3">My Profile</h1>
        <form method="post" action="/profile" class="row g-3">
          <input type="hidden" name="_token" value="<?= csrf_token() ?>">
          <div class="col-md-6"><label class="form-label">Full Name</label><input name="full_name" class="form-control" value="<?= e($user['full_name']) ?>"></div>
          <div class="col-md-6"><label class="form-label">Email</label><input class="form-control" value="<?= e($user['email']) ?>" disabled></div>
          <div class="col-md-6"><label class="form-label">Phone</label><input name="phone" class="form-control" value="<?= e($user['phone']) ?>"></div>
          <div class="col-md-6"><label class="form-label">Gender</label><select name="gender" class="form-select"><option value="">Select</option><option value="male" <?= $user['gender']==='male'?'selected':'' ?>>Male</option><option value="female" <?= $user['gender']==='female'?'selected':'' ?>>Female</option><option value="other" <?= $user['gender']==='other'?'selected':'' ?>>Other</option></select></div>
          <div class="col-12"><button class="btn btn-primary">Save Changes</button></div>
        </form>
      </div>
    </div>
  </div>
</div>
