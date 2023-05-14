<?php $render('partial\\header', $data); ?>

<div style="display:flex; justify-content: space-between;">
<h1>Welcome to röddit!</h1>
  <?php if($data['user'] !== null): ?>
    <?php $beginForm('Threads', 'PostThread', method: 'get', cssClass: 'form-inline'); ?>    
      <button style="align-self: end" class="btn btn-secondary">Create Thread</butto>
    <?php $endForm(); ?>
  <?php endif; ?>
</div>

<hr>
  <?php $render('partial\\threadList', ['threads' => $data['threads']]); ?>

<?php $render('partial\\footer', $data); ?>
