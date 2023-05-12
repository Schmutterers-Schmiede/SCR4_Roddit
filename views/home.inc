<?php $render('partial\\header', $data); ?>

<h1>Welcome to röddit!</h1>
<hr>
<table class="table align-middle">
  <?php if ($data['threads'] !== null) : ?>
    <?php foreach ($data['threads'] as $thread) : ?>
      <tr>
        <td><strong><?php $link($thread->title, 'ThreadDetail', 'Thread', ['tid' => $thread->id],  cssClass: 'nav-link'); ?></strong></td>
        <td>
          Last entry posted by: <strong><?php $htmlOut($thread->lastEntryUserName);?></strong>
        </td>
        <td>
          at: <strong><?php $htmlOut($thread->lastEntryDateTime->format('d.m.Y H:i:s')); ?></strong>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>

<?php $render('partial\\footer', $data); ?>
