
<div class="d-flex flex-column flex-lg-row">
  <label class="form-label col-lg-3 col-form-label" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>
  
  <div class="form-group col-12 col-lg-9">
    <select class="form-select bg-success-subtle border-success col-9" 
      type="<?php echo $field['type'] ?>" 
      name="<?php echo $field['name'] ?>" 
    >
      <?php
        foreach (array_keys($field['value']) as $option) {
          echo '<option class="bg-success-subtle border-success"'.($option == $row[$field['name']] ? ' selected ' : ' ').'value="'.$option.'">'.$field['value'][$option].'</option>';
        }
      ?>
    </select>
  </div>
</div>
