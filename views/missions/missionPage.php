
<main class="container d-flex flex-column align-items-center main-mission p-0">
  <h1 class="pt-3 m-0">Mission <?php echo $mission['title'] ?></h1>
  <form class="form-mission">
    <div class="form-group">
      <label class="" for="description">Description</label>
      <textarea class="form-control bg-success-subtle border-success rounded-0" disabled id="description" ><?php echo $mission['description'] ?></textarea>
    </div>
    <div class="d-flex flex-column flex-md-row col-12">
      <div class="form-group col-md-4 pe-md-2">
        <label class="mt-3" for="codeName">Nom de code</label>
        <input class="form-control bg-success-subtle border-success rounded-0" disabled id="codeName" value="<?php echo $mission['codeName'] ?>">
      </div>
      <div class="form-group col-md-4 pe-md-2">
        <label class="mt-3" for="begin">Date de début</label>
        <input class="form-control bg-success-subtle border-success rounded-0" disabled id="begin" value="<?php echo $mission['begin'] ?>">
      </div>
      <div class="form-group col-md-4">
        <label class="mt-3" for="end">Date de fin</label>
        <input class="form-control bg-success-subtle border-success rounded-0" disabled id="end" value="<?php echo $mission['end'] ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="mt-3" for="country">Pays</label>
      <input class="form-control bg-success-subtle border-success rounded-0" disabled id="country" value="<?php echo $mission['country'] ?>">
    </div>
    <div class="d-flex flex-column flex-md-row col-12">
      <div class="form-group col-md-6 pe-md-2">
        <label class="mt-3" for="statut">Statut de la mission</label>
        <input class="form-control bg-success-subtle border-success rounded-0" disabled id="statut" value="<?php echo $mission['statut'] ?>">
      </div>
      <div class="form-group col-md-6">
        <label class="mt-3" for="typemission">Type de mission</label>
        <input class="form-control bg-success-subtle border-success rounded-0" disabled id="typemission" value="<?php echo $mission['typeMission'] ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="mt-3" for="speciality">Spécialité requise</label>
      <input class="form-control bg-success-subtle border-success rounded-0" disabled id="speciality" value="<?php echo $mission['speciality'] ?>">
    </div>
    <div class="form-group">
      <label class="mt-3" for="hideouts">Planques</label>
      <div class="col-12 table-responsive">
        <table class="table table-data table-success table-striped m-0 border border-success" id="hideouts">
          <?php 
            foreach($mission['hideouts'] as $hideout) {
              echo '<tr>';
              echo '<td code>'.$hideout['code'].'</td>';
              echo '<td address>'.$hideout['address'].'</td>';
              echo '<td type>'.$hideout['type'].'</td>';
              echo '<td country>'.$hideout['country']->getName().'</td>';
              echo '</tr>';
            }
          ?>
        </table>
      </div>
      <?php 
        foreach($roles as $role) {
          echo '<div class="form-group col-12 table-responsive">';
          echo '<label class="mt-3" for="speciality">'.$role->getRole().'</label>';
          echo '<table class="table table-success table-striped m-0 border border-success">';
          foreach ($mission[str_replace(' ', '_', $role->getRole())] as $actor) {
            echo '  <tr>';
            echo '    <td class="firstname">'.$actor['firstname'].'</td>';
            echo '    <td class="lastname">'.$actor['lastname'].'</td>';
            echo '    <td class="birthdate">'.$actor['birthdate'].'</td>';
            echo '    <td class="identificationCode">'.$actor['identificationCode'].'</td>';
            echo '    <td class="country">'.$actor['country'].'</td>';
            echo '  </tr>';
          }
          echo'</table>';
          echo'</div>';
        }
      ?>
    </div>
  </form>
</main>