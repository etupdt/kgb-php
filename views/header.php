<header>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <a class="navbar-brand" href="#">KGB</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?php echo BASE_URL.'/';?>">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo BASE_URL.'/missions';?>">Missions</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Administration
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/pays";?>>Pays</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/planque";?>>Planques</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/acteur";?>>Acteurs</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/specialite";?>>Spécialités</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/typemission";?>>Types Mission</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/statut";?>>Statuts Mission</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/role";?>>Roles</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/mission";?>>Missions</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        <div class="ms-2">
          <button type="submit" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#loginpage">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
              <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
            </svg>
            denis-tavernier@wanadoo.fr
          </button>
          <?php require_once 'views/modals/login.php'; ?>
        </div>
      </div>
    </div>
  </nav>
</header>
