<div class="terminals">
  <?php if (count($terminals)): ?>
  <div class="terminals_list">
    <dl>
      <?php foreach ($terminals as $terminal): ?>
      <dt><?php echo $terminal->uuid; ?></dt>
      <dd><?php echo $terminal->url; ?></dd>
      <span id="<?php echo $terminal->uuid; ?>-answer"><img src="<?php echo base_url();?>assets/image/ajax-loader.gif"/></span>
      <script>
        $.ajax({
          url: '<?php echo base_url();?>ajax/check_terminal?url=<?php echo urlencode($terminal->url); ?>',
          success: function(data) {
            $('#<?php echo $terminal->uuid; ?>-answer').html(data);
          }
        });
      </script>
    </div>
    <? endforeach; ?>
  </dl>
  <div class="pager"><?php echo $this->pagination->create_links(); ?></div>
  <?php else: ?>
  <p>No terminal.</p>
  <?php endif; ?>
</div>