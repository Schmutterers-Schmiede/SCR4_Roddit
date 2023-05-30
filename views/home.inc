<?php $render('partial\\header', $data); ?>

<div style="display:flex; justify-content: space-between;">
  <?php if($data['user'] !== null): ?>
    <?php $beginForm('Threads', 'PostThread', method: 'get', cssClass: 'form-inline'); ?>    
      <button style="align-self: end" class="btn btn-primary">Create Thread</butto>
    <?php $endForm(); ?>
  <?php endif; ?>
</div>

<hr>
<?php $render('partial\\threadList', ['threads' => $data['threads']]); ?>

<?php $render('partial\\footer', $data); ?>
