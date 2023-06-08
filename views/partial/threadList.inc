<?php if ($data['threads'] !== null) : ?>
  <table class="table">
    <?php foreach ($data['threads'] as $thread) : ?>

      <tr style="height: 3rem;">
        <div>
          <td><strong><?php $link($thread->title, 'Threads', 'Thread', ['tid' => $thread->id, 'title' => $thread->title],  cssClass: 'nav-link'); ?></strong></td>

          <td>Created by ü\<?php $htmlOut($thread->userName);?></td>

          <td>
            <?php if($thread->entryCount > 0) :?>
              Last entry posted by ü\<?php $htmlOut($thread->lastEntryUserName);?>
            <?php endif; ?>
          </td>

          <td>
            <?php if($thread->entryCount > 0) :?>
              at <time style="margin-left: 30px;"><?php $htmlOut($thread->lastEntryTimestamp->format('d.m.Y H:i:s')); ?></time>
            <?php endif; ?>
          </td>

          <td>
            <?php if($thread->deletable): ?>
              <?php $beginForm('Threads', 'DeleteThread',['author' => $thread->userName, 'threadId' => $thread->id],  method: 'post', cssClass: 'form-inline'); ?>    
                <button style="align-self: end" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this sübröddit?')">Delete</button>
              <?php $endForm(); ?>              
            <?php endif; ?>
          </td>
          
        </div>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>