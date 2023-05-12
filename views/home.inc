<?php $render('partial\\header', $data); ?>

<h1>Welcome to röddit!</h1>
<hr>
<table class="table align-middle">
  <?php $render('partial\\threadList', $data); ?>
</table>

<?php $render('partial\\footer', $data); ?>
