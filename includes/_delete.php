<?php 
  // Inclusione del file per la connessione al database e del file di configurazione
  include 'Connessione_database.php';
  require_once 'config.php';
?>

<?php 
  // Inizializza le variabili per i valori inviati dal backend
  $id_to_delete = urldecode($_GET['id']);
  $content_type = urldecode($_GET['type']);

  // Se il type == post, prosegue con l'eliminazione di esso
  if($content_type == 'post'){
    // Si fa la query il database per ottenere i nomi delle immagini dalla tabella "image"
    $result = $conn_db->query("SELECT `path` FROM `image` WHERE id_pst = '$id_to_delete'");
    while($row = $result->fetch_assoc()){
        $file_path = $row['path'];
        if (file_exists('../post_images/' . $file_path)) {
            unlink('../post_images/' . $file_path);
        }
    }
    $result->free();
    // Si fa la query il database per cancellare le immagini e il post
    $query = "DELETE FROM `image` WHERE id_pst = '".$id_to_delete."'";
    $result = $conn_db->query($query);
    
    $query = "DELETE FROM post WHERE id_post = ".$id_to_delete;
    $result = $conn_db->query($query);
    
    //Si inizializza la variabile di sessione id_blog e ci rimandiamo a la pagina del blog cui post e' stato eliminato
    $_SESSION['id_blog'] = urldecode($_GET['id_blog']);
    header("Location: ../blog.php");
    exit(); 
  }

  // Se il type == blog, prosegue con l'eliminazione di esso
  else if($content_type == 'blog') {
    // Preparo la query per cancellare le immagini del blog dal server nel caso questo venga cancellato
    $result = $conn_db->query("SELECT img FROM blog WHERE id_blog = '$id_to_delete'");
    $row = $result->fetch_assoc();
    $file_path = $row['img'];
    // Verifico che esista un'immagine da cancellare e che non sia quella di default
    if (file_exists('../images/' . $file_path)) {
        if ($file_path != "1.png") {
            unlink('../images/' . $file_path);
        }
    }
    $result->free(); 
    
    // Preparo la query per cancellare il blog dal DB
    $query = "DELETE FROM blog WHERE id_blog = " . $id_to_delete;
    $result = $conn_db->query($query);
    // Invio l'utente alla pagina di contenuto cancellato
    header("Location: ../content_deleted.php");
    exit(); 
}
  // Se il type == commento, prosegue con l'eliminazione di esso
  else if($content_type == 'commento') {

    $query = "DELETE FROM commento WHERE id_comment = " . $id_to_delete;
    $result = $conn_db->query($query);

  }
$conn_db->close();
?>