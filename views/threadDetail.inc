<?php $render('partial\\header', $data); ?>

<h1><?php $htmlOut($data['title']); ?></h1>
<?php if($data['user'] !== null): ?>
  <?php $beginForm('Entries', 'PostEntry', ['threadId' => $data['threadId']], method: 'get', cssClass: 'form-inline'); ?>    
    <button style="align-self: end" class="btn btn-primary">Compose Entry</button>
  <?php $endForm(); ?>
<?php endif; ?>
<table class="table align-middle" style="margin-top: 10px">
  <?php foreach ($data['entries'] as $entry) : ?>
    <tr>
      <style>
        .entryCard{
          border: 2px solid rgb(0, 31, 170);
          border-radius: 10px;
          margin-top: 10px;
          padding-left: 10px;
          padding-right: 10px;
          background-color: rgb(222, 234, 255);
        }
        .entryCard-meta{
          display: flex; 
          flex-direction: row;           
          gap: 20px; 
        }
        .entryCard-meta-user{
          display: flex; 
          flex-direction: row;     
          align-items: baseline;      
        }
        .entryCard-meta-time{
          display: flex; 
          flex-direction: row;     
          align-items: baseline;      
        }
        .entryCard-header{
          display: flex;
          flex-direction: row;
          position: relative;
        }
        .entryCard-deleteButton{
          margin-top: 7px;
          position: absolute;
          right: 0px;
          top: 5px;
        }
      </style>
      <div class="entryCard">
        <div class="entryCard-header">
          <div class="entryCard-meta" >
            
            <div class="entryCard-meta-user">
              <pre class="text-muted p-0" style="margin: 0px">posted by ü/</pre>
              <?php $htmlOut($entry->userName);?>
            </div>
            
            <div class="entryCard-meta-time">
              <pre class="text-muted p-0" style="margin: 0px">at </pre>
              <time><?php $htmlOut($entry->timestamp->format('d.m.Y H:i:s'));?></time>
            </div>
            
          </div>

          <?php if($entry->deletable): ?>     
            <?php $beginForm('Entries', 'DeleteEntry',['author' => $entry->userName, 'entryId' => $entry->id, 'threadId' => $data['threadId']],  method: 'post', cssClass: 'form-inline'); ?>    
              <button style="align-self: end" class="btn btn-danger entryCard-deleteButton" onclick="return confirm('Are you sure you want to delete this entry?')">Delete</button>
            <?php $endForm(); ?>  
          <?php endif; ?>
        </div>
        <p style="margin-top: 10px; margin-bottom: 10px;"><?php $htmlOut($entry->text);?></p>
      </div>    
        
      

    </tr>
  <?php endforeach; ?>
</table>

<?php $render('partial\\footer', $data); ?>
