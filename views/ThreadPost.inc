<?php $render('partial/header', $data); ?>

<style>
  .textBox{
    width: 700px;
  }
</style>

<h1>Create new sübröddit</h1>


<?php $beginForm('Threads', 'CreateThread',['userId' => $data['user']->id], method: 'post'); ?>
  <div class="mb-3">
      <label for="title" class="form-label">Title:</label>
      <input class="form-control textBox" id="title" name="title">
  </div>
  <button class="btn btn-primary">Create</button>
<?php $endForm(); ?>

<?php $render('partial/footer', $data); ?>