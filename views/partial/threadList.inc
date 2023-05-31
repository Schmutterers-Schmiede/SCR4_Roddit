<?php if ($data['threads'] !== null) : ?>
<table class="table">
  <?php foreach ($data['threads'] as $thread) : ?>

    <tr style="height: 3rem;">
      <div>
        <td><strong><?php $link($thread->title, 'Threads', 'Thread', ['tid' => $thread->id],  cssClass: 'nav-link'); ?></strong></td>
        <td>
            <?php if($thread->entryCount > 0) :?>
              Last entry posted by ü\<?php $htmlOut($thread->lastEntryUserName);?>
            <?php endif; ?>
          </td>
          <td>
            <?php if($thread->entryCount > 0) :?>
              <time style="margin-left: 30px;"><?php $htmlOut($thread->lastEntryTimestamp->format('d.m.Y H:i:s')); ?></time>
            <?php endif; ?>
          </td>
      </div>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
</table>