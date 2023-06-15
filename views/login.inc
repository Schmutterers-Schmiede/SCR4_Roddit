<?php $render('partial/header', $data); ?>

<style>
  .textBox{
    width: 400px;
  }
</style>

<h1>Login</h1>

<?php $beginForm('User', 'LogIn', method: 'post'); ?>
<div class="mb-3">
    <label for="userName" class="form-label">User name</label>
    <input class="form-control textBox" id="userName" name="un" value="<?php $htmlOut($data['userName']); ?>">
</div>
<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control textBox" id="password" name="pwd">
</div>
<button class="btn btn-primary">Login</button>
<?php $endForm(); ?>

<?php $render('partial/footer', $data); ?>