<?php
  include 'Connessione_database.php';
  require_once 'config.php';
  include "_tempotrascorso.php"; 

  // S'inizializzano le variabili ottenute tramite GET   
  $searchType = $_GET['type'];
  $keyword = $_GET['keyword'];

// Switch per ogni caso per cambiare i risultati
  switch ($searchType) {
    case 'blog':
        $stmt = $conn_db->prepare("SELECT * FROM blog JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat
        WHERE blog.title LIKE ? OR blog.description LIKE ? ORDER BY blog.id_blog DESC");

        $stmt->bind_param("ss", $keyword, $keyword);    
        break;

    case 'post':
        // Replace with your actual query for Posts
        $stmt = $conn_db->prepare("SELECT * FROM post JOIN utente ON post.id_u = utente.id_user JOIN blog ON post.id_b = blog.id_blog
        WHERE post.title_post LIKE ? OR post.text_post LIKE ? ORDER BY post.id_post DESC");

        $stmt->bind_param("ss", $keyword, $keyword);
        break;

    case 'category':
        // Replace with your actual query for Categories
        $stmt = $conn_db->prepare("SELECT * FROM category WHERE category.name LIKE ? ORDER BY category.id_cat DESC");

        $stmt->bind_param("s", $keyword);
        break;

    case 'user':
        $stmt = $conn_db->prepare("SELECT * FROM utente WHERE utente.username LIKE ? ORDER BY utente.id_user DESC");

        $stmt->bind_param("s", $keyword);
        break;

    case 'author':
            $stmt = $conn_db->prepare("SELECT * FROM utente WHERE utente.username LIKE ? 
            AND (utente.id_user IN (SELECT id_author FROM blog) OR utente.id_user IN (SELECT id_aut FROM co_manages))
            ORDER BY utente.id_user DESC");

            $stmt->bind_param("s", $keyword);
            break;
    default:
      echo "Invalid search type.";
      exit();
  }
  
  // Esegui lo statement
  $stmt->execute();

  // VErifica che la query abbia avuto successo
  if ($stmt === false) {
    die('Error in query: ' . $conn_db->error);
  }
  
  // Inizializza la variabile con i risultati
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    echo '  <div class="d-flex flex-column justify-content-center align-items-center" style="height: 89vh;">
                <h1 class="mx-auto mt-auto">No results found... please try another tab</h1>
                <img src="assets/fine.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2" style="max-height: 15rem; object-fit: contain;" alt="">
            </div>';
} else {
  

  // Si generano le card per ogni risultato per ogni caso
  while ($row = $result->fetch_assoc()) {
    switch ($searchType) {
        case 'blog':
            echo '<div class="card d-flex flex-column mb-1 my-3 mx-3" style="max-width: 70rem;">
            <div class="row g-0">
            <div class="col-2 d-flex justify-content-center align-items-center">
                <img src="images/'.$row['img'].'" class="card-img-top py-0" style="max-height: 6rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="blog image">
            </div>
            <div class="col-md-8 col-6">
                <div class="card-body" style="position: relative;">
                        <h5 class="card-title blog"><a href="blog.php?id_blog=' . urlencode($row['id_blog']) . '" class="stretched-link">'. 't/' . $row['title'] .'</a></h5>
                        <p class="card-text text-truncate mb-2 post" style="position: relative; z-index: 2;"><a class="post_card" href="profile.php?id_user=' . urlencode($row['id_author']) . '">'. 'u/' . $row['username'] .'</a></p>
                        <p class="card-text text-truncate blog_description">'. $row['description'] .'</p>
                </div>
            </div>
            <div class="col-md-2 col-4 py-0">
                <div class="card-body text-end px-1">
                    <div>
                        <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2 blog_cat category"><a href="category.php?id_cat=' . urlencode($row['id_cat']) . '">'. $row['name'] .'       </a></span>
                    </div>
                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2" id="'. $row['id_blog'] .'" data-blogid="'. $row['id_blog'] .'">'. $row['n_followers'] .'  followers</span>
                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-4">'. ($row['n_coauthors']+1) .' authors</span>';
            
            // Genera bottoni follow
            if (isset($_SESSION['id_user'])) {
                if ($row['id_author'] != $_SESSION['id_user']) {
                    $id_user = $_SESSION['id_user'];
                    $id_b = $row['id_blog'];
                    $check = $conn_db->query("SELECT * FROM follows WHERE id_auth = $id_user AND id_b = $id_b");
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

            break;

        // Genera card per i post e i bottoni, contenuti ecc.
        case 'post':
            
            echo '<div class="my-1 col-12">
            <div class="card" style="height: auto">
                <div class="card-body pt-1">
                    <p class="card-text mb-3 blog"><a href="blog.php?id_blog=' . urlencode($row['id_blog']) . '"><strong><small>'. 't/' . $row['title'] .'</small></strong></a><small><small>● '. tempoTrascorso($row['date_time']) .'</small></small></p>
                    <p class="h4 card-title post"><a href="post.php?id_post=' . urlencode($row['id_post']) . '">'. $row['title_post'] .'</a></p>
                    <p class="card-text text-truncate mt-3">'. $row['text_post'] .'</p>
                    
                </div>';
                
                $id_p = $row['id_post'];
                $youtube_link = $row['link'];
                // Controlla se c'e' un video
                if (!empty($youtube_link)) {
                    // Estrae l'id del video YouTube
                    preg_match('/v=([^&]+)/', $youtube_link, $matches);
                    $video_id = $matches[1];

                    // Mostra iframe per il video YouTube
                    echo '
                    <div class="h-100 text-center mx-1 d-flex justify-content-center my-2 px-2">
                        <div class="ratio ratio-16x9" style="max-width: 90%;">
                            <iframe src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>';
                } else {
                        $subresult = $conn_db->query("SELECT path FROM image WHERE id_pst = '$id_p' ORDER BY id_image ASC");
                            
                        // Verifica se c'e' solo un'immagine
                        if ($subresult->num_rows == 1) {
                            // SE si, allora la mostra
                            $imageRow = $subresult->fetch_assoc();
                            echo '<div class="h-100 px-2">
                                    <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                                   </div>
                                   ';
                        //  Se ci sono piu' immagini allora genera il carousel Bootstrap    
                        } elseif ($subresult->num_rows > 1) {
                            
                        echo '<div class="">
                                <div id="car-'.$row['id_post'].'" class="carousel slide">
                                    <div class="carousel-inner" style="height: auto;">';
                        $firstImage = true; // Segna che questa e' la prima immagine
                        while($imageRow = $subresult->fetch_assoc()){
                            // Mostra ogni immagine
                            if($firstImage) {
                                echo '<div class="carousel-item active h-100 px-2">
                                        <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                                    </div>';
                                $firstImage = false; // Segna che le seguenti non lo sono
                            } else {
                                echo '<div class="carousel-item h-100 px-2">
                                  <img src="post_images/' . $imageRow['path'] . '" class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" alt="Post image">
                                </div>';
                            }
                        }
                        echo '</div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#car-'.$row['id_post'].'" data-bs-slide="prev">
                                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#car-'.$row['id_post'].'" data-bs-slide="next">
                                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                  <span class="visually-hidden">Next</span>
                                </button><br>
                                </div>
                                </div>';
                        }
                $subresult->free();
                    }
    
    echo        '<div class="card-footer d-flex flex-column flex-sm-row align-items-center py-1">';
    
    // Bottoni upvote
    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        $id_pt = $row['id_post'];
        $check = $conn_db->query("SELECT * FROM vote_post WHERE id_ur = $id_user AND id_pt = $id_pt");
        if($check->num_rows == 0){               
            
            echo '
            <div class="mb-2">
                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1 like-count-badge" id="'. $row['id_post'] .'" data-postid="'. $row['id_post'] .'"><span class="like-count">'. $row['n_votes'] .'</span> upvotes</span>
                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1" id="'. $row['id_post'] .'" data-postid="'. $row['id_post'] .'">'. $row['n_comments'] .'  comments</span>
            </div>
            <div class="mb-2">
                <button type="button" class="btn btn-primary like-post me-2 mb-1" data-postid="'. $row['id_post'] .'">upvote</button>';
            
            } else {
            
            echo '
            <div class="mb-2">
                <span class="badge text-bg-warning border rounded-pill py-2 me-2 mb-1 like-count-badge" id="'. $row['id_post'] .'" data-postid="'. $row['id_post'] .'"><span class="like-count">'. $row['n_votes'] .'</span> upvotes</span>
                <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1" id="'. $row['id_post'] .'" data-postid="'. $row['id_post'] .'">'. $row['n_comments'] .'  comments</span>
            </div>
            <div class="mb-2">
                <button type="button" class="btn btn-warning like-post me-2 mb-1" data-postid="'. $row['id_post'] .'">upvoted</button>';
            
            }
    } else {
        echo '
        <div class="mb-2">
            <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1" id="'. $row['id_post'] .'" data-postid="'. $row['id_post'] .'"><span class="like-count">'. $row['n_votes'] .'</span> upvotes</span>
            <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 me-2 mb-1" id="'. $row['id_post'] .'" data-postid="'. $row['id_post'] .'">'. $row['n_comments'] .'  comments</span>
        </div>';
    }
    // Bottoni per salvare i post
    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
        $id_p = $row['id_post'];
        $check = $conn_db->query("SELECT * FROM saves WHERE id_a = $id_user AND id_po = $id_p");
        if($check->num_rows == 0){               
            
            echo '<button type="button" class="btn btn-primary me-2 mb-1" id="save-post" data-postid="'. $row['id_post'] .'">Save post</button>';
            
            } else {
            
            echo '<button type="button" class="btn btn-success me-2 mb-1" id="save-post" data-postid="'. $row['id_post'] .'">Saved</button>';
            
            }
    }
    echo '      </div>
                </div>
            </div>
        </div>';
            break;

        // Genera le card per il caso 'Category'
        case 'category':
            // Generate category content
            echo '<div class="card d-flex flex-column mb-1 my-3 mx-3" style="max-width: 70rem;">
                    <div class="row g-0">
                        <div class="col-12">
                            <div class="card-body">
                                <h5 class="card-title text-truncate category"><a class="stretched-link" href="category.php?id_cat=' . urlencode($row['id_cat']) . '">'. $row['name'] .'</a></h5>
                            </div>
                        </div>
                    </div>
                </div>';
            break;
        
        // Genera le car per gli user
        case 'user':
                // Generate user content
                echo '<div class="card d-flex flex-column mb-1 my-3 mx-3 py-1" style="max-width: 70rem;">
                        <div class="row g-0">
                            <div class="col-2 m-auto">
                                <img src="propics/'.$row['propic'].'" class="card-img-top py-0" style="max-height: 6rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="" alt="blog image">
                            </div>
                            <div class="col-10">
                                <div class="card-body">
                                <h5 class="card-title text-truncate profile"><a class="stretched-link" href="profile.php?id_user=' . urlencode($row['id_user']) . '">'. $row['username'] .'</a></h5>
                                <p class="card-text text-truncate">'. $row['bio'] .'</p>
                            </div>
                            </div>
                        </div>
                    </div>';
            break;
        // Genera le card per i coautori
        case 'author':
                // Generate user content
                echo '<div class="card d-flex flex-column mb-1 my-3 mx-3 py-1" style="max-width: 70rem;">
                        <div class="row g-0">
                            <div class="col-2 m-auto">
                                <img src="propics/'.$row['propic'].'" class="card-img-top py-0" style="max-height: 6rem; object-fit: contain; clip-path: circle(40%); background-color:gray;" alt="" alt="blog image">
                            </div>
                            <div class="col-10">
                                <div class="card-body">
                                <h5 class="card-title text-truncate profile"><a class="stretched-link" href="profile.php?id_user=' . urlencode($row['id_user']) . '">'. $row['username'] .'</a></h5>
                                <p class="card-text text-truncate">'. $row['bio'] .'</p>
                            </div>
                            </div>
                        </div>
                    </div>';
            break;
    }
}
}
$stmt->close();
$result->free();
$conn_db->close();
?>