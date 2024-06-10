<?php 
  // Inclusione del file per la connessione al database e del file di configurazione
  include 'Connessione_database.php';
  require_once 'config.php';

  //Inizializza le variabili con i valori inviati dal frontend
  $id_to_delete = urldecode($_GET['id']);
  $id_post = urldecode($_GET['id_post']);
  $content_type = urldecode($_GET['type']);
  $username = urlencode($_GET['username']);

// Se il content_type e' post allora:
if($content_type == 'post'){
  // Query al DB per estrarre i nomi delle immagini da cancellare dal server
  $result = $conn_db->query("SELECT `path` FROM `image` WHERE id_image = '$id_to_delete'");
  $row = $result->fetch_assoc();
  $file_path =$row['path'];
  if (file_exists( '../post_images/' . $file_path)) {
      unlink( '../post_images/' . $file_path);
  }

  // Query al DB per elimnare le immagini del post
  $conn_db->query("DELETE FROM `image` WHERE id_image = '$id_to_delete'");
  if($conn_db->error)
      die("QUERY FAILED". $conn_db->error);
    
    $result->free();
    // si riinvia l'utente alla pagina edit post
    header("Location: ../edit_post.php?id_post=" . urlencode($id_post));
    exit();   

// Altirimenti se e' blog, prosegue a fare:
} else if($content_type == 'blog') {
    // Query al DB per avere i nomi della immagine da cancellare dal server
    $result = $conn_db->query("SELECT img FROM blog WHERE id_blog = '$id_to_delete'");
    $row = $result->fetch_assoc();
    $file_path = $row['img'];
    // Se l'immagine c'e', l'elimina
    if (file_exists('../images/' . $file_path)) {
        unlink('../images/' . $file_path);
    } 

    // Query al DB per ripristinare l'immagine di default al blog
    $conn_db->query("UPDATE blog SET img = '1.png' WHERE id_blog = '$id_to_delete'");
    if($conn_db->error)
        die("QUERY FAILED". $conn_db->error);

    $result->free();
    // Si riinvia l'utente alla pagina edit blog
    header("Location: ../edit_blog.php?id_blog=" . urlencode($id_to_delete));
    exit();

  } else if ($content_type == 'profile') {
    // Query al DB per ottenere il nome dell'immagine di profilo da eliminare dal server
    $result = $conn_db->query("SELECT propic FROM utente WHERE username = '$username'");
    $row = $result->fetch_assoc();
    $file_path = $row['propic'];
    if (file_exists('../propics/' . $file_path)) {
        unlink('../propics/' . $file_path);
    } 

    // Query al DB per ripristinare l'immagine di default al utente
    $conn_db->query("UPDATE utente SET propic = '0.png' WHERE username = '$username'");
    if($conn_db->error) {
        die("QUERY FAILED". $conn_db->error);
    }

    $result = $conn_db->query("SELECT * FROM utente WHERE username = '$username'");
    $row = $result->fetch_assoc();
    $_SESSION['propic'] = $row["propic"];
    $result->free();
    // Rimanda l'utente alla pagina di profilo
    header("Location: ../profile.php");
    exit();
  }
$conn_db->close();
?>