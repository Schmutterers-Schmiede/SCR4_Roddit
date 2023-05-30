<?php $render('partial/header', $data); ?>

<h1>Create new Thread</h1>

<?php $beginForm('Threads', 'CreateThread', method: 'post'); ?>
  <div class="mb-3">
      <label for="title" class="form-label">Title:</label>
      <input class="form-control" id="title" name="title">
  </div>
  <button class="btn btn-primary">Create</button>
<?php $endForm(); ?>

<?php $render('partial/footer', $data); ?>