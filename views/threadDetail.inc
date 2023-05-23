<?php $render('partial\\header', $data); ?>

<table class="table align-middle">
  <h1><?php $htmlOut($data['thread']->title); ?></h1>
  <?php $beginForm('Threads', 'CreateEntry', method: 'post', cssClass: 'form-inline'); ?>    
    <button style="align-self: end" class="btn btn-secondary">Compose Entry</butto>
  <?php $endForm(); ?>
  <?php foreach ($data['thread']->entries as $entry) : ?>
    <tr>
      <td>          
        posted by ü/<?php $htmlOut($entry->userName);?>
        <time style="margin-left: 30px;"><?php $htmlOut($entry->timestamp->format('d.m.Y H:i:s'));?></time>
        <p style="margin-top: 10px;"><?php $htmlOut($entry->text);?></p>
      </td>        
    </tr>
  <?php endforeach; ?>
</table>

<?php $render('partial\\footer', $data); ?>
