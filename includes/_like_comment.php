<?php
// Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
include 'Connessione_database.php';
require_once 'config.php';

// Verifica se l'id del commento c'e'
if(isset($_POST['commentid'])){
    $commentid = $_POST['commentid'];
    $userid = $_SESSION['id_user'];

    // Verifica se l'utente ha gia' upvotato il commento
    $check_sql = "SELECT * FROM vote_comment WHERE id_usr = $userid AND id_cmt = $commentid";
    $check_result = $conn_db->query($check_sql);

    if($check_result->num_rows > 0){
        // Se si, lo toglie
        $sql = "DELETE FROM vote_comment WHERE id_usr = $userid AND id_cmt = $commentid";
    } else {
        // Se no, allora lo aggiunge
        $sql = "INSERT INTO vote_comment (id_usr, id_cmt) VALUES ($userid, $commentid)";
    }
    $conn_db->query($sql);
}

$conn_db->close();
?>
