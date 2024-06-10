<?php
include 'Connessione_database.php';
require_once 'config.php';

// Verifica se c'e' l'id  del comment
if(isset($_POST['commentid'])) {
    $commentid = $_POST['commentid'];

    // Crea la query SQL
    $query = "SELECT testo FROM commento WHERE id_comment = " . $commentid;

    // Esegue la query
    $result = $conn_db->query($query);

    // Verifica se ha trovato il commento
    if($result->num_rows > 0) {
        // lo salva
        $row = $result->fetch_assoc();

        // Lo ritorna
        echo $row['testo'];
        $result->free();
    } else {
        echo "No comment found";
    }
}

// Chiude la conessione al DB
$conn_db->close();
?>