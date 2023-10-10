<main class="container col">         
  <div class="row title">
    <h1><?php echo $nameEntity?></h1>
  </div>
  <div class="row">
    <?php require_once 'sidemenu.php' ?>
    <form action="planque" method="<?php echo $method; ?>" class="col-9 form">
      <div class="container">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end m-5">
        <button role="button" class="btn btn-outline-success">Abandonner</button>
        <button role="button" class="btn btn-outline-success">Enregistrer</button>
        </div>
        <?php
          foreach ($fields as $field) {
            echo '<div class="m-5">';
            switch ($field['type']) {
              case "text" : require 'textField.php';
            }
            echo '</div>';
          }
        ?>
      </div>
    </form>
  </div>
</main>
