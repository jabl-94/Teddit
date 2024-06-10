<?php
// Includiamo i file neccessari per la struttura della pagina 
include "includes/head.php";
include "includes/header.php"; 
include "includes/sidebar.php"; 
include "includes/_tempotrascorso.php"; 
?>

<body class="bg-light">

<!-- media queries per responsiveness -->
<style>
@media only screen and (max-width: 768px) {

  #back_btn {
  display: none;
}

  #white_space {
    display: flex;
  }

  .blog_cat {
    display: none;
  }

  .blog_desc {
    display: none;
  }  

  .blog_aut {
    display: none;
  }

}

@media only screen and (min-width: 769px) {

  #white_space {
  display: none;
}

}

.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: black;
}
</style>

<?php

    // Verifica la provenienza dell'id_user
    if (isset($_GET['id_user'])) {
      $id_user = urldecode($_GET['id_user']);
    } elseif (isset($_SESSION['id_user'])) {
      $id_user = $_SESSION['id_user'];
    } 
    
    $result = $conn_db->query("SELECT * FROM utente WHERE id_user = '$id_user'");

    // Controlla se la query ha avuto successo
    if ($result === false) {
        die('Errore nella query: ' . $conn_db->error);
    }

    // Inizializza le variabili dell'utente
    while ($row = $result->fetch_assoc()) {
        $username = $row['username'];
        $email = $row['email'];
        $propic = $row['propic'];
        $bio = $row['bio'];

    }
    $result->free();

?>

<!-- Container per mostrare le info dell'utente -->
<div class="col-md-3 col-10 px-1">
    <div class="d-flex flex-column container-sm text-start ps-4 pb-5 bg-light" style="max-height: 100vh; overflow-y: auto;">
        <p class="h2 mt-1">
        <?php echo $username; ?>
        </p>
        <div class="text-center mt-0" style="max-height: 12rem;">
            <img class="img-fluid h-100" style="clip-path: circle(40%); max-width: 16rem; max-height: 16rem;" src="propics/<?php echo $propic; ?>" alt="<?php echo $username; ?>">
        </div>

        <div class="mt-5">
          <p><strong>About <?php echo $username?>:</strong></p>
            <p><?php echo $bio; ?></p>
        </div><?php
        // Verifica se l'utente e' loggato e se e' diverso dell'id dell'utente del profilo
        if (((isset($_SESSION['id_user'])) && ($id_user != $_SESSION['id_user']))) {
                $id_us = $_SESSION['id_user'];?>
                <form action="includes/_authorize.php" method="POST"> 
                    <div class="input-group my-2">
                        <select class="form-select" id="inputGroupSelect04" name="id_blog_user" aria-label="" required>
                            <option  disabled selected value>Select blog...</option>
                            <?php
                            // Query per estrarre i blog dell'utente
                            $result = $conn_db->query("SELECT * FROM blog WHERE id_author = $id_us AND id_blog NOT IN
                            (SELECT id_bl FROM co_manages WHERE id_aut = $id_user)");

                            // Verifica se la query ha avuto successo
                            if ($result === false) {
                                die('Errore nella query: ' . $conn_db->error);
                            }

                            // Genera le opzioni per autorizzare l'utente per diventare co-autore
                            while ($row = $result->fetch_assoc()) {
                              echo '<option value="' . $id_user . '|' . $row['id_blog'] . '">' . $row['title'] . '</option>'; 
                            }
                            $result->free();
                            ?>
                        </select>
                        <input type="hidden" name="type" value="autorizza">
                        <button class="btn btn-primary px-3" type="submit">Authorize</button>
                    </div><br>
                </form><?php
        }
        // Verifica se l'utente e' loggato e se coincide con l'id dell'utente del profilo
        if ((isset($_SESSION['id_user'])) && ($id_user == $_SESSION['id_user'])) {
        ?> 

          <?php if ($propic != '0.png') { ?>
            <div class="btn" aria-label="Delete profile picture">
                <!-- Bottone per mostrare il modal -->
                <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_propic">Delete image</a>
                <!-- modal per confermare l'eliminazione -->
                <div class="modal fade" id="delete_propic" tabindex="-1" aria-labelledby="delete_propic" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Delete profile picture?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body text-center">
                        <p>Are you sure you want to delete your profile picture?</p><br>
                        <p> This <strong>cannot</strong> be undone.</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="includes/_delete_image.php?username=<?php echo urlencode($username); ?>&type=<?php echo urlencode('profile'); ?>" type="button" class="btn btn-danger">Delete</a>
                      </div>
                    </div>
                  </div>
                </div>
            
            
            
            </div><br>
          <?php } ?>

            <div>
              <p><?php echo $email; ?></p>
            </div><br> 
            <?php
            echo '<div><a class="btn btn-success mb-2" href="edit_profile.php?id_user=' .  urlencode($id_user) . '" role="button">Edit profile</a>';
        }
        // Verifica se l'utente e' premium o no e mostra il bottone 
        if ((isset($_SESSION['id_user'])) && ($id_user == $_SESSION['id_user'])) {
            $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = $id_user");
            
            if ($result->num_rows == 1) {
                echo ' <a class="btn btn-warning mb-2" href="premium.php?id_user=' .  urlencode($id_user) . '" role="button"><strong>Manage subscription</strong></a>
                    </div>';
            } else {
                echo ' <a class="btn btn-warning mb-2" href="premium.php?id_user=' .  urlencode($id_user) . '" role="button"><strong>GET PREMIUM</strong></a>
                    </div>';
            }
        }
        ?>
    </div>
    </div>

<div class="col-2 bg-light" id="white_space"></div>


<!-- Accordion per i blog propri, cogestiti e post salvati dall'utente loggato -->
<div class="col-md-7 col-10 px-0">
<?php
    // blog dell'utente
    if ((isset($_SESSION['id_user'])) && ($id_user == $_SESSION['id_user'])) {

    $result = $conn_db->query("SELECT * FROM blog 
    JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat
    WHERE blog.id_author = $id_user ORDER BY blog.id_blog DESC");

    if ($result === false) {
        die('Errore nella query: ' . $conn_db->error);
    }
        echo '<div class="container-sm px-1 bg-light" style="max-height:100vh;">
            <div class="accordion" id="profile">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#myBlogs" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                    <strong>My Blogs</strong>
                  </button>
                </h2>
                <div id="myBlogs" class="accordion-collapse collapse" data-bs-parent="#profile">
                  <div class="d-flex flex-column accordion-body bg-light" style="max-height: 77vh; overflow-y: auto;">';

          if ($result->num_rows == 0) {
            echo '  <div class="d-flex flex-column justify-content-center align-items-center" style="max-height: 83vh;">
                        <h1 class="mx-auto mt-auto">Nothing to see here yet...</h1>
                        <img src="assets/fine.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2 py-2" style="max-height: 15rem; object-fit: contain;" alt="Nothing here">
                    </div>';
        }

        // Genera le card per ogni blog
        while ($row = $result->fetch_assoc()) {
          echo '<div class="card d-flex flex-column mb-1 my-3 mx-1" style="max-width: 70rem;">
                  <div class="row g-0">
                    <div class="col-2 d-flex justify-content-center align-items-center m-auto">
                        <img src="images/'.$row['img'].'" class="card-img-top py-0" style="max-height: 6rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="blog image">
                    </div>
                  <div class="col-md-7 col-5">
                      <div class="card-body" style="position: relative;">
                              <h5 class="card-title blog"><a href="blog.php?id_blog=' . urlencode($row['id_blog']) . '" class="stretched-link">'. 't/' . $row['title'] .'</a></h5>
                              <p class="card-text text-truncate mb-2 blog_aut profile" style="position: relative; z-index: 2;"><a class="post_card" href="profile.php?id_user=' . urlencode($row['id_author']) . '">'. 'u/' . $row['username'] .'</a></p>
                              <p class="card-text text-truncate blog_desc">'. $row['description'] .'</p>
                      </div>
                  </div>
                  <div class="col-md-3 col-5 py-0">
                      <div class="card-body text-end px-1">
                        <div>
                          <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2 blog_cat category"><a href="category.php?id_cat=' . urlencode($row['id_cat']) . '">'. $row['name'] .'       </a></span>
                        </div>
                        <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2" id="'. $row['id_blog'] .'" data-blogid="'. $row['id_blog'] .'">'. $row['n_followers'] .'  followers</span>
                        <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-4">'. ($row['n_coauthors']+1) .' authors</span>
                        </div>
                      </div>
                  </div>
                </div>';
        }

        echo '</div>
            </div>
          </div>';
        $result->free();


    // Blog in cui l'utente e' autorizzato
    $result = $conn_db->query("SELECT * FROM blog 
    JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat
    WHERE blog.id_blog IN
        (SELECT id_bl FROM co_manages WHERE id_aut = $id_user)   
    ORDER BY blog.id_blog DESC");

    if ($result === false) {
        die('Errore nella query: ' . $conn_db->error);
    }

        echo '<div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#comanagedBlogs" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                    <strong>Blogs comanaged by me</strong>
                  </button>
                </h2>
                <div id="comanagedBlogs" class="accordion-collapse collapse" data-bs-parent="#profile">
                  <div class="d-flex flex-column accordion-body px-0 bg-light" style="max-height: 77vh; overflow-y: auto;">';

          if ($result->num_rows == 0) {
            echo '  <div class="d-flex flex-column justify-content-center align-items-center" style="max-height: 83vh;">
                        <h1 class="mx-auto mt-auto">Nothing to see here yet...</h1>
                        <img src="assets/fine.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2 py-2" style="max-height: 15rem; object-fit: contain;" alt="Nothing here">
                    </div>';
        }

        // Genera le card per ogni blog
        while ($row = $result->fetch_assoc()) {
          echo '<div class="card d-flex flex-column mb-1 my-3 mx-3" style="max-width: 70rem;">
                  <div class="row g-0">
                    <div class="col-2 d-flex justify-content-center align-items-center m-auto">
                        <img src="images/'.$row['img'].'" class="card-img-top py-0" style="max-height: 6rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="blog image">
                    </div>
                    <div class="col-md-7 col-5">
                      <div class="card-body" style="position: relative;">
                              <h5 class="card-title blog"><a href="blog.php?id_blog=' . urlencode($row['id_blog']) . '" class="stretched-link">'. 't/' . $row['title'] .'</a></h5>
                              <p class="card-text text-truncate mb-2 blog_aut profile" style="position: relative; z-index: 2;"><a class="post_card" href="profile.php?id_user=' . urlencode($row['id_author']) . '">'. 'u/' . $row['username'] .'</a></p>
                              <p class="card-text text-truncate blog_desc">'. $row['description'] .'</p>
                      </div>
                  </div>
                  <div class="col-md-3 col-5 py-0">
                      <div class="card-body text-end px-1">
                        <div>
                          <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2 blog_cat category"><a href="category.php?id_cat=' . urlencode($row['id_cat']) . '">'. $row['name'] .'       </a></span>
                        </div>
                        <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2" id="'. $row['id_blog'] .'" data-blogid="'. $row['id_blog'] .'">'. $row['n_followers'] .'  followers</span>
                        <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-4">'. ($row['n_coauthors']+1) .' authors</span>
                        </div>
                      </div>
                  </div>
                </div>';
        }

        echo '</div>
            </div>
          </div>';
        $result->free();
        echo '<div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#savedPosts" aria-expanded="false" aria-controls="collapseThree">
                    <strong>Saved posts</strong>
                  </button>
                </h2>
                <div id="savedPosts" class="accordion-collapse collapse show" data-bs-parent="#profile">
                  <div class="d-flex flex-column accordion-body px-2 bg-light" style="max-height: 78vh; overflow-y: auto;">';
    
    // Post salvati dall'utente
    $result = $conn_db->query("SELECT * FROM post JOIN saves ON post.id_post = saves.id_po JOIN utente ON post.id_u = utente.id_user JOIN blog ON post.id_b = blog.id_blog
    WHERE saves.id_a = $id_user ORDER BY post.id_post DESC");
      // Check if the query was successful
      if ($result === false) {
        die('Errore nella query: ' . $conn_db->error);
      }
      // Mostra l'immagine di 'nothing found' se non c'e' niente
      if ($result->num_rows == 0) {
          echo '  <div class="d-flex flex-column justify-content-center align-items-center" style="max-height: 83vh;">
                      <h1 class="mx-auto mt-auto">Nothing to see here yet...</h1>
                      <img src="assets/fine.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2 py-2" style="max-height: 15rem; object-fit: contain;" alt="Nothing here">
                  </div>';
      }

      // Verifica che il numero di risultati sia piu' grande di 0
      if ($result->num_rows > 0) {
          // Genera le card per ogni post
          while ($postRow = $result->fetch_assoc()) {  
            echo '<div class="my-1 col-12">
            <div class="card" style="height: auto">
            <div class="card-body pt-1">
              <p class="card-text mb-3 blog"><a href="blog.php?id_blog=' . urlencode($postRow['id_blog']) . '"><strong><small>'. 't/' . $postRow['title'] .'</small></strong></a><small><small>● '. tempoTrascorso($postRow['date_time']) .'</small></small></p>
              <p class="h4 card-title post"><a href="post.php?id_post=' . urlencode($postRow['id_post']) . '">'. $postRow['title_post'] .'</a></p>
              <!-- <p class="card-text">'. 'u/' . $postRow['username'] .'</p> -->
              <p class="card-text text-truncate mt-3">'. $postRow['text_post'] .'</p>
            </div>';

            $id_p = $postRow['id_post'];
            $youtube_link = $postRow['link'];
            if (!empty($youtube_link)) {
                // Estrae l'id del video tramite il link YouTube
                preg_match('/v=([^&]+)/', $youtube_link, $matches);
                $video_id = $matches[1];
                
                // Genera l'iframe del servizio
                echo '
                <div class="h-100 text-center mx-1 d-flex justify-content-center my-2 px-2">
                <div class="ratio ratio-16x9" style="max-width: 90%;">
                        <iframe src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>';
            } else {
              
              $subresult = $conn_db->query("SELECT `path` FROM `image` WHERE id_pst = '$id_p' ORDER BY id_image ASC");

              // Verifica se c'e' solo un'immagine
              if ($subresult->num_rows == 1) {
                  // Se si, allora la mostra
                  $imageRow = $subresult->fetch_assoc();
                  echo '<div class="h-100 px-2">
                          <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                         </div>';
                // Altrimenti se ci sono piu' immagini, genera il carousel Bootstrap
              } elseif ($subresult->num_rows > 1) {

              // Carousel per le immagini
              echo '<div class="">
                      <div id="car-'.$postRow['id_post'].'" class="carousel slide">
                          <div class="carousel-inner" style="height: auto;">';
              $firstImage = true; // Segna la prima immagine
              while($imageRow = $subresult->fetch_assoc()){
                  // Le mostra
                  if($firstImage) {
                      echo '<div class="carousel-item active h-100 px-2">
                              <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                          </div>';
                      $firstImage = false; // Cambia la variabile per indicare che le altre non sono la prima immagine
                  } else {
                      echo '<div class="carousel-item h-100 px-2">
                        <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                      </div>';
                  }
              }
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

              } else {

              }
              $subresult->free();
            }


            echo        '<div class="card-footer d-flex flex-column flex-sm-row align-items-center py-1">';
            // Genera i bottoni per gli upvote
            if (isset($_SESSION['id_user'])) {
                $id_user = $_SESSION['id_user'];
                $id_pt = $postRow['id_post'];
                $check = $conn_db->query("SELECT * FROM vote_post WHERE id_ur = $id_user AND id_pt = $id_pt");
                if($check->num_rows == 0){               
                    
                    echo '
                    <div class="mb-2">
                      <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 like-count-badge" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'"><span class="like-count">'. $postRow['n_votes'] .'</span> upvotes</span>
                      <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'">'. $postRow['n_comments'] .'  Comments</span>
                    </div>
                    <div class="mb-2">
                      <button type="button" class="btn btn-primary like-post me-2" data-postid="'. $postRow['id_post'] .'">upvote</button>';
                    
                    } else {
                    
                    echo '
                    <div class="mb-2">
                      <span class="badge text-bg-warning border rounded-pill py-2 me-2 like-count-badge" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'"><span class="like-count">'. $postRow['n_votes'] .'</span> upvotes</span>
                      <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'">'. $postRow['n_comments'] .'  Comments</span>
                    </div>
                    <div class="mb-2">
                      <button type="button" class="btn btn-warning like-post me-2" data-postid="'. $postRow['id_post'] .'" aria-label="remove upvote">upvoted</button>';
                    
                    }
            }
            // Genera i bottoni per salvare i post
            if (isset($_SESSION['id_user'])) {
                $id_user = $_SESSION['id_user'];
                $id_p = $postRow['id_post'];
                $check = $conn_db->query("SELECT * FROM saves WHERE id_a = $id_user AND id_po = $id_p");
                if($check->num_rows == 0){               
                    
                    echo '<button type="button" class="btn btn-primary" id="save-post" data-postid="'. $postRow['id_post'] .'">Save post</button>
                    </div>';
                    
                    } else {
                    
                    echo '<button type="button" class="btn btn-success" id="save-post" data-postid="'. $postRow['id_post'] .'" aria-label="unsave">Saved</button>
                    </div>';
                    
                    }
            }
            echo '      </div>
                    </div>
                </div>';
                  }
              }
              $result->free();
                
                echo '            </div>
                                </div>
                              </div>
                            </div>';
    } else {   
      
      
      // visitatore
      // Genera i blog dell'utente che si visita
        $result = $conn_db->query("SELECT * FROM blog 
        JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat
        WHERE blog.id_author = $id_user ORDER BY blog.id_blog DESC");

        if ($result === false) {
            die('Errore nella query: ' . $conn_db->error);
        }
    
            echo '<div class="container-sm px-1 bg-light"">
                <div class="accordion" id="profile"">
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#myBlogs" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                        <strong>'. $username .'\'s Blogs</strong>
                      </button>
                    </h2>
                    <div id="myBlogs" class="accordion-collapse collapse" data-bs-parent="#profile">
                      <div class="accordion-body px-0 bg-light"style="max-height: 87vh; overflow-y: auto;">';
    
              if ($result->num_rows == 0) {
                echo '  <div class="d-flex flex-column justify-content-center align-items-center" style="max-height: 83vh;">
                            <h1 class="mx-auto mt-auto">Nothing to see here yet...</h1>
                            <img src="assets/fine.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2 py-2" style="max-height: 15rem; object-fit: contain;" alt="Nothing here">
                        </div>';
            }


            // Genera le card dei blog
            while ($row = $result->fetch_assoc()) {
              echo '<div class="card d-flex flex-column mb-1 my-3 mx-3" style="max-width: 70rem;">
              <div class="row g-0">
              <div class="col-2 d-flex justify-content-center align-items-center m-auto">
                  <img src="images/'.$row['img'].'" class="card-img-top py-0" style="max-height: 6rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="blog image">
              </div>
              <div class="col-md-7 col-5">
                  <div class="card-body" style="position: relative;">
                          <h5 class="card-title blog"><a href="blog.php?id_blog=' . urlencode($row['id_blog']) . '" class="stretched-link">'. 't/' . $row['title'] .'</a></h5>
                          <p class="card-text text-truncate mb-2 blog_aut profile" style="position: relative; z-index: 2;"><a class="post_card" href="profile.php?id_user=' . urlencode($row['id_author']) . '">'. 'u/' . $row['username'] .'</a></p>
                          <p class="card-text text-truncate blog_desc">'. $row['description'] .'</p>
                  </div>
              </div>
              <div class="col-md-3 col-5 py-0">
                  <div class="card-body text-end px-1">
                      <div>
                          <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2 blog_cat category"><a href="category.php?id_cat=' . urlencode($row['id_cat']) . '">'. $row['name'] .'       </a></span>
                      </div>
                      <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2" id="'. $row['id_blog'] .'" data-blogid="'. $row['id_blog'] .'">'. $row['n_followers'] .'  followers</span>
                      <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-4">'. ($row['n_coauthors']+1) .' authors</span>';
              // Bottoni per seguire i blog
              if (isset($_SESSION['id_user'])) {
                  if ($row['id_author'] != $_SESSION['id_user']) {
                      $id_follow = $_SESSION['id_user'];
                      $id_b = $row['id_blog'];
                      $check = $conn_db->query("SELECT * FROM follows WHERE id_auth = $id_follow AND id_b = $id_b");
                      if($check->num_rows == 0){
                          echo '<div class="">
                                  <button type="button" class="btn btn-primary follow-blog" data-blogid="'. $row['id_blog'] .'">Follow blog</button>
                                </div>';
                      } else {
                          echo    '<div class="">
                                      <button type="button" class="btn btn-success follow-blog" data-blogid="'. $row['id_blog'] .'">Following</button>
                                  </div>';
                      }
                  }
              } else {
                  echo    '<div class="">
                              <button type="button" class="btn btn-primary log-in" data-blogid="'. $row['id_blog'] .'">Follow blog</button>
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
  
          echo '</div>
              </div>
            </div>';
          $result->free();
          // Query per generare le card per i blog cogestiti dall'utente che si visita
        $result = $conn_db->query("SELECT * FROM blog 
        JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat
        WHERE blog.id_blog IN
        (SELECT id_bl FROM co_manages WHERE id_aut = $id_user)       
        ORDER BY blog.id_blog DESC");

        if ($result === false) {
            die('Errore nella query: ' . $conn_db->error);
        }
    
            echo '<div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#comanagedBlogs" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                      <strong>' . 'Blogs comanaged by ' . $username . '</strong>
                      </button>
                    </h2>
                    <div id="comanagedBlogs" class="accordion-collapse collapse show" data-bs-parent="#profile">
                      <div class="accordion-body px-0 bg-light" style="max-height: 87vh; overflow-y: auto;">';
    
              if ($result->num_rows == 0) {
                echo '  <div class="d-flex flex-column justify-content-center align-items-center" style="max-height: 83vh;">
                            <h1 class="mx-auto mt-auto">Nothing to see here yet...</h1>
                            <img src="assets/fine.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2 py-2" style="max-height: 15rem; object-fit: contain;" alt="Nothing here">
                        </div>';
            }

            // Genera le card dei blog
            while ($row = $result->fetch_assoc()) {
              echo '<div class="card d-flex flex-column mb-1 my-3 mx-3" style="max-width: 70rem;">
              <div class="row g-0">
              <div class="col-2 d-flex justify-content-center align-items-center m-auto">
                  <img src="images/'.$row['img'].'" class="card-img-top py-0" style="max-height: 6rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="blog image">
              </div>
              <div class="col-md-7 col-5">
                  <div class="card-body" style="position: relative;">
                          <h5 class="card-title blog"><a href="blog.php?id_blog=' . urlencode($row['id_blog']) . '" class="stretched-link">'. 't/' . $row['title'] .'</a></h5>
                          <p class="card-text text-truncate mb-2 blog_aut profile" style="position: relative; z-index: 2;"><a class="post_card" href="profile.php?id_user=' . urlencode($row['id_author']) . '">'. 'u/' . $row['username'] .'</a></p>
                          <p class="card-text text-truncate blog_desc">'. $row['description'] .'</p>
                  </div>
              </div>
              <div class="col-md-3 col-5 py-0">
                  <div class="card-body text-end px-1">
                      <div>
                          <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2 blog_cat category"><a href="category.php?id_cat=' . urlencode($row['id_cat']) . '">'. $row['name'] .'       </a></span>
                      </div>
                      <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2" id="'. $row['id_blog'] .'" data-blogid="'. $row['id_blog'] .'">'. $row['n_followers'] .'  followers</span>
                      <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-4">'. ($row['n_coauthors']+1) .' authors</span>';
              // Genera i bottoni dei blog
              if (isset($_SESSION['id_user'])) {
                  if ($row['id_author'] != $_SESSION['id_user']) {
                      $id_follow = $_SESSION['id_user'];
                      $id_b = $row['id_blog'];
                      $check = $conn_db->query("SELECT * FROM follows WHERE id_auth = $id_follow AND id_b = $id_b");
                      if($check->num_rows == 0){
                          echo '<div class="">
                                  <button type="button" class="btn btn-primary follow-blog" data-blogid="'. $row['id_blog'] .'">Follow blog</button>
                                </div>';
                      } else {
                          echo    '<div class="">
                                      <button type="button" class="btn btn-success follow-blog" data-blogid="'. $row['id_blog'] .'">Following</button>
                                  </div>';
                      }
                  }
              } else {
                  echo    '<div class="">
                              <button type="button" class="btn btn-primary log-in" data-blogid="'. $row['id_blog'] .'">Follow blog</button>
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
  
          echo '</div>
              </div>
            </div>';
          $result->free();
        }
    
    ?>
<!-- /div for col-8 -->
</div>
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