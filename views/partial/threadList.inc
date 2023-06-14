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
            height: 80px;
            min-width: 1000px;
          }
          .threadCard-meta,
          .threadCard-meta-author,
          .threadCard-meta-lastEntry,
          .threadCard-meta-lastEntry-author,
          .threadCard-meta-lastEntry-time,
          .threadCard-meta-entryCount        
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
            top: 25%;
            align-self: end;
          }
          .threadCard-meta-author {            
            width: 200px;
          }
          .threadCard-meta-lastEntry-author {
            width: 300px;
          }

          

        </style>
        <div class="threadCard">
          <strong><?php $link($thread->title, 'Threads', 'Thread', ['tid' => $thread->id, 'title' => $thread->title],  cssClass: 'nav-link'); ?></strong>
          <div class="threadCard-meta">

            <div class="threadCard-meta-author">
              <pre>Created by ü\</pre> 
              <?php $htmlOut($thread->userName);?>
            </div>

            <div class="threadCard-meta-entryCount">
              <?php $htmlOut($thread->entryCount);?>
              <pre> entries</pre> 
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
              <button class="btn btn-danger deleteButton" onclick="return confirm('Are you sure you want to delete this sübröddit?')">Delete</button>
            <?php $endForm(); ?>              
          <?php endif; ?>
          
        </div>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>