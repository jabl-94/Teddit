<?php 
  // Includiamo i file neccessari per la struttura della pagina
  include "includes/head.php";
  include "includes/header.php"; 
  include "includes/sidebar.php";
  include "includes/_tempotrascorso.php";
?>

<!-- Stileper il carousel e le media queries -->
<style>
.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: black;
}

@media only screen and (max-width: 768px) {
  #latestBlogs {
    display: none;
  }

}

@media only screen and (min-width: 768px) {
  #ad_mobile {
    display: none;
  }
}

</style>

<body class="bg-light">

<!-- Sezzione per il post -->
<div class="col-md-7 col-10 px-0">
    <section class="container-sm px-0">
    <div class="d-flex container-sm row px-0 bg-light">
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
                            <a class="mx-4" href="premium.php" aria-label="link to get premium"><img class="d-block mx-auto h-80 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" src="assets/getPremium.png" alt="get premium"></a>
                        </div>
                    </div>';
                }               
                $result->free();
            } else {
                // Se l'utente non e'loggato, viene mostrata la pubblicita' ma questo viene rimandato alla login.
                echo '
                <div class="container-sm row px-2 ms-0 me-2" id="ad_mobile" style="max-width: 90%; background-color: #87ceeb;" aria-label="Advertisement">
                    <div class="h-100 justify-content-center align-items-center">
                        <a class="mx-4" href="login.php" aria-label="link to login"><img class="d-block mx-auto h-80 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" src="assets/getPremium.png" alt="get premium"></a>
                    </div>
                </div>';
            
            } 
        ?>
        <div class="container-sm row" style="max-height: 100vh; overflow-y: auto;">
        <?php

            $items_per_page = 10; // Numero post per pagina

            if (isset($_GET['page']) && is_numeric($_GET['page'])) {
              $current_page = (int) $_GET['page'];
            } else {
              $current_page = 1;
            }
            
            $offset = ($current_page - 1) * $items_per_page;
            
            $result = $conn_db->query("SELECT * FROM post JOIN utente ON post.id_u = utente.id_user JOIN blog ON post.id_b = blog.id_blog ORDER BY id_post DESC LIMIT $items_per_page OFFSET $offset");
            
            // Prende il numero totale di item per calcolare il numero totale delle pagine
            $total_items_result = $conn_db->query("SELECT COUNT(*) AS total_count FROM post");
            $total_items = $total_items_result->fetch_assoc()['total_count'];
            
            // Verifica se la query ha avuto successo
            if ($result === false) {
                die('Errore nella query: ' . $conn_db->error);
            }

            // Controlla che ci siano post
            if ($result->num_rows > 0) {
                // Se non ci sono post, viene mostrata una pagina vuota dove c'e' un'immagine di not found
                while ($postRow = $result->fetch_assoc()) {
                    echo '<div class="my-1 col-12">
                            <div class="card" style="height: auto;">
                                <div class="card-body pt-1">
                                    <p class="card-text blog mb-3"><a href="blog.php?id_blog=' . urlencode($postRow['id_blog']) . '"><strong><small>'. 't/' . $postRow['title'] .'</small></strong></a><small><small>● '. tempoTrascorso($postRow['date_time']) .'</small></small></p>
                                    <p class="h4 card-title post"><a href="post.php?id_post=' . urlencode($postRow['id_post']) . '">'. $postRow['title_post'] .'</a></p>
                                    <!-- <p class="card-text">'. 'u/' . $postRow['username'] .'</p> -->
                                    <p class="card-text text-truncate mt-3">'. $postRow['text_post'] .'</p>
                                    
                                </div>';
                            
                        $id_p = $postRow['id_post'];
                        $youtube_link = $postRow['link'];
                        // Si controlla che ci sia un link YouTube o meno
                        if (!empty($youtube_link)) {
                            // Si controlla che il link rispechi il pattern di un link YouTube
                            preg_match('/v=([^&]+)/', $youtube_link, $matches);
                            $video_id = $matches[1];
                            
                            // Genera l'iframe del video YouTube
                            echo '
                            <div class="h-100 text-center mx-1 d-flex justify-content-center my-2 px-0">
                            <div class="ratio ratio-16x9" style="max-width: 90%;">
                                    <iframe src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </div>';
                        } else {
            
                            $subresult = $conn_db->query("SELECT path FROM image WHERE id_pst = '$id_p' ORDER BY id_image ASC");
                            if ($subresult->num_rows == 1) {
                            // Se c'e' solo un'immagine, viene mostrata semplicemente
                                $imageRow = $subresult->fetch_assoc();
                                echo '<div class="h-100 px-2">
                                        <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                                       </div>
                                       ';
                            } elseif ($subresult->num_rows > 1) {
                                
                            // Invece se c'e' piu' di un'immagine, queste vengono mostrate tramite Bootstrap carousel
                            echo '<div class="">
                                    <div id="car-'.$postRow['id_post'].'" class="carousel slide">
                                        <div class="carousel-inner" style="height: auto;">';
                            $firstImage = true; // Variabile per segnare che questa e' la prima immagine  
                            while($imageRow = $subresult->fetch_assoc()){
                                // Mostra ogni immagine
                                if($firstImage) {
                                    echo '<div class="carousel-item active h-100 px-2">
                                            <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                                        </div>';
                                    $firstImage = false; // Aggiorna la variabile per dire che le altre non sono la prima immagine
                                } else {
                                    echo '<div class="carousel-item h-100 px-2">
                                      <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                                    </div>';
                                }
                            }
                            // genera i controlli del carousello Bootstrap Next e Previous
                            echo '</div>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#car-'.$postRow['id_post'].'" data-bs-slide="prev">
                                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                      <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#car-'.$postRow['id_post'].'" data-bs-slide="next">
                                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                      <span class="visually-hidden">Next</span>
                                    </button><br>
                                    </div>
                                    </div>';
                            }
                    $subresult->free();
                    }
                    
                    // genera il footer delle card dei post
                    echo        '<div class="card-footer d-flex flex-column flex-sm-row align-items-center py-1">';
                    // Query per verificare se l'utente ha gia' votato il post o meno
                    // Genera le badges per mostrare il numero di upvote e commenti
                    if (isset($_SESSION['id_user'])) {
                        $id_user = $_SESSION['id_user'];
                        $id_pt = $postRow['id_post'];
                        $check = $conn_db->query("SELECT * FROM vote_post WHERE id_ur = $id_user AND id_pt = $id_pt");
                        if($check->num_rows == 0){               
                            
                            echo '
                            <div class="mb-2">
                                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1 like-count-badge" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'"><span class="like-count">'. $postRow['n_votes'] .'</span> upvotes</span>
                                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'">'. $postRow['n_comments'] .'  comments</span>
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-primary like-post me-2 mb-1" data-postid="'. $postRow['id_post'] .'" aria-label="add upvote">upvote</button>';
                            
                            } else {
                            
                            echo '
                            <div class="mb-2">
                                <span class="badge text-bg-warning border rounded-pill py-2 me-2 mb-1 like-count-badge" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'"><span class="like-count">'. $postRow['n_votes'] .'</span> upvotes</span>
                                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'">'. $postRow['n_comments'] .'  comments</span>
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-warning like-post me-2 mb-1" data-postid="'. $postRow['id_post'] .'" aria-label="remove upvote">upvoted</button>';
                            
                            }
                    } else {
                        echo '
                        <div class="mb-2">
                            <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1 like-count-badge" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'"><span class="like-count">'. $postRow['n_votes'] .'</span> upvotes</span>
                            <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'">'. $postRow['n_comments'] .'  comments</span>
                        </div>';
                    }
                    // controlla se l'utente ha gia' salvato il post o meno e genra gli appositi bottoni
                    if (isset($_SESSION['id_user'])) {
                        $id_user = $_SESSION['id_user'];
                        $id_p = $postRow['id_post'];
                        $check = $conn_db->query("SELECT * FROM saves WHERE id_a = $id_user AND id_po = $id_p");
                        if($check->num_rows == 0){               
                            
                            echo '<button type="button" class="btn btn-primary me-2 mb-1" id="save-post" data-postid="'. $postRow['id_post'] .'" aria-label="Save">Save post</button>
                            </div>';
                            
                            } else {
                            
                            echo '<button type="button" class="btn btn-success me-2 mb-1" id="save-post" data-postid="'. $postRow['id_post'] .'" aria-label="Unsave post">Saved</button>
                            </div>';
                            
                            }
                    }
                    echo '      
                            </div>
                        </div>';
                }
            }
            $result->free();
        ?>
        </div>
    </div>
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

    </section>
<!-- div col 7 -->
</div>



<!-- Sezzione del blog -->
<div class="col-3 px-0" id="latestBlogs">
    <section class="container-sm px-0">
        <div class="d-flex container-sm row px-0">
            <!-- Controllo se l'utente e' premium -->
            <?php
            if (isset($_SESSION['id_user'])) {
                $id_u = $_SESSION['id_user'];
                $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");
                
                // Controlla se la query ha avuto successo
                if ($result === false) {
                    die('Errore nella query: ' . $conn_db->error);
                }
                // Genera la pubblicita' desktop o no
                if ($result->num_rows > 0) {
                    echo '<div class="container-sm row px-2 mx-0 bg-light" style="max-height: 100vh; overflow-y: auto;">
                    <a class="btn btn-outline-secondary" role="button"><h3 class="latest">Latest blogs</h3></a>';
                } else {
                    echo '
                    <div class="container-sm bg-light">
                    <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="height: 25vh;">         
                        <img src="assets/ad-sq.gif" alt="Ad Image" class="m-auto img-fluid" style="height: 100%;">
                    </div>
                    </div>        
                    <div class="container-sm row px-2 mx-0 bg-light" style="max-height: 75vh; overflow-y: auto;">
                    <a class="btn btn-outline-secondary" role="button"><h3 class="latest">Latest blogs</h3></a>';
                }
                $result->free();
            } else {
                echo '
                <div class="container-sm bg-light;">
                <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="height: 25vh;">         
                    <img src="assets/ad-sq.gif" alt="Ad Image" class="m-auto img-fluid" style="max-height: 90%;">
                </div>
                </div> 
                <div class="container-sm row px-2 mx-0 bg-light" style="max-height: 75vh; overflow-y: auto;">
                <a class="btn btn-outline-secondary" role="button"><h3 class="latest">Latest blogs</h3></a>';
            }
            ?>   
            <?php
                    $result = $conn_db->query("SELECT * FROM blog JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat
                    ORDER BY id_blog DESC LIMIT 5");

                    // Controlla se la query ha avuto successo
                    if ($result === false) {
                        die('Errore nella query: ' . $conn_db->error);
                    }

                    // Itera sui risultati e genera una card per ogni blog
                    while ($blogRow = $result->fetch_assoc()) {
                        echo '<div class="card mx-1 my-1" style="max-width: 26rem; max-height: 20rem;">
                                <div class="row g-0">
                                <div class="col-4 d-flex justify-content-center align-items-center">
                                <img src="images/'.$blogRow['img'].'" class="card-img-top py-2" style="max-height: 7rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="blog image">
                                </div>
                                <div class="col-8">
                                <div class="card-body">
                                    <p class="card-text mb-1 category"><a href="category.php?id_cat=' . urlencode($blogRow['id_cat']) . '">'. $blogRow['name'] .'</a></p>
                                    <p class="h4 card-title text-truncate blog"><a href="blog.php?id_blog=' . urlencode($blogRow['id_blog']) . '">'. 't/' . $blogRow['title'] .'</a></p>
                                    <!-- <p class="card-text text-truncate">'. $blogRow['description'] .'</p> -->
                                    <p class="card-text text-truncate profile"><a href="profile.php?id_user=' . urlencode($blogRow['id_author']) . '">'. 'u/' . $blogRow['username'] .'</a></p>
                                    <div class="d-flex align-items-center">
                                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-1" id="'. $blogRow['id_blog'] .'" data-blogid="'. $blogRow['id_blog'] .'">'. $blogRow['n_followers'] .'  followers</span>
                                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-1">'. ($blogRow['n_coauthors']+1) .' authors</span>';
                                    echo   '</div>
                                            </div>
                                          </div>
                                        </div>
                                    </div>';
                    }
                    $result->free();
                    ?>
            </div>
        </div>
    </section>

<!-- /div for col-9 -->
</div>
<!-- /div row -->
</div>
<!-- /div for container -->
</div>

</body>

<?php
    $conn_db->close();
    include "includes/footer.php";
?>


</html>