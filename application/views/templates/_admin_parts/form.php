<?php echo (!isset($detail)) ? form_open_multipart($form_action) : null;?>
<?php foreach ($form_data as $key => $value): ?>
  <?php if ($value['type']!='hidden'): ?>
    <div class="col-sm-12">
      <div class="form-group form-float">
        <div class="form-line">
          <?php
            switch ($value['type']) {
              case 'select':
                  $label =  $value['placeholder'];
                  $options =  $value['option'];
                  $name = $value['name'];
                  $selected = $value['value'];
                  unset($value['placeholder']);
                  unset($value['option']);
                  unset($value['name']);
                  echo "<p' class='text-mute'>$label</p>";
                  echo form_dropdown($name, $options, $selected, $value);
                break;
              case 'textarea':
                $label =  $value['placeholder'];
                unset($value['placeholder']);
                echo "<label class='form-label'>$label</label>";
                echo form_textarea($value);
                break;
              case 'file':
                $label =  $value['placeholder'];
                echo "<label class='form-label'>$label</label><br/><br/>";
                echo form_upload($value);
                break;
              default:
                if($value['name'] == "date"){
                  $label =  $value['placeholder'];
                  unset($value['placeholder']);
                  // echo "<div class='form-group'><div class='form-line' id='bs_datepicker_container'>";
                  echo "<label class='form-label'>$label</label>";
                  echo form_input($value);
                  // echo "</div></div>";
                  break;
                }else{
                  $label =  $value['placeholder'];
                  unset($value['placeholder']);
                  echo "<label class='form-label'>$label</label>";
                  echo form_input($value);
                  break;
                }
            }
          ?>
        </div>
      </div>
    </div>
  <?php else: ?>
    <?php
      $name = $value['name'];
      $val = $value['value'];
      echo form_hidden($name, $val);
     ?>
  <?php endif; ?>

<?php endforeach; ?>
  <?php if (!isset($detail)): ?>
    <div class="col-sm-12 ">
        <button type="submit" class="btn float-left btn-success waves-effect">Simpan</button>
    </div>
    <?php echo form_close();?>
  <?php endif; ?>
