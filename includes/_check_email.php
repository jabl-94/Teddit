<?php
include 'Connessione_database.php';
require_once 'config.php';

// Si ottiene l'email tramite POST e si verifica se c'e' gia' nel DB
$email = $_POST['email'];

$stmt = $conn_db->prepare("SELECT * FROM utente WHERE email = ?");
$stmt->bind_param("s", $email);
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