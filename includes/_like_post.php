<?php  
// Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
include 'Connessione_database.php';
require_once 'config.php';

// Verifica se l'id del post c'e'
if(isset($_POST['postid'])){
    $postid = $_POST['postid'];
    // Verifica se l'utente ha gia' upvotato il post
    $check_sql = "SELECT * FROM vote_post WHERE id_ur = ".$_SESSION['id_user']." AND id_pt = ".$postid;
    $check_result = $conn_db->query($check_sql);
    if($check_result->num_rows > 0){
        // Se si, lo toglie
        $sql = "DELETE FROM vote_post WHERE id_ur = ".$_SESSION['id_user']." AND id_pt = ".$postid;
    } else {
        // Se no, allora lo aggiunge
        $sql = "INSERT INTO vote_post (id_ur, id_pt) VALUES (".$_SESSION['id_user'].", ".$postid.")";
    }
    $conn_db->query($sql);
}

$conn_db->close();
