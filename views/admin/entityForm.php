<main class="container col p-0">         
  <div class="d-flex flex-column flex-lg-row p-0">
    <div class="d-flex flex-column w-100 h-100">
      <div class="title pt-5">
        <h1><?php echo $nameMenu?></h1>
      </div>
      <form  onSubmit="return isValidForm(this)" action="<?php echo $nameEntity; ?>" class="form admin">
        <div class="container p-0 form-div">
          <div class="d-grid gap-2 d-md-flex justify-content-md-end my-4">
            <?php $button = ['id' => "cancelButton" , 'value' => 'Abandonner', 'action' => 'c', 'method' => 'GET']; require 'commandButton.php';?>
            <?php $button = ['id' => "validButton" , 'value' => 'Enregistrer', 'method' => 'POST']; require 'commandButton.php';?>
          </div>
          <?php
            foreach ($fields as $field) {
              echo '<div class="mb-3">';
              switch ($field['type']) {
                case "text" : {
                  require 'textField.php';
                  break;
                }
                case "number" : {
                  require 'textField.php';
                  break;
                }
                case "email" : {
                  require 'textField.php';
                  break;
                }
                case "textarea" : {
                  require 'textareaField.php';
                  break;
                }
                case "date" : {
                  require 'textField.php';
                  break;
                }
                case "select" : {
                  require 'selectField.php';
                  break;
                }
                case "multiSelect" : {
                  require 'multiSelectField.php';
                  break;
                }
                case "array" : {
                  require 'arrayField.php';
                  break;
                }
              }
              echo '</div>';
            }
          ?>
        </div>
      </form>
    </div>
  </div>
</main>
