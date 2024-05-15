<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta name="docsearch:language" content="fr">
  <title>KGB</title>
  <link href="/views/css/style.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
  <script src="/views/js/script.js" crossorigin="anonymous"></script>
  <?php if (isset($script)) echo '<script src="/views/js/control'.ucfirst($nameEntity).'.js" crossorigin="anonymous"></script>'; ?>
</head>
<body>

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
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/actor";?>>Acteurs</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/country";?>>Pays</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/hideout";?>>Planques</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/role";?>>Roles</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/speciality";?>>Spécialités</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/statut";?>>Statuts Mission</a></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/typemission";?>>Types Mission</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href=<?php echo BASE_URL.ADMIN_URL."/mission";?>>Missions</a></li>
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
          <?php require_once $_SERVER['DOCUMENT_ROOT'].'views/modals/login.php'; ?>
        </div>
      </div>
    </div>
  </nav>
</header>
