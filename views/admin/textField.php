
<div class="d-flex flex-column flex-lg-row">
  <label class="form-label col-lg-3 col-form-label" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>
  
  <?php 
    if ($field['name'] === "id") {
      ?>
        <div class="form-group col-lg-9">
          <input required class="form-control" disabled type="<?php echo $field['type'] ?>" name="<?php echo $field['name'] ?>" value="<?php echo $row[$field['name']] ?>"/>
        </div>
      <?php
    }
  ?>
  
  <div 
    <?php 
      if (isset($field['id'])) echo 'id="'.$field['id'].'"';
      echo 'type="input"'; 
      echo 'fieldtype="input"'; 
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
    <input 
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
        echo 'class="form-control bg-success-subtle border-success col-10 input"'; 
      ?> 
      value="<?php echo $row[$field['name']] ?>"
    />

    <div class="invalid-feedback message">
    </div>

  </div>
</div>
