<?php 
  // Includiamo i file neccessari per la struttura della pagina
  include "includes/head.php";
  include "includes/header.php"; 
  include "includes/sidebar.php"; 
?>
<?php
// Verifica la provenienza della variable id_user
    if (isset($_GET['id_user'])) {
        $id_user = urldecode($_GET['id_user']);
      } elseif (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
      } else {
        echo "No user ID provided";
      }
?>

<!-- Stile per la versione mobile -->
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
    <div class="ps-4 pt-2 bg-light">
            <h2 class="mb-0">Following</h2>
    </div>
    <!-- pubblicita per diventare premium -->
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
                        <a class="mx-4" href="premium.php" aria-label="link for premium content"><img class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" src="assets/getPremium.png" alt="get premium"></a>
                    </div>
                </div>';
            }               
            $result->free();
        } else {
            // Se l'utente non e'loggato, viene mostrata la pubblicita' ma questo viene rimandato alla login.
            echo '
            <div class="container-sm row px-2 ms-0 me-2" id="ad_mobile" style="max-width: 90%; background-color: #87ceeb;" aria-label="Advertisement">
                <div class="h-100 justify-content-center align-items-center">
                    <a class="mx-4" href="login.php" aria-label="link to login"><img class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" src="assets/getPremium.png" alt="get premium"></a>
                </div>
            </div>';

        } 
    ?>

<section class="container-sm ps-0 pe-0 g-0 my-0 py-0">
        <div class="post_section d-flex flex-column container-sm px-0 my-0 py-0 bg-light" style="max-height: 88vh; overflow-y: auto;">
        <?php
                $items_per_page = 10; // numero di pagine

                if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                  $current_page = (int) $_GET['page'];
                } else {
                  $current_page = 1;
                }

                $offset = ($current_page - 1) * $items_per_page;

                // Prende il numero totale di item per calcolare il numero totale delle pagine
                $total_items_result = $conn_db->query("SELECT COUNT(*) AS total_count FROM blog JOIN follows ON follows.id_b = blog.id_blog WHERE id_auth = '$id_user'");
                $total_items = $total_items_result->fetch_assoc()['total_count'];


                $result = $conn_db->query("SELECT * FROM blog 
                JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat JOIN follows ON follows.id_b = blog.id_blog
                WHERE id_auth = '$id_user' ORDER BY blog.id_blog DESC LIMIT $items_per_page OFFSET $offset");

                // Controlla se la query ha avuto successo
                if ($result === false) {
                    die('Errore nella query: ' . $conn_db->error);
                }
                
                // se l'utente non segue nessun blog, viene mostrata la schermata di 'nothing found'
                if ($result->num_rows == 0) {
                  echo '  <div class="d-flex flex-column justify-content-center align-items-center" style="height: 89vh;" aria-label="Nothing to see here yet">
                              <h1 class="mx-auto mt-auto">Nothing to see here yet...</h1>
                              <img src="assets/fine.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2" style="max-height: 15rem; object-fit: contain;" alt="">
                          </div>';
                }

                    // Genera le card per ogni blog che viene seguito
                    while ($blogRow = $result->fetch_assoc()) {
                        echo '<div class="card d-flex flex-column mb-1 my-3 mx-3" style="max-width: 70rem;">
                                <div class="row g-0">
                                <div class="col-2 d-flex justify-content-center align-items-center m-auto">
                                        <img src="images/'.$blogRow['img'].'" class="card-img-top py-0" style="max-height: 6rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="blog image">
                                </div>
                                <div class="col-md-8 col-5">
                                    <div class="card-body pe-0" style="position: relative;">
                                        <h5 class="card-title blog"><a href="blog.php?id_blog=' . urlencode($blogRow['id_blog']) . '" class="stretched-link">'. 't/' . $blogRow['title'] .'</a></h5>
                                        <p class="card-text text-truncate mb-2 profile" style="position: relative; z-index: 2;"><a class="post_card" href="profile.php?id_user=' . urlencode($blogRow['id_author']) . '">'. 'u/' . $blogRow['username'] .'</a></p>
                                        <p class="card-text text-truncate blog_description">'. $blogRow['description'] .'</p>
                                    </div>
                                </div>
                                <div class="col-md-2 col-5 py-0">
                                <div class="card-body text-end">
                                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2" id="'. $blogRow['id_blog'] .'" data-blogid="'. $blogRow['id_blog'] .'">'. $blogRow['n_followers'] .'  followers</span>
                                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-4">'. ($blogRow['n_coauthors']+1) .' authors</span>';

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
                                                        <button type="button" class="btn btn-success follow-blog" data-blogid="'. $blogRow['id_blog'] .'" aria-label="unfollow">Following</button>
                                                    </div>';
                                        }
                                    }
                                } else {
                                    echo    '<div class="">
                                                <button type="button" class="btn btn-primary log-in" data-blogid="'. $blogRow['id_blog'] .'">Follow blog</button>
                                            </div>';
                                }
                                echo   '</div>
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
                  echo '<li class="page-item"><a class="page-link" href="?id_user='. urlencode($id_user) .'&page=' . ($current_page - 1) . '" aria-label="Previous">';
                  echo '<span aria-hidden="true">&laquo;</span></a></li>';
                }
            
                // Itera e genera il numero di pagine
                for ($i = 1; $i <= $total_pages; $i++) {
                  $active_class = ($i == $current_page) ? ' active' : '';
                  echo '<li class="page-item' . $active_class . '"><a class="page-link" href="?id_user='. urlencode($id_user) .'&page=' . $i . '">' . $i . '</a></li>';
                }
            
                // Bottone pagina seguente (se non si e' nell'ultima pagina)
                if ($current_page < $total_pages) {
                  echo '<li class="page-item"><a class="page-link" href="?id_user='. urlencode($id_user) .'&page=' . ($current_page + 1) . '" aria-label="Next">';
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

                    // Mostra la pubblicita' se l'utente non e' premium
                    if ($result->num_rows == 0) {
                        echo '
                        <div class="container-sm" style="max-height: 100vh; overflow-y: auto; background-color: white;">
                        <div class="d-flex flex-column justify-content-center align-items-center" style="max-height: 100vh; overflow-y: auto; background-color: white;">           
                            <img src="assets/ad-v.gif" alt="Ad Image" class="m-auto img-fluid" style="height: 100%;">
                        </div>';
                    }                
                    $result->free();
                } else {
                  // Mostra la pubblicita' se l'utente non e' loggato
                    echo '
                    <div class="container-sm" style="max-height: 100vh; overflow-y: auto; background-color: white;">
                    <div class="d-flex flex-column justify-content-center align-items-center" style="max-height: 100vh; overflow-y: auto; background-color: white;">           
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