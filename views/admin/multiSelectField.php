
<div class="d-flex flex-column flex-lg-row">
  <label class="form-label col-lg-3" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>
  
  <div class="form-group col-12 col-lg-9">
    <select class="form-select me-2 bg-success-subtle border-success" 
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
  </div>
</div>