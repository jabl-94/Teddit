<?php
  include 'Connessione_database.php';
  require_once 'config.php';

  // S'inizializza la variabile del tipo di richiesta
  $type = $_POST['type'];
    // Se il tipo e' 'autorizza', procede con l'autorizzazione
    if ($type == 'autorizza') {
        $id_blog_user = $_POST['id_blog_user'];
        $parts = explode('|', $id_blog_user);
        $id_user = $parts[0];
        $id_blog = $parts[1];
        // Si inserisce l'use ID e il blog ID nella tabella comanages
        $result = $conn_db->query("INSERT INTO co_manages (id_aut, id_bl) VALUES ('$id_user', '$id_blog')");    
        
        if ($result === false) {
            die('Errore nella query: ' . $conn_db->error);
        }
        $conn_db->close();
        header("Location: ../profile.php?id_user=" . urlencode($id_user));
        exit();
      }
      // Se il tipo e' 'revoca', procede con la cancelazzione
      if ($type == 'revoca') {
        $id_user = $_POST['id_user'];
        $id_blog = $_POST['id_blog'];
      // Si cancella la riga che contiene la combinazione di user ID e blog ID
          $result = $conn_db->query("DELETE FROM co_manages WHERE id_aut = '$id_user' AND id_bl = '$id_blog'");
          if ($result === false) {
              die('Errore nella query: ' . $conn_db->error);
          }
          $_SESSION['id_blog'] = $id_blog;
          // Si chiude la conessione e si riinvia alla pagina del blog
          $conn_db->close();
          header("Location: ../blog.php");
          exit();
      }
      
?>