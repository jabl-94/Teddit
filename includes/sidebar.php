<!-- media queries per l'alternanza desktop-mobile -->
<style>
@media only screen and (max-width: 768px) {
  #desktopSidebar {
    display: none;
  }
  #mobileSidebar {
    display: flex;
  }
}
@media only screen and (min-width: 768px) {
  #mobileSidebar {
    display: none;
  }
}

</style>


<div class="">
  <div class="row">
    <!-- sidebar mobile -->
      <div class="col-2 px-1 bg-body-tertiary" id="mobileSidebar">
      <div class="container-sm px-2 mb-auto">
        <div class="d-flex flex-column bg-body-tertiary" style="height: auto; width: auto;">
          <ul class="nav nav-pills nav-flush flex-column mb-auto text-center">
            <?php
              // condizione per controllare se l'utente e' loggato o meno per mostrare i bottoni 
              if (isset($_SESSION['id_user'])) {
                // pulsanti in ordine: crea blog, my blogs, following
                echo '
            <li class="nav-item">
              <a href="createblog.php" class="nav-link py-3 mx-auto border-bottom rounded-0" aria-current="page" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Create blog" data-bs-original-title="Home">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="blue" class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
              </svg>
              </a>
            </li>
            <li>
              <a href="myBlogs.php" class="nav-link py-3 mx-auto border-bottom rounded-0" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="My blogs" data-bs-original-title="Dashboard">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="blue" class="bi bi-journals" viewBox="0 0 16 16">
                <path d="M5 0h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2 2 2 0 0 1-2 2H3a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1H1a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v9a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1H3a2 2 0 0 1 2-2"/>
                <path d="M1 6v-.5a.5.5 0 0 1 1 0V6h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V9h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 2.5v.5H.5a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H2v-.5a.5.5 0 0 0-1 0"/>
              </svg>
              </a>
            </li>
            <li>
            <a href="following.php" class="nav-link py-3 mx-auto border-bottom rounded-0" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="Blogs you follow" data-bs-original-title="Orders">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="blue" class="bi bi-journal-bookmark" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8"/>
              <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
              <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
            </svg>
            </a>
          </li>';
              }
            ?>
            <!-- Bottone per le categorie viene generato sempre per permettere all'utente di trovare dei blog -->
            <li>
              <a href="categories.php" class="nav-link py-3 mx-auto border-bottom rounded-0" data-bs-toggle="tooltip" data-bs-placement="right" aria-label="category page" data-bs-original-title="Products">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="blue" class="bi bi-list-task" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2 2.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5V3a.5.5 0 0 0-.5-.5zM3 3H2v1h1z"/>
                <path d="M5 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M5.5 7a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1zm0 4a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1z"/>
                <path fill-rule="evenodd" d="M1.5 7a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5zM2 7h1v1H2zm0 3.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm1 .5H2v1h1z"/>
              </svg>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- desktop sidebar -->
    <div class="col-2 px-1 bg-light" id="desktopSidebar">

      <!-- Controllo se l'utente e' premium per mostrare la pubblicita' o meno-->
      <?php
      if (isset($_SESSION['id_user'])) {
          $id_u = $_SESSION['id_user'];
          $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");
          
          // Controlla se la query ha avuto successo
          if ($result === false) {
              die('Errore nella query: ' . $conn_db->error);
          }

          // Se l'utente non e' prmeium viene mostrata la pubblicita'
          if ($result->num_rows == 0) {
            echo '
            <div class="container-sm bg-light">
            <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="height: 25vh;" aria-label="advertisement">         
              <img src="assets/ad-sq.gif" alt="Ad Image" class="m-auto img-fluid" style="max-height: 90%;">
            </div>
            </div>
            <div class="container-sm px-0 bg-light" style="max-height: 75vh; overflow-y: auto;">';
          }  else {
            echo '<div class="container-sm px-0 bg-light" style="max-height: 100vh; overflow-y: auto;">';
          }              
          $result->free();
      // La pubblicita' viene mostrata se l'utente non e' loggato 
      } else { 
          echo '  
          <div class="container-sm bg-light">
          <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="height: 25vh;" aria-label="advertisement">         
            <img src="assets/ad-sq.gif" alt="Ad Image" class="m-auto img-fluid" style="max-height: 90%;">
          </div>
          </div>
          <div class="container-sm px-0 bg-light" style="max-height: 75vh; overflow-y: auto;">';

      }
      ?>   

      <div class="accordion" id="accordionPanelsStayOpenExample">
        <!-- Primo elemento dell'accordion -->
        <div class="accordion-item">
          <!-- Intestazione del primo elemento -->
          <h2 class="accordion-header" id="panelsStayOpen-headingOne">
            <!-- Pulsante per espandere/collassare il primo elemento -->
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
              <strong>My blogs</strong>
            </button>
          </h2>
          <!-- Corpo del primo elemento -->
          <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
            <div class="accordion-body cat_nav">
              <div class="list-group d-flex text-break">
              <?php
                  // Verifica se l'utente e' loggato
                  if(isset($_SESSION['id_user'])){
                      echo "<a href='createblog.php' class='list-group-item list-group-item-action list-group-item-info'>+   Create blog</a>";
                      // Esegui la query per ottenere i titoli dei blog
                      $id_autore = $_SESSION['id_user'];
                      $result = $conn_db->query("SELECT title, id_blog FROM blog WHERE id_author = '$id_autore' ORDER BY id_blog DESC");
                  
                      // Controlla se la query ha avuto successo
                      if ($result === false) {
                          die('Errore nella query: ' . $conn_db->error);
                      }
                    
                      // Itera sui risultati e crea un'ancora per ogni blog
                      while ($row = $result->fetch_assoc()) {
                        echo '<a href="blog.php?id_blog=' . urlencode($row['id_blog']) . '"class="list-group-item list-group-item-action list-group-item-info">' . $row['title'] . '</a>';
                      }
                      $result->free();
                  // mostra un pulsante che rimanda alla login se l'utente non e' loggato
                  } else {
                      echo "<a href='includes/_login.php' class='list-group-item list-group-item-action list-group-item-info'>Please <strong>log in</strong> to see your blogs</a>";
                  }
              ?>
                </div>
            </div>
          </div>
        </div>

        <!-- Secondo elemento dell'accordion -->
        <div class="accordion-item">
          <!-- Intestazione del secondo elemento -->
          <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
            <!-- Pulsante per espandere/collassare il secondo elemento -->
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
            <strong>Following</strong>
            </button>
          </h2>
          <!-- Corpo del secondo elemento -->
          <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingTwo">
            <div class="accordion-body">
              <div class="list-group d-flex text-break">
              <?php
                // Check if 'id_user' is set in the session
                if(isset($_SESSION['id_user'])){
                    // Esegui la query per ottenere i titoli dei blog
                    $id_auth = $_SESSION['id_user'];
                    $result = $conn_db->query("SELECT title, id_blog FROM blog JOIN follows ON follows.id_b = blog.id_blog WHERE id_auth = '$id_auth'");
                    
                    // Controlla se la query ha avuto successo
                    if ($result === false) {
                        die('Errore nella query: ' . $conn_db->error);
                    }
                    
                    // Verifica se l'utente segue dei blog
                    if ($result->num_rows > 0) {
                        // Itera sui risultati e crea un'ancora per ogni blog
                        while ($row = $result->fetch_assoc()) {
                            echo '<a href="blog.php?id_blog=' . urlencode($row['id_blog']) . '"class="list-group-item list-group-item-action list-group-item-info">' . $row['title'] . '</a>';
                        }
                    // Genera un messaggio che indica all'utente di seguire blog se non l'ha ancora fatto
                    } else {
                        echo "<p class='list-group-item list-group-item-action list-group-item-info'> Please <strong>follow a blog</strong> to see it here</p>";
                    }
                    $result->free();
                // Genera un pulsante che rimanda alla login se l'utente non e' loggato
                } else {
                    echo "<a href='includes/_login.php' class='list-group-item list-group-item-action list-group-item-info'>Please <strong>log in</strong> to follow a blog</a>";
                }
                ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Terzo elemento dell'accordion -->
        <div class="accordion-item">
          <!-- Intestazione del terzo elemento -->
          <h2 class="accordion-header" id="panelsStayOpen-headingThree">
            <!-- Pulsante per espandere/collassare il terzo elemento -->
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
              <strong>Categories</strong>
            </button>
          </h2>
          <!-- Corpo del terzo elemento -->
          <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingThree">
            <div class="accordion-body">
              <div class="list-group d-flex text-break">
                <?php
                // Esegui la query per ottenere le categorie principali
                $result = $conn_db->query("SELECT category.name, id_cat FROM category WHERE id_parent IS NULL");

                // Controlla se la query ha avuto successo
                if ($result === false) {
                    die('Errore nella query: ' . $conn_db->error);
                }

                // Itera sui risultati e crea un'ancora per ogni categoria madre
                while ($row = $result->fetch_assoc()) {
                  echo '<a href="category.php?id_cat=' . urlencode($row['id_cat']) . '"class="list-group-item list-group-item-action list-group-item-info">' . $row['name'] . '</a>';
                }
                $result->free();
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- div col-2 -->
</div>