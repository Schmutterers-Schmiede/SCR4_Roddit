<?php if ($data['threads'] !== null) : ?>
  <table class="table">
    <?php foreach ($data['threads'] as $thread) : ?>

      <tr>
        <style>
          .threadCard{
            border: 2px solid rgb(0, 104, 14);
            background-color: rgb(223, 255, 223);
            position: relative;
            padding-left: 10px;
            padding-right: 10px;
            border-radius: 10px;
            margin-top: 10px;
          }
          .threadCard-meta,
          .threadCard-meta-author,
          .threadCard-meta-lastEntry,
          .threadCard-meta-lastEntry-author,
          .threadCard-meta-lastEntry-time
          {
            display: flex; 
            flex-direction: row;     
            align-items: baseline;
          }
          .threadCard-meta{
            gap: 50px;
          }
          .threadCard-meta-lastEntry{
            gap: 20px;
          }
          .deleteButton{
            position: absolute;
            right: 10px;
            top: 10px;
          }

        </style>
        <div class="threadCard">
          <strong><?php $link($thread->title, 'Threads', 'Thread', ['tid' => $thread->id, 'title' => $thread->title],  cssClass: 'nav-link'); ?></strong>
          <div class="threadCard-meta">

            <div class="threadCard-meta-author">
              <pre>Created by ü\</pre> 
              <?php $htmlOut($thread->userName);?>
            </div>
            
            <?php if($thread->entryCount > 0) :?>
              <div class="threadCard-meta-lastEntry">
                <div class="threadCard-meta-lastEntry-author">
                  <pre>Last entry posted by ü\</pre>
                  <?php $htmlOut($thread->lastEntryUserName);?>
                </div>

                <div class="threadCard-meta-lastEntry-time">
                  <pre>at </pre>  
                  <time><?php $htmlOut($thread->lastEntryTimestamp->format('d.m.Y H:i:s')); ?></time>
                </div>
                
              </div>
            <?php endif; ?>
          </div>
          <?php if($thread->deletable): ?>
            <?php $beginForm('Threads', 'DeleteThread',['author' => $thread->userName, 'threadId' => $thread->id],  method: 'post', cssClass: 'form-inline'); ?>    
              <button style="align-self: end" class="btn btn-danger deleteButton" onclick="return confirm('Are you sure you want to delete this sübröddit?')">Delete</button>
            <?php $endForm(); ?>              
          <?php endif; ?>
          
        </div>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>