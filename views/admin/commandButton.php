<button class="btn btn-outline-success" formmethod="<?php echo $button['method']; ?>"><?php echo $button['value']?></button>
<?php
  if (isset($button['action'])) {
    echo '<input hidden type="text" name="a" value="'.$button['action'].'">';
  }
?>
