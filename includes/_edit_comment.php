<?php
// Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
include 'Connessione_database.php';
require_once 'config.php';

// Verifica se sono state inviati l'id e il testo del commento tramite POST e inizializza le variabili
if(isset($_POST['id_comment']) && isset($_POST['testo_commento']) && isset($_POST['id_post'])) {
    $commentid = $_POST['id_comment'];
    $commentText = $_POST['testo_commento'];
    $postid = $_POST['id_post'];

    // Prepara lo statement, i suoi parametri e lo esegue
    $stmt = $conn_db->prepare("UPDATE commento SET testo = ? WHERE id_comment = ?");
    $stmt->bind_param("si", $commentText, $commentid);
    $stmt->execute();

    // Verifica se il commento e' stato aggornato
    if($stmt->affected_rows > 0) {
        echo $commentText; // Ritorna il commento aggiornato
    } else {
        echo "Failed to update comment";
    }

    // Chiude lo statement
    $stmt->close();
}

// Chide la conn. al DB
$conn_db->close();
?>