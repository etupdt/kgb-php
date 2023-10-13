
<label class="form-label me-2" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>

<select class="form-select me-2" 
  type="<?php echo $field['type'] ?>" 
  name="<?php echo $field['name'] ?>" 
>
  <?php
    foreach (array_keys($field['value']) as $option) {
      echo '<option'.($option == $row[$field['name']] ? ' selected ' : ' ').'value="'.$option.'">'.$field['value'][$option].'</option>';
    }
  ?>
</select>
