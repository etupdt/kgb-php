
<div class="d-flex flex-column flex-lg-row">
  <label class="form-label col-lg-3" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>
  
  <div 
    <?php 
      if (isset($field['id'])) echo 'id="'.$field['id'].'"';
      echo 'type="select"'; 
      echo 'fieldtype="multiselect"'; 
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
        echo 'name="'.$field['name'].'[]"' ;
        $optionEvents = ' ';
        if (isset($field['events'])) {
          foreach($field['events'] as $event=>$eventValue) {
            $littleEvent = substr($event, strpos($event, '.') + 1);
            $selectFunctions = '';
            $optionFunctions = '';
            $params = "'".$field["id"]."'";
            foreach ($eventValue as $value) {
              if (isset($value['param'])) {
                $params = $params.", '".implode("', '", $value['param'])."'";
              }
              if (strpos($event, 'select') === 0) {
                $selectFunctions = $selectFunctions.$value['function']."(".$params.");";
              } else {
                $optionFunctions = $optionFunctions.$value['function']."(this, ".$params.");";
              }
            }
            if ($selectFunctions !== '') {
              echo $littleEvent.'="'.$selectFunctions.'"';
            }
            if ($optionFunctions !== '') {
              $optionEvents = $optionEvents.$littleEvent.'="'.$optionFunctions.'" ';
            }
          }
        }
        echo 'class="form-select me-2 bg-success-subtle border-success multiselect"';
        echo 'multiple';
      ?>
    >
      <?php
        foreach ($field['value'] as $index=>$option) {
          if (in_array($option['id'], $row[$field['name']])) {
            echo '<option selected value="'.$option['id'].'"'.$optionEvents.'>'.$option['name'].'</option>';
          } else {
            echo '<option index="'.$index.'" value="'.$option['id'].'"'.$optionEvents.'>'.$option['name'].'</option>';
          }
        }
      ?>
    </select>
    
    <div class="invalid-feedback message">
    </div>

  </div>
</div>