<?php $render('partial\\header', $data); ?>

<h1>Welcome to röddit!</h1>
<hr>
  <?php $render('partial\\threadList', ['threads' => $data['threads']]); ?>

<?php $render('partial\\footer', $data); ?>
