<dl>
  <?php foreach ($terminals as $terminal): ?>
  <dt><?php echo $terminal->uuid; ?></dt>
  <dd><span id="<?php echo $terminal->uuid; ?>-answer"><img src="<?php echo base_url();?>assets/image/ajax-loader.gif"/></span></dd>
  <script>
    $.ajax({
      url: '<?php echo base_url();?>ajax/check_terminal?autodelete=true&url=<?php echo urlencode($terminal->url); ?>',
      success: function(data) {
        $('#<?php echo $terminal->uuid; ?>-answer').html(data);
      }
    });
  </script>
  <?php endforeach; ?>
</dl>