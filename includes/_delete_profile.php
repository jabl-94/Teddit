<?php 
// Inclusione del file per la connessione al database e del file di configurazione
include 'Connessione_database.php';
require_once 'config.php';

$id_user = $_SESSION['id_user'];

// Fa la query al DB per ottenere i post dell'utente
$result = $conn_db->query("SELECT id_post FROM post WHERE id_u = '$id_user'");
while($row = $result->fetch_assoc()){
    $id_post = $row['id_post'];
    // Query al DB per ottenere i nomi delle immagini del post da eliminare dal DB
    $result_img = $conn_db->query("SELECT `path` FROM `image` WHERE id_pst = '$id_post'");
    while($row_img = $result_img->fetch_assoc()){
        $file_path = $row_img['path'];
        if (file_exists('../post_images/' . $file_path)) {
            unlink('../post_images/' . $file_path);
        }
    }
}
$result->free();

// Fa la query al DB per ottenere i blog dell'utente
$result = $conn_db->query("SELECT id_blog, img FROM blog WHERE id_author = '$id_user'");
while($row = $result->fetch_assoc()){
    $file_path = $row['img'];
    if (file_exists('../images/' . $file_path)) {
        // VErifica che l'immagine del blog non sia quella di default e poi la elimina 
        if ($file_path != "1.png") {
            unlink('../images/' . $file_path);
        }
    }
}
$result->free();

// Query al DB per cancellare l'utente
$conn_db->query("DELETE FROM utente WHERE id_user = '$id_user'");

// Chiude e distrugge la sessione
session_unset();
session_destroy();   

// Chiude conn. al DB e manda l'utente alla home
$conn_db->close();
header("Location: ../home.php");
exit();
?>
