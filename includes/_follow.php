<?php
// Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
include 'Connessione_database.php';
require_once 'config.php';

// Controlla che ci siano i valori e inizializza le variabili
if(isset($_POST['blogid']) && isset($_SESSION['id_user'])){
    $blogid = $_POST['blogid'];
    $userid = $_SESSION['id_user'];

    // Verifica se l'utente gia' seguiva il blog
    $check_sql = "SELECT * FROM follows WHERE id_auth = $userid AND id_b = $blogid";
    $check_result = $conn_db->query($check_sql);
    if($check_result->num_rows > 0){
        // Se si, rimuove il follow
        $sql = "DELETE FROM follows WHERE id_auth = $userid AND id_b = $blogid";
    } else {
        // Se no, aggiunge il follo
        $sql = "INSERT INTO follows (id_auth, id_b) VALUES ($userid, $blogid)";
    }
    // Si esegue la query a seconda della condizione
    if($conn_db->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn_db->error;
    }
}
// Si chiude la connessione al DB
$conn_db->close();
?>
