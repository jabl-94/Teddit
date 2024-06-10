<?php
// Parametri di connessione al database
$servername = "localhost"; // Indirizzo del server MySQL
$username = "root"; // Nome utente del database
$password = ""; // Password del database
$dbname = "borges_pastorelli"; // Nome del database a cui connettersi
// Connessione al database
$conn_db = new mysqli($servername, $username, $password, $dbname);
// Verifica della connessione
if ($conn_db->connect_error) {
die("Connessione fallita: " . $conn_db->connect_error);
}

?>