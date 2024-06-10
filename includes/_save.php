<?php
include 'Connessione_database.php';
require_once 'config.php';

//Verifica che ci sia il post id e la variabile dell'utente
if(isset($_POST['postid'])){
    $postid = $_POST['postid'];
    $userid = $_SESSION['id_user'];

    // Fa la query per vedere se l'utente ha salvagto il post o meno
    $check_sql = "SELECT * FROM saves WHERE id_a = $userid AND id_po = $postid";
    $check_result = $conn_db->query($check_sql);
    if($check_result->num_rows > 0){
        // Lo cancella se l'utente aveva gia' salvato il post
        $sql = "DELETE FROM saves WHERE id_a = $userid AND id_po = $postid";
    } else {
        // Fa l'insert se l'utente non lo aveva salvato
        $sql = "INSERT INTO saves (id_a, id_po) VALUES ($userid, $postid)";
    }
    // Esegue la query
    if($conn_db->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn_db->error;
    }
}

$conn_db->close();
?>