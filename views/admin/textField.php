
<div class="d-flex flex-column flex-lg-row">
  <label class="form-label col-lg-3 col-form-label" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>
  
  <?php 
    if ($field['name'] === "id") {
      ?>
        <div class="form-group col-lg-9">
          <input class="form-control" disabled type="<?php echo $field['type'] ?>" name="<?php echo $field['name'] ?>" value="<?php echo $row[$field['name']] ?>"/>
        </div>
      <?php
    }
  ?>
  
  <div class="form-group col-12 col-lg-9">
    <input class="form-control bg-success-subtle border-success col-10" <?php if ($field['name'] === "id") echo 'hidden'; ?> type="<?php echo $field['type'] ?>" name="<?php echo $field['name'] ?>" value="<?php echo $row[$field['name']] ?>"/>
  </div>
</div>
