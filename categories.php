<?php 
  // Includiamo i file neccessari per la struttura della pagina
  include "includes/head.php";
  include "includes/header.php"; 
  include "includes/sidebar.php"; 
?>
<?php
  // Controlliamo se la pagina riceve 'id_user' tramite GET o SESSION
    if (isset($_GET['id_user'])) {
        $id_user = urldecode($_GET['id_user']);
      } elseif (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
      }
?>


<!-- Media query per responsiveness -->
<style>
    @media only screen and (max-width: 768px) {
  .blog_description {
    display: none;
  }

  #ad_cat {
    display: none;
  }

  #ad_mobile {
    display: flex;
  }
}

@media only screen and (min-width: 769px) {
  #ad_mobile {
    display: none;
  }
}
</style>

<body class="bg-light">
<div class="col-md-8 col-10 px-0">
    <!-- Nome della category -->
    <div class="ps-4 pt-2 bg-light">
            <h2 class="mb-0">Categories</h2>
    </div>
    <!-- pubblicita premium -->
    <?php
        if (isset($_SESSION['id_user'])) {
            $id_u = $_SESSION['id_user'];
            $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");

            // Controlla se la query ha avuto successo
            if ($result === false) {
                die('Errore nella query: ' . $conn_db->error);
            }

            // Se non c'e' l'utente nella tabella premium, viene mostrata la pubblicita' per diventarlo
            if ($result->num_rows == 0) {
                echo '
                <div class="container-sm row px-2 ms-0 me-2" id="ad_mobile" style="max-width: 90%; background-color: #87ceeb;" aria-label="Advertisement">
                    <div class="h-100 justify-content-center align-items-center">
                        <a class="mx-4" href="premium.php" aria-label="Link to Premium Membership"><img class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" src="assets/getPremium.png" alt="get premium"></a>
                    </div>
                </div>';
            }               
            $result->free();
        } else {
            // Se l'utente non e'loggato, viene mostrata la pubblicita' ma questo viene rimandato alla login.
            echo '
            <div class="container-sm row px-2 ms-0 me-2" id="ad_mobile" style="max-width: 90%; background-color: #87ceeb;" aria-label="Advertisement">
                <div class="h-100 justify-content-center align-items-center">
                    <a class="mx-4" href="login.php" aria-label="Link to login"><img class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" src="assets/getPremium.png" alt="get premium"></a>
                </div>
            </div>';

        } 
    ?>

<section class="container-sm ps-0 pe-0 g-0 my-0 py-0">
        <div class="post_section d-flex flex-column container-sm px-0 my-0 py-0 bg-light" style="max-height: 88vh; overflow-y: auto;">
        <?php
                $items_per_page = 15; // limite di pagine per pagina

                // controlla se la variabile 'page' e' stata inviata tramite GET per andare avanti con le seguenti pagine
                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                  $current_page = (int) $_GET['page'];
                } else {
                  $current_page = 1;
                }

                $offset = ($current_page - 1) * $items_per_page;

                // Prende il numero totale di item per calcolare il numero totale delle pagine
                $total_items_result = $conn_db->query("SELECT COUNT(*) AS total_count FROM category");
                $total_items = $total_items_result->fetch_assoc()['total_count'];


                $result = $conn_db->query("SELECT * FROM category ORDER BY id_cat ASC LIMIT $items_per_page OFFSET $offset");

                // Controlla se la query ha avuto successo
                if ($result === false) {
                    die('Errore nella query: ' . $conn_db->error);
                }

                    // Itera sui risultati e crea un bottone per ogni categoria e le sue sottocategorie
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="card d-flex flex-column mb-1 my-3 mx-3" style="max-width: 70rem;">
                        <div class="row g-0">
                            <div class="col-12">
                                <div class="card-body">
                                    <h5 class="card-title text-truncate category"><a class="stretched-link" href="category.php?id_cat=' . urlencode($row['id_cat']) . '">'. $row['name'] .'</a></h5>
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
                    $result->free();
                ?>
        <div class="d-flex flex-column justify-content-center align-items-center">
            <?php
                $total_pages = ceil($total_items / $items_per_page);
                        
                echo '<nav aria-label="Page navigation example">';
                echo '<ul class="pagination">';
                        
                // Bottone pagina precedente (se non si e' nella prima pagina)
                if ($current_page > 1) {
                  echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page - 1) . '" aria-label="Previous">';
                  echo '<span aria-hidden="true">&laquo;</span></a></li>';
                }
            
                // Itera e genera il numero di pagine
                for ($i = 1; $i <= $total_pages; $i++) {
                  $active_class = ($i == $current_page) ? ' active' : '';
                  echo '<li class="page-item' . $active_class . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                }
            
                // Bottone pagina seguente (se non si e' nell'ultima pagina)
                if ($current_page < $total_pages) {
                  echo '<li class="page-item"><a class="page-link" href="?page=' . ($current_page + 1) . '" aria-label="Next">';
                  echo '<span aria-hidden="true">&raquo;</span></a></li>';
                }
            
                echo '</ul>';
                echo '</nav>';
                ?>
        </div>
        </div>
    </section>


<!-- /div for col-8 -->
</div>
<div class="col-2" id="ad_cat">
            <!-- Controllo se l'utente e' premium -->
            <?php
                if (isset($_SESSION['id_user'])) {
                    $id_u = $_SESSION['id_user'];
                    $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");
                    
                    // Controlla se la query ha avuto successo
                    if ($result === false) {
                        die('Errore nella query: ' . $conn_db->error);
                    }

                    // Itera sui risultati e genera la pubblicita' se l'utente non e' premium
                    if ($result->num_rows == 0) {
                        echo '
                        <div class="container-sm bg-light" style="max-height: 100vh; overflow-y: auto;">
                        <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="max-height: 100vh; overflow-y: auto;" aria-label="ad">           
                            <img src="assets/ad-v.gif" alt="Ad Image" class="m-auto img-fluid" style="height: 100%;">
                        </div>';
                    }                
                    $result->free();
                } else {
                    // Genera la pubblicita' se l'utente non e' loggato
                    echo '
                    <div class="container-sm bg-light" style="max-height: 100vh; overflow-y: auto;">
                    <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="max-height: 100vh; overflow-y: auto;" aria-label="ad">           
                        <img src="assets/ad-v.gif" alt="Ad Image" class="m-auto img-fluid" style="height: 100%;">
                    </div>';

                }
                ?>         
<!-- /div for container -->
</div>
</div>
</body>

<?php
  $conn_db->close();
  include "includes/footer.php"; 
?>

</html>