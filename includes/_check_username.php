<?php
include 'Connessione_database.php';
require_once 'config.php';

// Si ottiene l'username tramite POST e si verifica se c'e' gia' nel DB o meno
$username = $_POST['username'];

$stmt = $conn_db->prepare("SELECT * FROM utente WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    echo "false";
} else {
    echo "true";
}
$stmt->close();
$conn_db->close();
?>