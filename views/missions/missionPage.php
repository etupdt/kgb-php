
<main class="container main-mission">
  <h1>Mission <?php echo $mission['title'] ?></h1>
  <form>
    <div class="form-group">
      <label for="description">Description</label>
      <textarea class="form-control" id="description" ><?php echo $mission['description'] ?></textarea>
    </div>
    <div class="d-flex flex-column flex-md-row col-12">
      <div class="form-group col-md-4 me-md-1">
        <label for="codeName">Nom de code</label>
        <input class="form-control" id="codeName" value="<?php echo $mission['codeName'] ?>">
      </div>
      <div class="form-group col-md-4 me-md-1">
        <label for="begin">Date de début</label>
        <input class="form-control" id="begin" value="<?php echo $mission['begin'] ?>">
      </div>
      <div class="form-group col-md-4">
        <label for="end">Date de fin</label>
        <input class="form-control" id="end" value="<?php echo $mission['end'] ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="country">Pays</label>
      <input class="form-control" id="country" value="<?php echo $mission['country'] ?>">
    </div>
    <div class="d-flex flex-column flex-md-row col-12">
      <div class="form-group col-md-6 me-md-1">
        <label for="statut">Statut de la mission</label>
        <input class="form-control" id="statut" value="<?php echo $mission['statut'] ?>">
      </div>
      <div class="form-group col-md-6 me-md-1">
        <label for="typemission">Type de mission</label>
        <input class="form-control" id="typemission" value="<?php echo $mission['typeMission'] ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="speciality">Spécialité requise</label>
      <input class="form-control" id="speciality" value="<?php echo $mission['speciality'] ?>">
    </div>
    <div class="form-group">
      <label for="hideouts">Planques</label>
      <table class="border w-100" id="hideouts">
        <?php 
          foreach($mission['hideouts'] as $hideout) {
            echo '<tr>';
            echo '<td>'.$hideout['code'].'</td>';
            echo '<td>'.$hideout['address'].'</td>';
            echo '<td>'.$hideout['type'].'</td>';
            echo '<td>'.$hideout['country']->getName().'</td>';
            echo '</tr>';
          }
        ?>
      </table>
      <?php 
        foreach($roles as $role) {
          echo '<div class="form-group">';
          echo '<label for="speciality">'.$role->getRole().'</label>';
          echo '<table class="border w-100">';
          foreach ($mission[str_replace(' ', '_', $role->getRole())] as $actor) {
            echo '  <tr>';
            echo '    <td>'.$actor['firstname'].'</td>';
            echo '    <td>'.$actor['lastname'].'</td>';
            echo '    <td>'.$actor['birthdate'].'</td>';
            echo '    <td>'.$actor['identificationCode'].'</td>';
            echo '    <td>'.$actor['country'].'</td>';
            echo '  </tr>';
          }
          echo'</table>';
        }
      ?>
    </div>
  </form>
</main>