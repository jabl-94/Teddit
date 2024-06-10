<?php 
  // Inclusione del file per la connessione al database
  include "includes/Connessione_database.php"; 

  // Inclusione del file di configurazione
  require_once "includes/config.php";
?>

<!-- Stile per la responsiveness -->
<style>
#search-box {
    position: relative;
}

#suggestion-box {
    position: absolute;
    z-index: 999;
    max-height: 200px;
    overflow-y: auto;
    background-color: white;
    border: 1px solid #ccc;
    width: 100%;
    top: 100%;
}

#autocomplete-list {
    list-style-type: none;
    padding-left: 5px;
}

@media only screen and (max-width: 768px) {
  #logo span {
    display: none;
  }
}
</style>

<header>
    <!-- Creazione della barra di navigazione -->
    <div class="row">
    <nav class="navbar navbar-light bg-light">
      <!-- Logo del sito -->
        <div class="col-md-2 col-3"> 
            <a class="navbar-brand my-0 pt-2 mx-4" id="logo" href="home.php">
              <img class="mb-2" src="icon/Teddit.png" alt="" width="60rem" height="60rem">
              <span>Teddit</span>
            </a>
        </div>

        <!-- Form di ricerca -->
        <div class="col-md-4 col-9"> 
            <form action="search_results.php" method="GET" class="d-flex position-relative" >
                <!-- Campo di input per la ricerca -->
                <input id="search-box" class="form-control" type="search" placeholder="Search..." aria-label="Search" required="yes" name="search" type="text">
                <!-- Pulsante di ricerca -->
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                <div id="suggestion-box"></div>
            </form>
        </div>


        <!-- Pulsante di login -->
        <div class="col-md-2 col-12 text-end">
                  <?php
                      // Si verifica se l'utente e' loggato o meno
                      if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']){
                        $id = $_SESSION['id_user'];
                      ?>
                        <div class="btn-group" role="group" aria-label="profile and logout buttons">
                        </div>
                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-label="profile button"><strong><?php echo $_SESSION['username']?></strong>   <img class="profile_pic" alt="propic" style="max-height: 4rem; clip-path: circle(40%);" src="propics/<?php echo $_SESSION['propic']?>"></button>
                        <ul class="dropdown-menu dropdown-menu-end text-start">
                          <li><a class="dropdown-item" href="profile.php?id_user=<?php echo urlencode($_SESSION['id_user']) ?>" aria-label="view profile">View profile</a></li>

                          <!-- Query per verificare se l'utente e' premium o no -->
                          <?php
                          $result = $conn_db->query("SELECT * FROM utente 
                          JOIN premium ON utente.id_user = premium.id_premium 
                          WHERE utente.id_user = '$id'");

                          // Controlla se la query ha avuto successo
                          if ($result === false) {
                              die('Errore nella query: ' . $conn_db->error);
                          }
                          
                          // Cambia l'opzione da mostrare a seconda del risultato
                          if ($result->num_rows > 0) {
                            echo '<li><a class="dropdown-item" href="premium.php?id_user=' . urlencode($id) . '" aria-label="premium">Manage subscription</a></li>';
                        } else {
                            echo '<li><a class="dropdown-item" href="premium.php?id_user=' . urlencode($id) . '" aria-label="get premium">GET PREMIUM!</a></li>';
                        }
                          $result->free();
                          ?>
                          <li><a class="dropdown-item" href="delete_profile.php" aria-label="settings">Settings</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item" href="includes/_logout.php" aria-label="logout button">Log out</a></li>
                        </ul>
                      <?php
                      }  
                      else{
                      ?>
                          <button onclick="window.location.href='includes/_login.php'" type='button' class='btn btn-primary me-2'>Login</button>
                      <?php
                      }
                      ?>
          </div>
        </div>
    </nav>


<!-- jQuery AJAX per l'autocomplete della ricerca -->
<script>
  $(document).ready(function() {
      $("#search-box").keyup(function() {
          $.ajax({
              type: "POST",
              url: "includes/_autocomplete.php",
              data: 'keyword=' + $(this).val(),
              success: function(data) {
                  $("#suggestion-box").show();
                  $("#suggestion-box").html(data);
              }
          });
      });
  });
  function selectResult(val) {
      $("#search-box").val(val);
      $("#suggestion-box").hide();
  }
</script>

</header>