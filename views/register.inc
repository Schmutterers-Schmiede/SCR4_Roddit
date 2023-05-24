<?php $render('partial/header', $data); ?>

<h1>Create Account</h1>
<p>Become a part of the magical röddit cömmünity!</p>

<?php $beginForm('User', 'Create', method: 'post'); ?>
<div class="mb-3">
    <label for="userName" class="form-label">User name</label>
    <input class="form-control" id="userName" name="un" value="<?php $htmlOut($data['userName']); ?>">
</div>
<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password1" name="pwd1">
</div>
<div class="mb-3">
  <label for="password" class="form-label">Confirm password</label>
  <input type="password" class="form-control" id="password2" name="pwd2">
</div>
<button class="btn btn-primary">Create</button>
<?php $endForm(); ?>

<?php $render('partial/footer', $data); ?>