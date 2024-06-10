<?php 
  // Includiamo i file neccessari per la struttura della pagina
  include "includes/head.php";
  include "includes/header.php"; 
  include "includes/sidebar.php";
  include "includes/_tempotrascorso.php"; 
?>
<!-- Verifica la precedenza dell'id_post -->
<?php
    if (isset($_GET['id_post'])) {
        $id_p = urldecode($_GET['id_post']);
      } elseif (isset($_SESSION['id_post'])) {
        $id_p = $_SESSION['id_post'];
      } else {
        echo "No post ID provided";
      }
    
    // Query per estrarre il post
    $result = $conn_db->query("SELECT * FROM post JOIN utente ON post.id_u = utente.id_user WHERE id_post = $id_p");
    while($row = $result->fetch_assoc()){
        // Assegnazione dei risultati alle variabili
        $titolo = $row['title_post'];
        $testo = $row['text_post'];
        $data = $row['date_time'];
        $n_votes = $row['n_votes'];
        $n_comments = $row['n_comments'];
        $id_blog = $row['id_b'];
        $id_autore = $row['id_u'];
        $youtube_link = $row['link'];
        $nome_autore = $row['username'];
    }
    $result->free();
    // Query per estrarre le info del blog
    $result = $conn_db->query("SELECT * FROM blog WHERE id_blog = '$id_blog'");
    while($row = $result->fetch_assoc()){
        // Assegnazione dei risultati alle variabili
        $id_autoreBlog = $row['id_author'];
        $titolo_blog = $row['title'];

    }
    $result->free();
             
?>

<!-- Stile per la responsiveness -->
<style>
.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: black;
}

@media only screen and (max-width: 768px) {

  #ad_post {
    display: none;
  }

  #back_btn {
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
                        <a class="mx-4" href="premium.php" aria-label="link to get premium"><img class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" src="assets/getPremium.png" alt="get premium"></a>
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

    <div class="container-sm row px-2 mx-0 bg-light" style="max-height: 100vh; overflow-y: auto;">
        <div class="col-1 px-0" id="back_btn">
            <!-- Pulsante per tornare alla pagina del blog -->
            <button class="go_back btn btn-secondary" type="button" aria-label="go back" onclick="window.location.href='blog.php?id_blog=<?php echo $id_blog ?>'"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z"/>
                    </svg></button>
        </div>
        <div class="col-md-11 col-12 ps-0 pe-0">
            <!-- Section per visualizzare i post -->
            <section class="container-sm px-0">
            <div class="d-flex container-sm row px-0 bg-light">
                <div class="container-sm row pe-0">
                <?php
                $result = $conn_db->query("SELECT * FROM post JOIN utente ON post.id_u = utente.id_user JOIN blog ON post.id_b = blog.id_blog WHERE post.id_post = $id_p");

                // Check if the query was successful
                if ($result === false) {
                    die('Errore nella query: ' . $conn_db->error);
                }
                // Se non c'e' nessun post, viene mostrata la pagina "Nothing to see..."
                if ($result->num_rows == 0) {
                    echo '  <div class="d-flex flex-column justify-content-center align-items-center" style="height: 89vh;">
                                <h1 class="mx-auto mt-auto">Nothing to see here yet...</h1>
                                <img src="assets/fine.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2" style="max-height: 15rem; object-fit: contain;" alt="">
                            </div>';
                }
            
                // Check if the number of rows is greater than 0
                if ($result->num_rows > 0) {
                    // Genera le card per i post
                    while ($postRow = $result->fetch_assoc()) {  
                        echo '<div class="my-1 col-12">
                                    <div class="card" style="height: auto">
                                    <div class="card-body pt-1">
                                    <p class="h6 blog" value="' . $postRow['title'] . '"><a href="blog.php?id_blog=' . urlencode($postRow['id_blog']) . '">t/'. $postRow['title'] .'</a></p>

                                    <p class="h4 card-title">'. $postRow['title_post'] .'</p>
                                    <p class="card-text mb-3 profile"><a href="profile.php?id_user=' . urlencode($postRow['id_user']) . '"><strong><small>'. 'u/' . $postRow['username'] .'</small></strong></a><small><small>  ● '. tempoTrascorso($postRow['date_time']) .'</small></small></p>

                                    <p class="card-text mt-3">'. $postRow['text_post'] .'</p>
                                </div>';
                                                    
                                    $id_p = $postRow['id_post'];
                                    // Verifica che ci sia il liink e lo estrae se c'e'
                                    if (!empty($youtube_link)) {
                                        preg_match('/v=([^&]+)/', $youtube_link, $matches);
                                        $video_id = $matches[1];
                                        
                                        // Mostra il vdeo YouTube in un iframe
                                        echo '
                                        <div class="h-100 text-center mx-2">
                                            <div class="ratio ratio-16x9">
                                                <iframe src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                        </div>';
                                    } 

                                    $subresult = $conn_db->query("SELECT path FROM image WHERE id_pst = '$id_p' ORDER BY id_image ASC");
                    
                                    // Verifica se c'e' solo un'immagine
                                    if ($subresult->num_rows == 1) {
                                        // Se si, allora la mostra
                                        $imageRow = $subresult->fetch_assoc();
                                        echo '<div class="h-100 justify-content-center align-items-center px-2">
                                                <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                                               </div>';
                                    
                                    } elseif ($subresult->num_rows > 1) {   // Se ci sono piu' immagini allora genera il carousello

                                    echo '<div class="">
                                            <div id="car-'.$postRow['id_post'].'" class="carousel slide">
                                                <div class="carousel-inner" style="height: auto;">';
                                    $firstImage = true; // Segna la prima immagine
                                    while($imageRow = $subresult->fetch_assoc()){
                                        // Le mostra
                                        if($firstImage) {
                                            echo '<div class="carousel-item active h-100 justify-content-center align-items-center px-2">
                                                    <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                                                </div>';
                                            $firstImage = false; // Cambia la variabile per indicare che non le altre non sono la prima immagine
                                        } else {
                                            echo '<div class="carousel-item h-100 justify-content-center align-items-center px-2">
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
                                
                                
                            echo        '<div class="card-footer d-flex flex-column flex-sm-row align-items-center py-1">';
                            // Genera i bottoni a seconda di chi e' l'utente
                            if (isset($_SESSION['id_user'])) {
                                $id_user = $_SESSION['id_user'];
                                $id_pt = $postRow['id_post'];
                                $check = $conn_db->query("SELECT * FROM vote_post WHERE id_ur = $id_user AND id_pt = $id_pt");
                                if($check->num_rows == 0){               
                                    
                                    echo '
                                    <div class="mb-2">
                                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 like-count-badge" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'"><span class="like-count">'. $postRow['n_votes'] .'</span> upvotes</span>
                                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2" id="comment-'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'">'. $postRow['n_comments'] .'  comments</span>
                                    </div>
                                    <div class="mb-2">
                                    <button type="button" class="btn btn-primary like-post me-2" data-postid="'. $postRow['id_post'] .'">upvote</button>';
                                    
                                    } else {
                                    
                                    echo '
                                    <div class="mb-2">
                                    <span class="badge text-bg-warning border rounded-pill py-2 me-2 like-count-badge" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'"><span class="like-count">'. $postRow['n_votes'] .'</span> upvotes</span>
                                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2" id="comment-'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'">'. $postRow['n_comments'] .'  comments</span>
                                    </div>
                                    <div class="mb-2">
                                    <button type="button" class="btn btn-warning like-post me-2" data-postid="'. $postRow['id_post'] .'" aria-label="remove upvote">upvoted</button>';
                                    
                                    }
                            } else {
                                echo '
                                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 like-count-badge" id="'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'"><span class="like-count">'. $postRow['n_votes'] .'</span> upvotes</span>
                                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2" id="comment-'. $postRow['id_post'] .'" data-postid="'. $postRow['id_post'] .'">'. $postRow['n_comments'] .'  comments</span>';
                            }
                            // Bottone salva
                            if (isset($_SESSION['id_user'])) {
                                $id_user = $_SESSION['id_user'];
                                $id_p = $postRow['id_post'];
                                $check = $conn_db->query("SELECT * FROM saves WHERE id_a = $id_user AND id_po = $id_p");
                                if($check->num_rows == 0){               

                                    echo '<button type="button" class="btn btn-primary me-2" id="save-post" data-postid="'. $postRow['id_post'] .'">Save post</button>
                                    </div>';

                                    } else {
                                    
                                    echo '<button type="button" class="btn btn-success me-2" id="save-post" data-postid="'. $postRow['id_post'] .'" aria-label="unsave">Saved</button>
                                    </div>';
                                    
                                    }
                            } // Bottoni per gestire il post se si e' autore
                            if (isset($_SESSION['id_user']) && ($_SESSION['id_user'] == $postRow['id_u'] || $_SESSION['id_user'] == $postRow['id_author'])) {
                                echo '  <div class="mb-2">
                                        <a href="edit_post.php?id_post='. $postRow['id_post'] .'" type="button" class="btn btn-success me-2">Edit</a>                                        
                                            <!-- Button to trigger modal -->
                                            <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_post">Delete post</a>

                                            <!-- modal ro confirm deletion -->
                                            <div class="modal fade" id="delete_post" tabindex="-1" aria-labelledby="delete_post?" aria-hidden="true">
                                              <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title">Delete post?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                  </div>
                                                  <div class="modal-body text-center">
                                                    <p>Are you sure you want to delete your post?</p><br>
                                                    <p> This <strong>cannot</strong> be undone.</p>
                                                  </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <a href="includes/_delete.php?id='. $postRow['id_post'] .'&type='. urlencode("post") .'&id_blog='. urlencode($postRow['id_blog']) .'" type="button" class="btn btn-danger me-2">Delete</a>
                                                    </div>
                                                </div>
                                              </div>
                                            </div>
                                        </div>';
                            }
                            echo '      </div>
                                    </div>
                                </div>
                                ';
                    }
                }
                $result->free();
                ?>
                </div>
            </div>
            </section><br>
            
            <!-- Section per inserire un commento -->
            <section class="container-sm ps-0 pe-5">
                <form action="includes/_comment.php" method="POST">
                    <div class="card w-100 px-2">
                        <div class="create_comment">
                            <div class="comment_input">
                                <!-- Campo d'input per il commento -->
                                <label for="testo_commento" aria-label="leave a comment"></label>
                                <textarea class="form-control mt-1" id="testo_commento" name="testo_commento" placeholder="Leave a comment" required maxlength="2500"></textarea>        
                                <input type="hidden" value= "<?php echo $id_p ?>" id="hidden" name="id_post">
                                <!-- Pulsante per inviare il commento -->
                                <div class="text-end mt-2">
                                    <?php if (isset($_SESSION['id_user'])) {
                                        echo '<button class="submit_comment btn btn-dark mb-1" type="submit">Comment</button>';
                                    } else {
                                        echo '<button class="submit_comment btn btn-dark mb-1" id="commenta" type="submit">Comment</button>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>

            <!-- Sezzione commenti-->
            <section class="comment_section container-sm px-0 py-3 w-autp">
                <div class="col-sm-6 mb-3 mb-sm-0 w-100 pe-5">
                    <?php 
                $result = $conn_db->query("SELECT * FROM commento WHERE id_p = '$id_p' ORDER BY id_comment DESC");
                while($row = $result->fetch_assoc()){
                    $id_user = $row['id_us'];
                    $risultato = $conn_db->query("SELECT username FROM utente WHERE id_user = '$id_user'");
                    $riga = $risultato->fetch_assoc();
                    $username = $riga["username"];
                    $dt = tempoTrascorso($row['dt_tm']);
                    echo '<div class="card mb-2">
                            <div class="card-body">
                                <p class="h5 mb-0">' . $username . '</p>
                                <p class="mb-0" id="' . $row['id_comment'] . '">' . $row['testo'] . '</p>
                                <p class="mb-0">'. $dt . '</p>
                            </div>
                            <div class="card-footer d-flex flex-column flex-sm-row align-items-center py-1">';

                    $id_c = $row['id_comment'];
                    $n_vts = $row['n_vts'];

                    // Verifica se l'id_user c'e' e se e' uguale a quello dell'autore del commento
                    if(isset($_SESSION['id_user']) && ($_SESSION['id_user'] == $id_user) ){
                        ?>
                            <div>
                                <?php   if (isset($_SESSION['id_user'])) {
                                        $id_user = $_SESSION['id_user'];
                                        $check = $conn_db->query("SELECT * FROM vote_comment WHERE id_usr = $id_user AND id_cmt = $id_c");
                                        if($check->num_rows == 0){    // vengono generati i bottoni per upvottare i commmenti          

                                            echo '
                                            <span id="com-' . $id_c . '" data-commentid="' . $id_c . '" class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mx-2 likeCommentBadge"><span class="like-count">' . $n_vts . '</span> upvotes</span>
                                            <button type="button" class="btn btn-primary like-comment me-2" data-commentid="' . $id_c . '">upvote</button
                                            </div>
                                            </div>';

                                            } else {
                                            
                                            echo '                                   
                                            <span class="badge text-bg-warning border rounded-pill py-2 mx-2 likeCommentBadge" id="com-' . $id_c . '" data-commentid="' . $id_c . '"><span class="like-count">' . $n_vts . '</span> upvotes</span>
                                            <button type="button" class="btn btn-warning like-comment me-2" data-commentid="' . $id_c . '" aria-label="remove upvote">upvoted</button
                                            </div>
                                            </div>';
                                            
                                            }

                                        }
                                            ?>
                            <div>
                                <!-- Bottoni per modificare o cancellare il comenti se si e' autore -->
                            <div class="btn" role="group" aria-label="Edit and delete">
                                
                                <button type="button" class="btn btn-success edit-comment ms-2 my-2" data-commentid="<?php echo $id_c; ?>">Edit</button>
                                <!-- bottone per aprire il moda; -->
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_comment_<?php echo $id_c; ?>">Delete</button>
                                <!-- modal per cancellare eliminazione commento -->
                                <div class="modal fade" id="delete_comment_<?php echo $id_c; ?>" tabindex="-1" aria-labelledby="delete_comment?" aria-hidden="true">
                                  <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <h5 class="modal-title">Delete comment?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body text-center">
                                        <p>Are you sure you want to delete your comment? This <strong>cannot</strong> be undone.</p>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <a type="button" class="btn btn-danger comment my-2" data-commentid="<?php echo $id_c; ?>" data-bs-dismiss="modal">Delete comment</a>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    } elseif (isset($_SESSION['id_user'])) {        // query per generare i bottoni di upvote
                        $id_user = $_SESSION['id_user'];
                        $check = $conn_db->query("SELECT * FROM vote_comment WHERE id_usr = $id_user AND id_cmt = $id_c");
                        if($check->num_rows == 0){               

                            echo '
                            <div class="row">
                            <div class="col-6 px-1">
                            <span id="com-' . $id_c . '" data-commentid="' . $id_c . '" class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mx-2 likeCommentBadge"><span class="like-count">' . $n_vts . '</span> upvotes</span>
                            </div>
                            <div class="col-6 px-1">
                                <button type="button" class="btn btn-primary like-comment me-2" data-commentid="' . $id_c . '">upvote</button>
                            </div>
                            </div>
                            </div>
                            </div>';

                            } else {
                            
                            echo '
                            <div class="row">
                                <div class="col-6 px-1">                              
                                <span class="badge text-bg-warning border rounded-pill py-2 mx-2 likeCommentBadge" id="com-' . $id_c . '" data-commentid="' . $id_c . '"><span class="like-count">' . $n_vts . '</span> upvotes</span>
                                </div>
                                <div class="col-6 px-1">
                                <button type="button" class="btn btn-warning like-comment me-2" data-commentid="' . $id_c . '" aria-label="remove upvote">upvoted</button>
                                </div>
                            </div>
                            </div>
                            </div>';
                            
                            }                

                    } else {
                    ?>
                    <div>
                        <div>
                            <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mx-2 likeCommentBadge" id="<?php echo $id_c; ?>" data-postid="<?php echo $id_c; ?>"><span class="like-count"><?php echo "$n_vts"; ?></span> upvotes</span>                    
                        </div>                 
                    </div>
                    </div>
                    </div>
                    <?php
                    }
                    }
                    echo '      </div>
                    </div>
                    </div>';
                    ?>
                <?php $result->free();?>
                </div>
            </section>
    <!-- /div for col-8 -->




    <div class="col-2 ps-0">
        <div  id="ad_post">
        <!-- Controllo se l'utente e' premium -->
        <?php
            if (isset($_SESSION['id_user'])) {
                $id_u = $_SESSION['id_user'];
                $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");

                // Controlla se la query ha avuto successo
                if ($result === false) {
                    die('Errore nella query: ' . $conn_db->error);
                }

                if ($result->num_rows == 0) {
                    // Genera la pubblicita' desktop o no
                    echo '
                    <div class="container-sm bg-light" style="max-height: 100vh; overflow-y: auto;">
                    <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="max-height: 100vh; overflow-y: auto;">           
                        <img src="assets/ad-v.gif" alt="Ad Image" class="m-auto img-fluid" style="height: 100%;">
                    </div>';
                }                
                $result->free();
            } else {                
                // Genera la pubblicita' desktop o no
                echo '
                <div class="container-sm bg-light" style="max-height: 100vh; overflow-y: auto;">
                <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="max-height: 100vh; overflow-y: auto;">           
                    <img src="assets/ad-v.gif" alt="Ad Image" class="m-auto img-fluid" style="height: 100%;">
                </div>';

            }
            ?>   
    </div>
    </div>
    </div>            
</div>   

<!-- /div row -->
</div>
<!-- /div for container -->
</div>

    <!-- ajax per modificare i commenti -->
<script>
$(document).ready(function(){
    $('.edit-comment').click(function(){
        var commentid = $(this).data('commentid');
        var postid = $(this).data('postid');
        var commentElement = $('#' + commentid);
        // Verifica se il commento esiste
        if(commentElement.next('form').length > 0) {
            // Se si, estrae il form
            commentElement.next('form').remove();
            commentElement.show();
        } else {
            // Senno', lo crea
            $.ajax({
                url: 'includes/_get_comment.php',
                type: 'post',
                data: {
                    'commentid': commentid,
                    'postid': postid
                },
                success: function(response){
                    // Crea un form con il testo del commento come valore preimpotato

                    var form = '<form action="includes/_edit_comment.php" method="POST">' +
                    '<textarea class="form-control" required maxlength="2500" name="testo_commento">' + response + '</textarea>' +
                               '<input type="hidden" value="' + commentid + '" name="id_comment">' +
                               '<input type="hidden" value="' + postid + '" name="id_post">'+
                               '<button class="btn btn-success btn-sm" type="submit">Confirm</button>' +
                               '</form>';
                    // Inserisce il form acanto al commento
                    commentElement.after(form);
                    // Nasconde il vecchio elemento
                    commentElement.hide();
                }
            });
        }
    });
    // Sente la risposta
    $('.comment_section').on('submit', 'form', function(e) {
        e.preventDefault();     // previene la funzione se c'e' un errore
        var form = $(this);
        var commentid = form.find('input[name="id_comment"]').val();
        var commentText = form.find('textarea[name="testo_commento"]').val();
        $.ajax({
            url: 'includes/_edit_comment.php',
            type: 'post',
            data: form.serialize(),
            success: function(response){
                // Sostituisce il form con l'elemento p che inlcude la nuova versione del commento
                form.prev('p').replaceWith('<p class="mb-0" id="' + commentid + '">' + commentText + '</p>');
                form.remove();
            }
        });
    });
});
</script>

<!-- AJAX per cancellare commenti -->
<script>
    $(document).ready(function(){
    $(".comment").click(function(e){        // Ascolta l'evento di click
        e.preventDefault();
        var commentId = $(this).data('commentid');
        var parentDiv = $(this).parents('.card');
        $.ajax({                                //esegue la chiamata ajax al backen
            url: 'includes/_delete.php',               
            type: 'GET',
            data: {
                id: commentId,
                type: 'commento'
            },
            success: function(){            // nasconde la card dove si trova il commento cancellato
                parentDiv.hide();                         
                var commentSpan = $("body").find("#comment-<?php echo "$id_p"?>");
                var currentCount = parseInt(commentSpan.text());
                commentSpan.text((currentCount - 1) + ' comments');
            }
        });
    });
});
</script>

</body>

<?php
    $conn_db->close();
    include "includes/footer.php";
?>

</html>