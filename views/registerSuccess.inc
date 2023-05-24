<?php $render('partial\\header', $data); ?>

<div style="display:flex; justify-content: center;">
  <div style="display: flex; justify-content: center; flex-direction: column;">
    <h1 style="text-align: center;">Welcome to röddit!</h1>
    <p>Your Account has been successfully registered. you may Login now.</p>
    <?php $beginForm('User', 'Login', method: 'get', cssClass: 'form-inline'); ?>
    <div style="display: flex; justify-content: center;">
      <button class="btn btn-primary">Login</button>
    </div>
    <?php $endForm(); ?>
  </div>
</div>

<?php $render('partial\\footer', $data); ?>
