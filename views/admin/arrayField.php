<label class="form-label me-2" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>

<?php 
    echo '<pre>';
    print_r($field);
    print_r($row);
  if ($field['name'] === "id") {
    ?>
      <input class="form-control me-2" disabled type="<?php echo $field['type'] ?>" name="<?php echo $field['name'] ?>" value="<?php echo $row[$field['name']] ?>"/>
    <?php
  }
?>

<input class="form-control me-2" <?php if ($field['name'] === "id") echo 'hidden'; ?> type="<?php echo $field['type'] ?>" name="<?php echo $field['name'] ?>" value="<?php echo $row[$field['name']] ?>"/>
