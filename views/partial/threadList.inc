<?php if ($data['threads'] !== null) : ?>
  <?php foreach ($data['threads'] as $thread) : ?>
    <tr>
      <div>

        <td><strong><?php $link($thread->title, 'Threads', 'Thread', ['tid' => $thread->id],  cssClass: 'nav-link'); ?></strong></td>
        <td>
          Last entry posted by ü\<?php $htmlOut($thread->lastEntryUserName);?>
        </td>
        <td>
          <time style="margin-left: 30px;"><?php $htmlOut($thread->lastEntryTimestamp->format('d.m.Y H:i:s')); ?></time>
        </td>
      </div>
    </tr>
  <?php endforeach; ?>
<?php endif; ?>