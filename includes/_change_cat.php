<?php
  include 'Connessione_database.php';
  require_once 'config.php';

// Riceve l'id della categoria tramite GET 
$id_cat = urldecode($_GET['id_cat']);

  // Si estragono i blog che hanno quell'id della categoria sia in id_parent come id_cat 
  $result = $conn_db->query("SELECT * FROM blog 
  JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat
  WHERE category.id_parent = '$id_cat' OR category.id_cat = '$id_cat' ORDER BY blog.id_blog DESC");

    // Controlla se la query ha avuto successo
    if ($result === false) {
        die('Errore nella query: ' . $conn_db->error);
    }   
    // Genera le card per ogni blog
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

      // Genera bottoni follow blog
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
$conn_db->close();
?>