
<label class="form-label me-2" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>

<select class="form-select me-2" 
  type="<?php echo $field['type'] ?>" 
  name="<?php echo $field['name'] ?>" 
  value="<?php echo $field['value'] ?>"
>
  <?php
    foreach ($field['value'] as $option) {
      echo '<option value="'.$option['id'].'">'.$option['name'].'</option>';
    }
  ?>
</select>
