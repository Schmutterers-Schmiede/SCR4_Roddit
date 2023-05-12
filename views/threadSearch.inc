<?php $render('partial\\header', $data); ?>

<h1>Search</h1>

<div class="mb-3">
  <?php $beginForm('Threads',  'Search'); ?>
  <div class="row g-3">
    <div class="col-auto">
      <input class="form-control" name="f" value="<?php $htmlOut($data['filter']); ?>">
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Search</button>
    </div>
  </div>
  <?php $endForm(); ?>
</div>

<?php if ($data['threads'] !== null) : ?>
  <?php if (sizeof($data['threads']) > 0) : ?>
    <?php $render('partial/threadList', ['threads' => $data['threads'], 'context' => $data['context']]) ?>
  <?php else : ?>
    <p>No threads under this name.</p>
  <?php endif; ?>
<?php else: ?>
  <p>Please enter a search term.</p>
<?php endif; ?>


<?php $render('partial\\footer', $data); ?>