<?php 
  // Includiamo i file neccessari per la struttura della pagina
  include "includes/head.php";
  include "includes/header.php"; 
  include "includes/sidebar.php"; 
?>
<?php
    // Controlliamo se la pagina riceve 'id_user' tramite GET e si estrae il nome
      $id_c = urldecode($_GET['id_cat']);
    $result = $conn_db->query("SELECT * FROM category WHERE id_cat = '$id_c'");
    while($row = $result->fetch_assoc()){
        $name = $row["name"];
    }
    $result->free();
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
    <div class="ps-4 pt-2 bg-light"">
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

    <div class="d-flex flex-wrap gap-1 justify-content-center py-1 me-0 ms-0 bg-light">
        <?php            
            $result = $conn_db->query("SELECT * FROM category WHERE id_cat = '$id_c' OR id_parent = '$id_c' ORDER BY id_cat ASC");
            // Controlla se la query ha avuto successo
            if ($result === false) {
                die('Errore nella query: ' . $conn_db->error);
            }
            
            // Itera sui risultati e crea un pulsante per ogni categoria e le sue sottocategorie
            while ($row = $result->fetch_assoc()) {
                $activeClass = $row['id_cat'] == $id_c ? 'active' : '';
                echo '<div class="btn py-0 px-1" aria-label="">
                        <button onclick="changeCategory(' . urlencode($row['id_cat']) . ')" class="btn btn-outline-info d-inline-flex justify-content-center ' . $activeClass . '" type="button"><strong>'. $row['name'] .'</strong></button>
                      </div>';
            }
            $result->free();
        ?>
    </div>            

    <section class="container-sm ps-0 pe-0 g-0 my-0 py-0">
        <div class="post_section d-flex flex-column container-sm px-0 my-0 py-0 bg-light" style="max-height: 88vh; overflow-y: auto;">
        <?php
                $result = $conn_db->query("SELECT * FROM blog 
                JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat
                WHERE category.id_parent = '$id_c' OR category.id_cat = '$id_c' ORDER BY blog.id_blog DESC");

                // Controlla se la query ha avuto successo
                if ($result === false) {
                    die('Errore nella query: ' . $conn_db->error);
                }

                    // Itera sui risultati e genera una card per ogni blog
                    while ($blogRow = $result->fetch_assoc()) {
                        echo '<div class="card d-flex flex-column mb-1 my-3 mx-3" style="max-width: 70rem;">
                                <div class="row g-0">
                                <div class="col-2 d-flex justify-content-center align-items-center m-auto">
                                        <img src="images/'.$blogRow['img'].'" class="card-img-top py-0" style="max-height: 6rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="blog image">
                                </div>
                                <div class="col-md-8 col-6">
                                    <div class="card-body" style="position: relative;">
                                        <h5 class="card-title blog"><a href="blog.php?id_blog=' . urlencode($blogRow['id_blog']) . '" class="stretched-link">'. 't/' . $blogRow['title'] .'</a></h5>
                                        <p class="card-text text-truncate mb-2 profile" style="position: relative; z-index: 2;"><a class="post_card" href="profile.php?id_user=' . urlencode($blogRow['id_author']) . '">'. 'u/' . $blogRow['username'] .'</a></p>
                                        <p class="card-text text-truncate blog_description">'. $blogRow['description'] .'</p>
                                    </div>
                                </div>
                                <div class="col-md-2 col-4 py-0">
                                <div class="card-body text-end">
                                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2" id="'. $blogRow['id_blog'] .'" data-blogid="'. $blogRow['id_blog'] .'">'. $blogRow['n_followers'] .'  followers</span>
                                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-4">'. ($blogRow['n_coauthors']+1) .' authors</span>';

                                // Si controlla se l'utente NON e' l'autore del blog per generare i bottoni "Follow blog" e "Following"
                                // Questi non vengono generati se l'utente E' l'autore del blog generato
                                if (isset($_SESSION['id_user'])) {
                                    if ($blogRow['id_author'] != $_SESSION['id_user']) {
                                        $id_user = $_SESSION['id_user'];
                                        $id_b = $blogRow['id_blog'];
                                        $check = $conn_db->query("SELECT * FROM follows WHERE id_auth = $id_user AND id_b = $id_b");
                                        if($check->num_rows == 0){
                                            echo '<div class="">
                                                    <button type="button" class="btn btn-primary follow-blog" data-blogid="'. $blogRow['id_blog'] .'">Follow blog</button>
                                                  </div>';
                                        } else {
                                            echo    '<div class="">
                                                        <button type="button" class="btn btn-success follow-blog" data-blogid="'. $blogRow['id_blog'] .'">Following</button>
                                                    </div>';
                                        }
                                    }
                                } else {
                                    echo    '<div class="">
                                                <button type="button" class="btn btn-primary log-in" data-blogid="'. $blogRow['id_blog'] .'">Follow blog</button>
                                                <div class="text-start">
                                                    <span class="login-message text-danger"></span>
                                                </div>
                                            </div>';
                                }
                                echo   '</div>
                                        </div>
                                      </div>
                                    </div>';
                    }
                    $result->free();
                ?>
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

                    // Genera la pubblicita' desktop se l'utente non e' premium
                    if ($result->num_rows == 0) {
                        echo '
                        <div class="container-sm bg-light" style="max-height: 100vh; overflow-y: auto;">
                        <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="max-height: 100vh; overflow-y: auto;" aria-label="ad">           
                            <img src="assets/ad-v.gif" alt="Ad Image" class="m-auto img-fluid" style="height: 100%;">
                        </div>';
                    }                
                    $result->free();
                } else {
                    // Genera la pubblicita' desktop se l'utente non e' loggato
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
<!-- Script per generare dinamicamente la pagina dove vengono mostrati i blog della categoria selezionata -->
<script>
        function changeCategory(id_cat) {
            // Rimuove la classe 'active' da tutti i bottoni
            var buttons = document.querySelectorAll('.btn-outline-info');
            buttons.forEach(function(button) {
                button.classList.remove('active');
            });

            // Aggiunge la classe 'active' al bottone premuto
            var clickedButton = document.querySelector('button[onclick="changeCategory(' + id_cat + ')"]');
            clickedButton.classList.add('active');

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                // se la response e' ok, viene mostrato il risultato nel elemento con la classe '.post_section'
                if (this.readyState == 4 && this.status == 200) {
                    document.querySelector(".post_section").innerHTML = this.responseText;
                }
            };
            // chiamata alla pagina backend con l'idi della categoria selezionata tramite il bottone
            xhttp.open("GET", "includes/_change_cat.php?id_cat=" + id_cat, true);
            xhttp.send();
        }
    </script>

</body>

<?php
    $conn_db->close();
    include "includes/footer.php"; 
?>

</html>