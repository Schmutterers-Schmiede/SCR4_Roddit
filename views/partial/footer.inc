</div>

<footer class="container">
  <?php if($data['latestEntry'] !== null): ?>
    <div style="display:flex; gap: 50px; width: 800px;">

      <div style="display: flex; flex-direction: row; align-items: baseline;">
        <pre class="text-muted p-0" style="margin: 0px">latest entry posted in </pre>
        <?php $htmlOut( $data['latestEntry']->threadTitle);?>
      </div>

      <div style="display: flex; flex-direction: row; align-items: baseline;">
        <pre class="text-muted p-0"> by </pre>
        <?php $htmlOut($data['latestEntry']->userName);?>
      </div>

      <div style="display: flex; flex-direction: row; align-items: baseline;">
        <pre class="text-muted p-0"> at </pre>
        <?php $htmlOut($data['latestEntry']->timeStamp->format('d.m.Y H:i:s'));?>
      </div>

    </div>
  <?php endif; ?>
</footer>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>