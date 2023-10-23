<button 
  class="btn btn-outline-success" 
  formmethod="<?php echo (isset($button['method']) ? $button['method'] : 'GET'); ?>"
  type="<?php echo (isset($button['type']) ? $button['type'] : 'submit'); ?>"
><?php echo $button['value']?></button>
<?php
  if (isset($button['action'])) {
    echo '<input hidden type="text" id="'.$button['id'].'" 
      name="a" 
      value="'.$button['action'].'" .'.(isset($button['function']) ? $button['function'] : '').'>';
  }
?>
