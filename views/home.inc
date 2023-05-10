<?php $render('partial\\header', $data); ?>

<h1>Welcome to röddit!</h1>
<hr>
<table class="table align-middle">
  <?php if ($data['threads'] !== null) : ?>
    <?php foreach ($data['threads'] as $thread) : ?>
      <tr>
        <td><?php $htmlOut($thread->title); ?></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>

<?php $render('partial\\footer', $data); ?>
