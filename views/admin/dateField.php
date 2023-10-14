
<div class="d-flex flex-column flex-lg-row">
  <label class="form-label col-form-label col-lg-3" for="<?php echo $field['name'] ?>"><?php echo $field['label'] ?></label>

  <div class="form-group col-lg-9">
    <input class="form-control me-2 bg-success-subtle border-success" type="<?php echo $field['type'] ?>" name="<?php echo $field['name'] ?>" value="<?php echo $row[$field['name']] ?>"/>
  </div>
</div>