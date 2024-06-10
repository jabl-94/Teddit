<?php 
  // Includiamo i file neccessari per la struttura della pagina
  include "includes/head.php";
  include "includes/header.php"; 
  include "includes/sidebar.php"; 
  include "includes/_tempotrascorso.php"; 

  // Controlliamo se la pagina riceve 'id_blog' tramite GET o SESSION
  if (isset($_GET['id_blog'])) {
    $id_b = urldecode($_GET['id_blog']);
  } elseif (isset($_SESSION['id_blog'])) {
    $id_b = $_SESSION['id_blog'];
  } else {
    die("No blog ID provided.");
  }

  // Si fa una query al database per ottenere i dati del blog
  $result = $conn_db->query("SELECT * FROM blog 
  JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat
  WHERE id_blog = '$id_b'");
  while($row = $result->fetch_assoc()){
    // Assegniamo le chiavi che ci servono a delle variabili.
    $titolo = $row['title'];
    $descrizione = $row['description'];
    $immagine = $row['img'];
    $autore = $row['id_author'];
    $num_followers = $row['n_followers'];
    $categoria = $row['name'];
    $num_coauthors = $row['n_coauthors'];
    $category_id = $row['id_cat'];
    $nome_autore = $row['username'];
  }
  $result->free();
?>

<style>
.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: black;      
}

/* Media query per responsiveness */
@media only screen and (max-width: 768px) {
  #blog_panel {
    display: none;
  }
  #ad_mobile {
    display: flex;
  }

  .blog_cat {
    display: none;
  }

  #blog_card {
    display: flex;
  }

}

@media only screen and (min-width: 769px) {
  #ad_mobile {
    display: none;
  }

  #blog_card {
    display: none;
  }
}

</style>

<body class="bg-light">


<!-- div per la pubblicita del servizio premium -->
<div class="col-md-8 col-10 px-0 mx-0 bg-light">

        <!-- Si controlla se l'utente e' premium o meno -->
        <?php
        if (isset($_SESSION['id_user'])) {
            $id_u = $_SESSION['id_user'];
            $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");

            // Si controlla se la query ha avuto successo
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


    <!-- Sezione per visualizzare i post -->
    <section class="container-sm px-0 mx-0">
    <div class="d-flex container-sm row px-0 bg-light">
        <div class="container-sm row" style="max-height: 100vh; overflow-y: auto;">
        
        <!-- Card del blog (versione mobile) -->
        <div class="my-1 col-12" id="blog_card">
            <div class="card d-flex flex-column mb-1 my-3 mx-0" style="height: auto">
            <div class="row g-0">
            <div class="col-3 d-flex justify-content-center align-items-center">
                <img src="images/<?php echo $immagine; ?>" class="card-img-top py-0" style="max-height: 6rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="blog image">
            </div>
            <div class="col-9">
                <div class="card-body" style="position: relative;">
                        <h5 class="card-title mb-0">t/<?php echo $titolo; ?></h5>
                        <p class="card-text text-truncate mb-0" style="position: relative; z-index: 2;"><a class="post_card" href="profile.php?id_user=<?php echo urlencode($autore) ?>">u/<?php echo $nome_autore; ?></a></p>
                        <p class="card-text text-truncate blog_description"><?php echo $descrizione; ?><</p>
                    <div>
                        <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2 category blog_cat"><a href="category.php?id_cat=' . urlencode($blogRow['id_cat']) . '"><?php echo $categoria; ?></a></span>
                    </div>
                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-1 <?php echo $id_b; ?>" data-blogid="<?php echo $id_b; ?>"><?php echo $num_followers; ?>  followers</span>
                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-1"><?php echo $num_followers + 1 ; ?> authors</span>
            
            <!-- Query al database per vedere se l'utente segue il blog o meno -->
            <?php
            if (isset($_SESSION['id_user'])) {
                if ($autore != $_SESSION['id_user']) {
                    $id_user = $_SESSION['id_user'];
                    $id_bl = $id_b;
                    $check = $conn_db->query("SELECT * FROM follows WHERE id_auth = $id_user AND id_b = $id_bl");
                    if($check->num_rows == 0){
                        echo '<div class="px-0">
                                <button type="button" class="btn btn-primary follow" data-blogid="'. $id_b .'">Follow blog</button>
                              </div>';
                    } else {
                        echo    '<div class="px-0">
                                    <button type="button" class="btn btn-success follow" data-blogid="'. $id_b .'">Following</button>
                                </div>';
                    }
                }
            } else {
              // Se questo non e' loggato, gli viene chiesto di loggarsi prima
                echo    '<div class="px-0">
                            <button type="button" class="btn btn-primary log-in" data-blogid="'. $id_b .'">Follow blog</button>
                            <div class="text-start">
                              <span class="login-message text-danger"></span>
                            </div>
                        </div>';
            }
            // Verifica se 'id_user' è settato nella sessione e se è co-autore del blog per premettere di creare dei post
            $result = $conn_db->query("SELECT id_aut FROM co_manages WHERE id_bl = '$id_b'");
            while($row = $result->fetch_assoc()) {
                if(isset($_SESSION['id_user']) && ($_SESSION['id_user'] == $row['id_aut'])) {
                        ?>
                        <div class="btn pb-2 px-0" role="group" aria-label="Create a post">
                            <a href="create_post.php?id_blog=<?php echo urlencode($id_b); ?>" class="btn btn-dark">Create a post</a>            
                        </div><?php
                }
            }
            $result->free();
            // Verifica se 'id_user' è settato nella sessione e se è l'autore del blog
            // Se si, da permessi di gestione del blog
            if(isset($_SESSION['id_user']) && ($_SESSION['id_user'] == $autore)){
            ?>
            <div class="px-0">
                <div class="btn pb-2 px-0" role="group" aria-label="Create a post">
                    <a href="create_post.php?id_blog=<?php echo urlencode($id_b); ?>" class="btn btn-dark">Create a post</a>            
                </div>
                <div class="btn-group" role="group" aria-label="Edit and delete">
                    <a href="edit_blog.php?id_blog=<?php echo urlencode($id_b); ?>" aria-label="Edit" type="button" class="btn btn-success">Edit</a>
                    <!-- Bottone per attivare il modal -->
                    <a type="button" class="btn btn-danger" aria-label="Delete blog mobile" data-bs-toggle="modal" data-bs-target="#delete_blog_mobile">Delete</a>
                    <!-- modal per confermare l'eliminazione -->
                    <div class="modal fade" id="delete_blog_mobile" tabindex="-1" aria-labelledby="delete_blog_mobile?" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Delete blog?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-center">
                            <p>Are you sure you want to delete your blog?</p>
                            <p> This <strong>cannot</strong> be undone.</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <a href="includes/_delete.php?id=<?php echo urlencode($id_b); ?>&type=<?php echo urlencode('blog'); ?>" aria-label="Confirm blog deletion" type="button" class="btn btn-danger">Delete</a>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>

              <?php 
            } echo '
            </div>
            </div>
            </div>
            </div>
            </div>';?>

        
        
        <!-- card dei post -->
        <?php
        $items_per_page = 10; // limite di pagine per pagina
        
        // controlla se la variabile 'page' e' stata inviata tramite GET per andare avanti con le seguenti pagine
        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            $current_page = (int) $_GET['page'];
          } else {
            $current_page = 1;
          }
          
          $offset = ($current_page - 1) * $items_per_page;
          
          $result = $conn_db->query("SELECT * FROM post JOIN utente ON post.id_u = utente.id_user JOIN blog ON post.id_b = blog.id_blog WHERE blog.id_blog = $id_b ORDER BY id_post DESC LIMIT $items_per_page OFFSET $offset");
          
          // Prende il numero totale di item per calcolare il numero totale delle pagine
          $total_items_result = $conn_db->query("SELECT COUNT(*) AS total_count FROM post WHERE id_b = $id_b");
          $total_items = $total_items_result->fetch_assoc()['total_count'];

        // Controlla se la query ha avuto successo
        if ($result === false) {
            die('Errore nella query: ' . $conn_db->error);
        }
        // Se non ci sono post, viene mostrata una pagina vuota dove c'e' un'immagine di not found
        if ($result->num_rows == 0) {
            echo '  <div class="d-flex flex-column justify-content-center align-items-center" style="height: 89vh;" aria-label="Nothing to see here yet">
                        <h1 class="mx-auto mt-auto">Nothing to see here yet...</h1>
                        <img src="assets/fine.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2" style="max-height: 15rem; object-fit: contain;" alt="">
                    </div>';
        }

        // Controlla che ci siano post
        if ($result->num_rows > 0) {
           // Itera l'array associativo e genera le card dei post
            while ($postRow = $result->fetch_assoc()) {  
                echo '<div class="my-1 col-12">
                <div class="card" style="height: auto">
                    <div class="card-body pt-1">
                        <p class="card-text profile mb-3"><a href="profile.php?id_user=' . urlencode($postRow['id_user']) . '" aria-label="Post author"><strong><small>'. 'u/' . $postRow['username'] .'</small></strong></a><small><small>  ● '. tempoTrascorso($postRow['date_time']) .'</small></small></p>
                        <p class="h4 card-title post"><a href="post.php?id_post=' . urlencode($postRow['id_post']) . '" aria-label="Post title">'. $postRow['title_post'] .'</a></p>
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
                        <div class="ratio ratio-16x9" style="max-width: 90%;" aria-label="Youtube video">
                            <iframe src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>';
                } else { 
                        // si controlla che ci siano immagini nel post
                        $subresult = $conn_db->query("SELECT `path` FROM `image` WHERE id_pst = '$id_p' ORDER BY id_image ASC");
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
                                  <span class="visually-hidden" aria-label="previous">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#car-'.$postRow['id_post'].'" data-bs-slide="next">
                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden" aria-label="next">Next</span>
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
                    <button type="button" class="btn btn-primary like-post me-2 mb-1" data-postid="'. $postRow['id_post'] .'" aria-label="upvote">upvote</button>';
                
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
                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'"><span class="like-count">'. $postRow['n_votes'] .'</span> upvotes</span>
                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'">'. $postRow['n_comments'] .'  comments</span>
            </div>';
        }
        // controlla se l'utente ha gia' salvato il post o meno e genra gli appositi bottoni
        if (isset($_SESSION['id_user'])) {
            $id_user = $_SESSION['id_user'];
            $id_p = $postRow['id_post'];
            $check = $conn_db->query("SELECT * FROM saves WHERE id_a = $id_user AND id_po = $id_p");
            if($check->num_rows == 0){               
                
                echo '<button type="button" class="btn btn-primary me-2 mb-1" id="save-post" data-postid="'. $postRow['id_post'] .'" aria-label="save post">Save post</button>
                </div>';
                
                } else {
                
                echo '<button type="button" class="btn btn-success me-2 mb-1" id="save-post" data-postid="'. $postRow['id_post'] .'" aria-label="unsave post">Saved</button>
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
    <!-- div per i bottoni d'impaginazione Bootstrap -->
    <div class="d-flex flex-column justify-content-center align-items-center">
    <?php
        $total_pages = ceil($total_items / $items_per_page);

        echo '<nav aria-label="Page navigation example">';
        echo '<ul class="pagination">';

        // Bottone pagina precedente (se non si e' nella prima pagina)
        if ($current_page > 1) {
          echo '<li class="page-item"><a class="page-link" href="?id_blog='. urlencode($id_b) .'&page=' . ($current_page - 1) . '" aria-label="Previous page">';
          echo '<span aria-hidden="true">&laquo;</span></a></li>';
        }

        // Itera e genera il numero di pagine
        for ($i = 1; $i <= $total_pages; $i++) {
          $active_class = ($i == $current_page) ? ' active' : '';
          echo '<li class="page-item' . $active_class . '"><a class="page-link" href="?id_blog='. urlencode($id_b) .'&page=' . $i . '">' . $i . '</a></li>';
        }

        // Bottone pagina seguente (se non si e' nell'ultima pagina)
        if ($current_page < $total_pages) {
          echo '<li class="page-item"><a class="page-link" href="?id_blog='. urlencode($id_b) .'&page=' . ($current_page + 1) . '" aria-label="Next page">';
          echo '<span aria-hidden="true">&raquo;</span></a></li>';
        }

        echo '</ul>';
        echo '</nav>';
        ?>
        </div>
    </section>
<!-- div col 8 -->
</div>

<!-- div per la sezione blog sul desktop -->
<div class="col-2 text-end pe-3 pt-2 ms-0 ps-0 d-flex flex-column bg-light" style="max-height: 100vh; overflow-y: auto;">
    <div id="blog_panel">
    <!-- Immagine del blog -->
        <div> 
            <img src="images/<?php echo $immagine; ?>" class="img-thumbnail text-end" style="max-height: 18rem;" alt="<?php echo $immagine; ?>">
        </div> 
    <!-- Titolo del blog e badges per le info sul blog -->
        <div class="container-sm">
            <div class="">
                <p class="container-sm mx-0 h2 text-break"><?php echo 't/' . $titolo; ?></p>
                <div>
                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2 category"><a href="category.php?id_cat=<?php echo $category_id; ?>"><?php echo $categoria; ?></a></span>
                </div>
                <div>
                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2 <?php echo $id_b; ?>" data-blogid="<?php echo $id_b; ?>"><?php echo $num_followers; ?>  followers</span>
                </div>
                <div>
                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2"><?php echo $num_coauthors+1 . " authors"; ?></span>
                </div>
            </div><br>

            <!-- Descrizione del blog-->
            <div class="container-sm px-0">
                <div>
                    <?php
                        $result = $conn_db->query("SELECT username, id_user FROM utente WHERE id_user = '$autore'");
                        // Controlla se la query ha avuto successo
                        if ($result === false) {
                            die('Errore nella query: ' . $conn_db->error);
                        }
                        // Mostra il nome dell'autore
                        while ($row = $result->fetch_assoc()) {
                            echo '<p class="profile h5" value="' . $row['username'] . '"><a href="profile.php?id_user=' . urlencode($row['id_user']) . '" aria-label="' . $row['username'] . ' author">' . 'u/' . $row['username'] . '</a></p>';
                        }
                        $result->free();
                        ?>
                <div>
                    <p class="blog_description text-break mt-3 text-start"><?php echo $descrizione; ?></p>
                </div>
            </div>
            <br>


            <?php
            // Verifica se 'id_user' è impostato nella sessione e se è co-autore del blog per permettere di creare post
            $result = $conn_db->query("SELECT id_aut FROM co_manages WHERE id_bl = '$id_b'");
            while($row = $result->fetch_assoc()) {
                if(isset($_SESSION['id_user']) && ($_SESSION['id_user'] == $row['id_aut'])) {
                        ?>
                        <div class="btn pb-2 pe-0" role="group" aria-label="Create a post">
                            <a href="create_post.php?id_blog=<?php echo urlencode($id_b); ?>" class="btn btn-dark">Create a post</a>            
                        </div><?php
                }
            }
            $result->free();
            // Verifica se 'id_user' è impostato nella sessione e se è l'autore del blog per abilitare i permessi
            if(isset($_SESSION['id_user']) && ($_SESSION['id_user'] == $autore)){
            ?>
            <div class="">
                <div class="btn pb-2 pe-0" role="group" aria-label="Create a post">
                    <a href="create_post.php?id_blog=<?php echo urlencode($id_b); ?>" class="btn btn-dark">Create a post</a>            
                </div>
                <div class="btn-group" role="group" aria-label="Edit and delete">
                    <a href="edit_blog.php?id_blog=<?php echo urlencode($id_b); ?>" type="button" class="btn btn-success">Edit</a>
                    <!-- Button to trigger modal -->
                    <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_blog_desktop">Delete</a>
                    <!-- modal ro confirm deletion -->
                    <div class="modal fade" id="delete_blog_desktop" tabindex="-1" aria-labelledby="delete_blog_desktop?" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Delete blog?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-center">
                            <p>Are you sure you want to delete your blog?</p>
                            <p> This <strong>cannot</strong> be undone.</p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <a href="includes/_delete.php?id=<?php echo urlencode($id_b); ?>&type=<?php echo urlencode('blog'); ?>" type="button" class="btn btn-danger">Delete</a>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>

                <!-- Sezione dei coautori -->
                <div class="d-block mt-4 text-center">
                    <p class="h5">Co-authors:</p>
                </div>


                <!-- REVOCA ACCESSO AI COAUTORI -->
                <div id="coauthors" class="d-flex flex-column flex-sm-row flex-md-wrap mt-3 align-items-end justify-content-end">
                <?php
                $result = $conn_db->query("SELECT username, id_user, propic FROM utente WHERE id_user IN
                (SELECT id_aut FROM co_manages WHERE id_bl = '$id_b')");

                // Verifica che la query abbia avuto successo
                if ($result === false) {
                    die('Error in the query: ' . $conn_db->error);
                }
                // Vengono elencati i coautori insieme al pulsante X per eliminarli del blog se l'autore lo desidera
                while ($row = $result->fetch_assoc()) {
                    echo '    <div class="d-flex flex-column mb-1 my-2 ms-0 me-2 py-1">
                                <span class="badge d-flex align-items-center p-1 pe-2 text-info-emphasis bg-info-subtle border border-info-subtle rounded-pill">
                                  <img class="rounded-circle me-1" width="24" height="24" src="propics/'.$row['propic'].'" alt="propic">
                                    <a href="profile.php?id_user=' . urlencode($row['id_user']) . '">'. $row['username'] .'</a>
                                  <span class="vr mx-2"></span>
                                  <form action="includes/_authorize.php" method="post">
                                    <input type="hidden" name="type" value="' . urldecode('revoca') . '">
                                    <input type="hidden" name="id_user" value="' . urlencode($row['id_user']) . '">
                                    <input type="hidden" name="id_blog" value="' . urlencode($id_b) . '">
                                    <button aria-label="remove author" type="submit" style="background: none!important; border: none; padding: 0!important; color:inherit;">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="red" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"></path>
                                      </svg>
                                    </button>
                                  </form>
                                </span>
                                </div>';
                }
                $result->free();
                ?>
                </div>

            </div>
            <?php

            // Se l'utente non e' l'autore, viene mostrato il bottone "Follow blog" se non lo segue gia' e i coautori senza bottone X.
            } elseif (isset($_SESSION['id_user'])){
                $id_user = $_SESSION['id_user'];
                $check = $conn_db->query("SELECT * FROM follows WHERE id_auth = $id_user AND id_b = $id_b");
                if($check->num_rows == 0){               
                    ?>
                    <div class="">
                        <button type="button" class="btn btn-primary follow" data-blogid="<?php echo $id_b; ?>">Follow blog</button>
                    </div>
                    <div class="d-block mt-4 text-center">
                        <p class="h5">Co-authors:</p>
                    </div>

                    <!-- REVOCA -->
                    <div id="coauthors" class="d-flex flex-column flex-sm-row flex-md-wrap mt-3 align-items-end justify-content-end">
                        <?php
                        $result = $conn_db->query("SELECT username, id_user, propic FROM utente WHERE id_user IN
                        (SELECT id_aut FROM co_manages WHERE id_bl = '$id_b')");

                        // Check if the query was successful
                        if ($result === false) {
                            die('Error in the query: ' . $conn_db->error);
                        }
                    
                        while ($row = $result->fetch_assoc()) {
                            echo '   <div class="d-flex flex-column mb-1 my-2 ms-0 me-2 py-1">
                                        <span class="badge d-flex align-items-center p-1 pe-2 text-info-emphasis bg-info-subtle border border-info-subtle rounded-pill">
                                          <img class="rounded-circle me-1" width="30" height="30" src="propics/'.$row['propic'].'" alt="propic">
                                          <a href="profile.php?id_user=' . urlencode($row['id_user']) . '">'. $row['username'] .'</a>
                                        </span>
                                    </div>';
                        }
                        $result->free();
                        ?>
                    </div>
                    <?php
                    // Se l'utente non e' l'autore, viene mostrato il bottone "Following" se gia' lo segue e i coautori senza bottone X.
                    } else {
                    ?>
                    <div class="">
                        <button type="button" class="btn btn-success follow" data-blogid="<?php echo $id_b; ?>">Following</button>
                    </div>
                    <div class="d-block mt-4 text-center">
                        <p class="h5">Co-authors:</p>
                    </div>

                    <!-- Badges dei coautori -->
                    <div id="coauthors" class="d-flex flex-column flex-sm-row flex-md-wrap mt-3 align-items-end justify-content-end">
                        <?php
                        $result = $conn_db->query("SELECT username, id_user, propic FROM utente WHERE id_user IN
                        (SELECT id_aut FROM co_manages WHERE id_bl = '$id_b')");

                        // Check if the query was successful
                        if ($result === false) {
                            die('Error in the query: ' . $conn_db->error);
                        }
                    
                        while ($row = $result->fetch_assoc()) {
                            echo '   <div class="d-flex flex-column mb-1 my-2 ms-0 me-2 py-1">
                                        <span class="badge d-flex align-items-center p-1 pe-2 text-info-emphasis bg-info-subtle border border-info-subtle rounded-pill">
                                          <img class="rounded-circle me-1" width="30" height="30" src="propics/'.$row['propic'].'" alt="propic">
                                          <a href="profile.php?id_user=' . urlencode($row['id_user']) . '">'. $row['username'] .'</a>
                                        </span>
                                    </div>';
                        }
                        $result->free();
                        ?>
            </div>

                    <?php
                    }
            } else  {
              // Se l'utente non e' loggato, viene motrato "Follow blog" con il messaggio di errore che gli dice di loggarsi
                ?>
                <div class="">
                    <button type="button" class="btn btn-primary log-in" data-blogid="<?php echo $id_b; ?>">Follow blog</button>
                      <div class="text-start">
                        <span class="login-message text-danger"></span>
                      </div>
                </div>
                <div class="d-block mt-4 text-center">
                    <p class="h5">Co-authors:</p>
                </div>

                <!-- Badges dei coautori -->
                <div id="coauthors" class="d-flex flex-column flex-sm-row flex-md-wrap mt-3 align-items-end justify-content-end">
                    <?php
                    $result = $conn_db->query("SELECT username, id_user, propic FROM utente WHERE id_user IN
                    (SELECT id_aut FROM co_manages WHERE id_bl = '$id_b')");

                    // Check if the query was successful
                    if ($result === false) {
                        die('Error in the query: ' . $conn_db->error);
                    }

                    while ($row = $result->fetch_assoc()) {
                        echo '   <div class="d-flex flex-column mb-1 my-2 ms-0 me-2 py-1">
                                    <span class="badge d-flex align-items-center p-1 pe-2 text-info-emphasis bg-info-subtle border border-info-subtle rounded-pill">
                                      <img class="rounded-circle me-1" width="30" height="30" src="propics/'.$row['propic'].'" alt="propic">
                                      <a href="profile.php?id_user=' . urlencode($row['id_user']) . '">'. $row['username'] .'</a>
                                    </span>
                                </div>';
                    }
                    $result->free();
                    ?>
                </div>

                <?php
            }

            ?>
        </div><br><br>

    </div>
</div>
</div>
<!-- /div pre chiudere col-10 -->
</div>
<!-- /div row -->
</div>
<!-- /div per i container -->
</div>


<!-- AJAX per seguire i blog -->
<script>
  $(document).ready(function(){
    $('body').on('click', '.follow', function(){  // Nel body, sente l'evento click nell'elemento della classe .follow
        var blogid = $(this).data('blogid');
        var button = $(this);
        var followersBadge = $("." + blogid); // si riferisce ai badge della classe blogid
        $.ajax({
            url: 'includes/_follow.php',
            type: 'post',
            data: {
                'blogid': blogid
            },
            success: function(response){
                // Aggiorna il bottone con le classi giuste a seconda della risposta
                if(button.text() == "Follow blog") {
                    button.text("Following");
                    button.removeClass("btn-primary"); // rimuove la classe precedente
                    button.addClass("btn-success"); // aggiunge la classe nuova

                    // Aumenta il numero di followers
                    var num_followers = parseInt(followersBadge.text());
                    followersBadge.text(num_followers + 1 + " followers");
                } else {
                    button.text("Follow blog");
                    button.removeClass("btn-success"); // rimuove la classe precedente
                    button.addClass("btn-primary"); // aggiunge la classe nuova

                    // Diminuisce il numero di followers
                    var num_followers = parseInt(followersBadge.text());
                    followersBadge.text(num_followers - 1 + " followers");
                }
            }
        }).fail(function(jqXHR, textStatus, errorThrown){
            // Alert per informare se c'e' stato un'errore
            alert("Please log in to be able to follow blogs");
        });
    });
});
</script>

</body>
<!-- inclusione del footer -->
<?php
  $conn_db->close();
  include "includes/footer.php";
?>

</html>
