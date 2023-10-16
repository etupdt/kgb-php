
<main class="container d-flex flex-column align-items-center">
  <h1 class="pt-3 m-0">Missions</h1>
  <?php 
    foreach ($missions as $mission) {
      require 'missionCard.php';
    }
  ?>
</main>