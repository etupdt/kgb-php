
<div class="d-flex flex-column flex-lg-row">
  
  <label class="form-label col-lg-3 col-form-label" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>
  
  <div 
    <?php 
      if (isset($field['id'])) echo 'id="'.$field['id'].'"';
      echo 'type="select"'; 
      echo 'fieldtype="select"'; 
      $classes = ''; 
      if (isset($field['events'])) {
        $classes = ' need-validation'; 
        foreach($field['events'] as $event=>$eventValue) {
          foreach ($eventValue as $value) {
            $classes = $classes.' validation-'.$value['function'];
          }
        }
      }
      echo 'class="form-group col-12 col-lg-9'.$classes.'"';
    ?>
  >
    <select 
      <?php
        echo 'name="'.$field['name'].'"' ;
        if (isset($field['events'])) {
          foreach($field['events'] as $event=>$eventValue) {
            $functions = '';
            $params = ["'".$field["id"]."'", "'".$row['id']."'"];
            if (isset($value['param'])) {
              $params[] = $value['param'];
            }
            foreach ($eventValue as $value) {
              $functions = $functions.$value['function']."(".implode(', ', $params).");";
            }
            echo $event.'="'.$functions.'"';
          }
        }
        echo 'class="form-select bg-success-subtle border-success col-9 select"'; 
        ?>
        >
        <?php
          foreach (array_keys($field['value']) as $option) {
          echo '<option class="bg-success-subtle border-success "';
          if ($option == $row[$field['name']]) {
            echo '<option selected value="'.$option.'">'.$field['value'][$option].'</option>';
          } else {
            echo '<option value="'.$option.'">'.$field['value'][$option].'</option>';
          }
        }
      ?>
    </select>
    
    <div class="invalid-feedback message">
    </div>

  </div>
</div>
