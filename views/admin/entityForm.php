<main class="container col">         
  <div class="row pt-5">
    <?php require_once 'sidemenu.php' ?>
    <div class="d-flex flex-column col-9 h-100">
      <div class="row title">
        <h1><?php echo $nameMenu?></h1>
      </div>
      <form action="/<?php echo $nameEntity; ?>" method="POST" class="form">
        <div class="container px-4">
          <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
            <button role="button" class="btn btn-outline-success" formaction='/<?php echo $nameEntity; ?>/c'>Abandonner</button>
            <button role="button" class="btn btn-outline-success">Enregistrer</button>
          </div>
          <?php
            foreach ($fields as $field) {
              echo '<div class="">';
              switch ($field['type']) {
                case "text" : {
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
