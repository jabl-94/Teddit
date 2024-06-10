<?php 
  // Includiamo i file neccessari per la struttura della pagina 
  include "includes/head.php";
  include "includes/header.php"; 
  include "includes/sidebar.php";
?>
<?php
    // Riceve la query della search bar tramite GET
    $search = "%" . $_GET['search'] . "%";
?>

<!-- media queries per responsiveness -->
<style>
.carousel-control-prev-icon,
.carousel-control-next-icon {
  background-color: black;
}

@media only screen and (max-width: 768px) {
  .blog_description {
    display: none;
  }

  .blog_cat {
    display: none;
  }

  #ad_search {
    display: none;
  }
}


</style>

<body class="bg-light">
<div class="col-md-8 col-10 px-0">
        <div class="ps-4 pt-2 ms-0 mb-0 bg-light">
            <h2 class="mb-0">Results</h2>
        </div>

        <!-- pubblicita per diventare premium -->
        <?php
        // Verifica se l'utente loggato e' premum o no
        if (isset($_SESSION['id_user'])) {
            $id_u = $_SESSION['id_user'];
            $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");

            // VErifica se la query ha avuto successo
            if ($result === false) {
                die('Errore nella query: ' . $conn_db->error);
            }

            // Mostra la pubblicita' se l'utente loggato non e' premium
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
            // Mostra la pubblicita' se l'utente non e' loggato
            echo '
            <div class="container-sm row px-2 ms-0 me-2" id="ad_mobile" style="max-width: 90%; background-color: #87ceeb;" aria-label="Advertisement">
                <div class="h-100 justify-content-center align-items-center">
                    <a class="mx-4" href="login.php" aria-label="link to login"><img class="d-block mx-auto h-100 img-fluid py-2" style="max-height: 20rem; object-fit: contain;" src="assets/getPremium.png" alt="get premium"></a>
                </div>
            </div>';

        } 
    ?> 
        <!-- Bottoni per cambiare risultati -->
        <div class="d-flex flex-wrap flex-sm-row gap-1 justify-content-center py-1 me-0 ms-0 bg-light" id="changeResults" aria-label="change result tab">
            <div class="btn py-0" aria-label="">
                <button data-type="blog" onclick="changeResults(this, '<?php echo urlencode($search) ?>')" class="btn btn-outline-info d-inline-flex active" type="button"><strong>Blogs</strong></button>
            </div>
            <div class="btn py-0" aria-label="">
                <button data-type="post" onclick="changeResults(this, '<?php echo urlencode($search) ?>')" class="btn btn-outline-info d-inline-flex" type="button"><strong>Posts</strong></button>
            </div>
            <div class="btn py-0" aria-label="">
                <button data-type="category" onclick="changeResults(this, '<?php echo urlencode($search) ?>')" class="btn btn-outline-info d-inline-flex" type="button"><strong>Categories</strong></button>
            </div>
            <div class="btn py-0" aria-label="">
                <button data-type="user" onclick="changeResults(this, '<?php echo urlencode($search) ?>')" class="btn btn-outline-info d-inline-flex" type="button"><strong>Users</strong></button>
            </div>
            <div class="btn py-0" aria-label="">
                <button data-type="author" onclick="changeResults(this, '<?php echo urlencode($search) ?>')" class="btn btn-outline-info d-inline-flex" type="button"><strong>Authors</strong></button>
            </div>
        </div>         
    <!-- Sezione per mostrare i risultati -->
    <section class="container-sm ps-0 pe-0 g-0 my-0 py-0">
        <div class="post_section d-flex flex-column container-sm px-0 my-0 py-0 bg-light" style="max-height: 88vh; overflow-y: auto;">
        <?php
                $stmt = $conn_db->prepare("SELECT * FROM blog JOIN utente ON blog.id_author = utente.id_user JOIN category ON blog.id_category = category.id_cat
                WHERE blog.title LIKE ? OR blog.description LIKE ? ORDER BY blog.id_blog DESC ");

                $stmt->bind_param("ss", $search, $search);
                
                $stmt->execute();

                // Verifica se la query ha avuto successo
                if ($stmt === false) {
                    die('Errore nella query: ' . $conn_db->error);
                }
                $result = $stmt->get_result();
                // Mostra 'nothing found' se non e' stato trovato nulla
                if ($result->num_rows == 0) {
                    echo '  <div class="d-flex flex-column justify-content-center align-items-center" style="height: 89vh;">
                                <h1 class="mx-auto mt-auto">No results found... please try another tab</h1>
                                <img src="assets/fine.jpg" class="d-block mx-auto mb-auto h-100 img-fluid px-2" style="max-height: 15rem; object-fit: contain;" alt="">
                            </div>';
                } else {


                // Genera le card con i risultati
                while ($blogRow = $result->fetch_assoc()) {
                    echo '<div class="card d-flex flex-column mb-1 my-3 mx-3" style="max-width: 70rem;">
                            <div class="row g-0">
                            <div class="col-2 d-flex justify-content-center align-items-center">
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
                                <div class="card-body text-end px-1">
                                    <div>
                                        <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2 blog_cat category"><a href="category.php?id_cat=' . urlencode($blogRow['id_cat']) . '">'. $blogRow['name'] .'       </a></span>
                                    </div>
                                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-2" id="'. $blogRow['id_blog'] .'" data-blogid="'. $blogRow['id_blog'] .'">'. $blogRow['n_followers'] .'  followers</span>
                                    <span class="badge bg-info-subtle border border-info-subtle text-info-emphasis rounded-pill py-2 mb-4">'. ($blogRow['n_coauthors']+1) .' authors</span>';
                            // Genera bottoni follow
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
            }
                $stmt->close();
                $result->free();
                ?>
        </div>



    </section>


<!-- /div for col-8 -->
</div>
<div class="col-2" id="ad_search">
            <!-- Verifica se l'utente e' premium -->
            <?php
                if (isset($_SESSION['id_user'])) {
                    $id_u = $_SESSION['id_user'];
                    $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");
                    
                    // Verifica se la query ha avuto successo
                    if ($result === false) {
                        die('Errore nella query: ' . $conn_db->error);
                    }

                    // Mostra la pubblicita' se l'utente non e' premium
                    if ($result->num_rows == 0) {
                        echo '
                        <div class="container-sm bg-light" style="max-height: 100vh; overflow-y: auto;" aria-label="Advertisement">
                        <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="max-height: 100vh; overflow-y: auto;">           
                            <img src="assets/ad-v.gif" alt="Ad Image" class="m-auto img-fluid" style="height: 100%;">
                        </div>';
                    }                
                    $result->free();
                } else {
                    // Mostra la pubblicita' se l'utente non e' loggato
                    echo '
                    <div class="container-sm bg-light" style="max-height: 100vh; overflow-y: auto;" aria-label="Advertisement">
                    <div class="d-flex flex-column justify-content-center align-items-center bg-light" style="max-height: 100vh; overflow-y: auto;">           
                        <img src="assets/ad-v.gif" alt="Ad Image" class="m-auto img-fluid" style="height: 100%;">
                    </div>';

                }
                ?>   
<!-- /div for container -->
</div>
</div>

<!-- AJAX per cambiare i risultati -->
<script>
function changeResults(button, search) {
    // Rimuove la classe 'active' da tutti i bottone
    var buttons = document.querySelectorAll('.btn-outline-info');
    buttons.forEach(function(btn) {
        btn.classList.remove('active');
    });
    // Aggiunge la classe 'active' al bottone
    button.classList.add('active');

    // Ottiene il tipo di risultato dal bottone
    var type = button.getAttribute('data-type');

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".post_section").innerHTML = this.responseText;
        }
    };
    xhttp.open("GET", "includes/_change_results.php?keyword=" + search + "&type=" + type, true);
    xhttp.send();
}
</script>

</body>

<?php
    $conn_db->close();
    include "includes/footer.php"; 
?>

</html>