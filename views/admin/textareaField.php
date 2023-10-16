
<div class="d-flex flex-column flex-lg-row">
  <label class="form-label col-lg-3 col-form-label" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>
  
  <div class="form-group col-12 col-lg-9">
    <textarea 
    class="form-control bg-success-subtle border-success col-10" <?php if ($field['name'] === "id") echo 'hidden'; ?> 
    type="<?php echo $field['type'] ?>" 
    name="<?php echo $field['name'] ?>"> 
      <?php echo $row[$field['name']] ?>
    </textarea>
  </div>
</div>
