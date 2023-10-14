
<label class="form-label me-2" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>

<select class="form-select me-2" 
  name="<?php echo $field['name'].'[]' ?>" 
  multiple
>
  <?php
  echo '<pre>';
  print_r($field);
    foreach ($field['value'] as $option) {
      if (in_array($option['id'], $row[$field['name']])) {
        echo '<option'.' selected '.'value="'.$option['id'].'">'.$option['name'].'</option>';
      } else {
        echo '<option'.' '.'value="'.$option['id'].'">'.$option['name'].'</option>';
      }
    }
  ?>
</select>
