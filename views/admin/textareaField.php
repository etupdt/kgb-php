
<div class="d-flex flex-column flex-lg-row">
  <label class="form-label col-lg-3 col-form-label" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>
  
  <div 
    <?php 
      if (isset($field['id'])) echo 'id="'.$field['id'].'"';
      echo 'type="textarea"'; 
      echo 'fieldtype="textarea"'; 
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
    <textarea 
      <?php 
        if ($field['name'] === "id") echo ' hidden ';
        echo 'type="'.$field['type'].'"'; 
        echo 'name="'.$field['name'].'"' ;
        if (isset($field['events'])) {
          foreach($field['events'] as $event=>$eventValue) {
            $functions = '';
            foreach ($eventValue as $value) {
              $functions = $functions.$value['function']."('".$field['id']."', '".(isset($value['param']) ? $value['param'] : '')."');";
            }
            echo $event.'="'.$functions.'"';
          }
        }
        echo 'class="form-control bg-success-subtle border-success col-10 textarea"'; 
      ?> 
      value="<?php echo $row[$field['name']] ?>"
    ><?php echo $row[$field['name']] ?></textarea>

    <div class="invalid-feedback message">
    </div>

  </div>
</div>
