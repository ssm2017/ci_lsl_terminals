<div class="center border terminals_list">
  <div>
    Found <?php echo $num_results; ?> Terminals
  </div>

  <?php if ($num_results): ?>
  <table>
    <thead class="border">
      <?php foreach($fields as $field_name => $field_display): ?>
      <th <?php if ($sort_by == $field_name) echo "class=\"sort_$sort_order\"" ?>>
        <?php echo anchor("terminals_list/index/$field_name/" .
          (($sort_order == 'asc' && $sort_by == $field_name) ? 'desc' : 'asc') ,
          $field_display); ?>
      </th>
      <?php endforeach; ?>
      <th>Status</th>
    </thead>

    <tbody class="border">
      <?php foreach($terminals as $terminal): ?>
      <tr>
        <?php foreach($fields as $field_name => $field_display): ?>
        <td>
          <?php echo $terminal->$field_name; ?>
        </td>
        <?php endforeach; // td ?>
        <td>
          <span id="<?php echo $terminal->uuid; ?>-answer"><img src="<?php echo base_url();?>assets/image/ajax-loader.gif"/></span>
          <script>
            $.ajax({
              url: '<?php echo base_url();?>ajax/check_terminal?url=<?php echo urlencode($terminal->url); ?>',
              success: function(data) {
                $('#<?php echo $terminal->uuid; ?>-answer').html(data);
              }
            });
          </script>
        </td>
      </tr>
      <?php endforeach; // tr ?>
    </tbody>

  </table>

  <?php if (strlen($pagination)): ?>
  <div class="center border pager">
    <?php echo $pagination; ?>
  </div>
  <?php endif; // pagination ?>
  <?php endif; // num results ?>
</div>