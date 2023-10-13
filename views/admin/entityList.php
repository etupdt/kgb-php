<main class="container col">         
  <div class="row title">
    <h1><?php echo $nameEntity?></h1>
  </div>
  <div class="row">
    <?php require_once 'sidemenu.php' ?>
    <form action="/<?php echo $nameEntity; ?>" class="col-9 form">
      <div class="container">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end m-5">
          <button role="button" class="btn btn-outline-success">Ajouter</button>
        </div>
        <table class="table">
        <thead>
            <tr>
              
              <?php
                foreach ($fields as $field) {
                  if ($field['name'] === 'id') {
                    echo '<th scope="col">Id</th>';
                  } else {
                    echo '<th scope="col">';
                    echo $field['label'];
                  echo '</th>';
                  }
                }
              ?>
              <th scope="col"></th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php
              foreach ($rows as $row) {
                echo '<tr>';
                foreach ($fields as $field) {
                  if ($field['name'] === 'id') {
                    echo '<th scope="row">';
                      echo $row['id'];
                    echo '</th>';
                  } else {
                    echo '<td>';
                    switch ($field['type']) {
                      case "text" : {
                        echo $row[$field['name']];
                        break;
                      }
                      case "select" : {
                        // echo '<pre>';
                        // print_r($field['value']);
                        echo $field['value'][$row[$field['name']]];
                        break;
                      }
                      case "multiSelect" : {
                        echo count($row[$field['name']]);
                        break;
                      }
                    }
                    echo '</td>';
                  }
                }
                echo "<th scope='col' class='micro-th'><button 
                  class='btn micro-btn' 
                  formaction='/".$nameEntity."/d/".$row['id']."'
                ><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash micro' viewBox='0 0 16 16'>
                  <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z'/>
                  <path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z'/>
                </svg></button></th>";
                echo "<th scope='col' class='micro-th'><button 
                  class='btn micro-btn' 
                  formaction='/".$nameEntity."/u/".$row['id']."'
                ><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pen' viewBox='0 0 16 16'>
                  <path d='m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z'/>
                </svg></button></th>";
                echo '</tr>';
              }
            ?>
          </tbody>
        </table>
      </div>
    </form>
  </div>
</main>
