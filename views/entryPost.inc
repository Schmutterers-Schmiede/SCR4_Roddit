<?php $render('partial/header', $data); ?>

<h1>Create new entry</h1>
<?php $beginForm('Entries', 'CreateEntry', ['threadId' => $data['threadId']], method: 'post'); ?>
  <div class="mb-3" style="min-height: 300px; display: flex; flex-direction: column;">
      <label for="text" class="form-label">Enter text:</label>
      <textarea name="text" rows="10" maxlength="4000"></textarea>      
  </div>
  <button class="btn btn-primary">Post</button>
<?php $endForm(); ?>

<?php $render('partial/footer', $data); ?>