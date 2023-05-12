<?php $render('partial\\header', $data); ?>

<table class="table align-middle">
  <?php if ($data['selectedThread'] !== null) : ?>
    <h1><?php $htmlOut($data['selectedThread']->title) ?></h1>
    <?php foreach ($data['selectedThread']->entries as $entry) : ?>
      <tr>
        <td>          
          <?php $htmlOut($entry->userName);?>
          <time style="margin-left: 30px;"><?php $htmlOut($entry->dateTime);?></time>
          <p style="margin-top: 10px;"><?php $htmlOut($entry->text) ?></p>
        </td>        
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>

<?php $render('partial\\footer', $data); ?>
