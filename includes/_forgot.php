<?php
// Inclusione del file per la connessione al database e del file di configurazione
include 'Connessione_database.php';
require_once 'config.php';

$security_question = '';

// Verifica se la richiesta è di tipo POST e inizializza le variabili
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $_SESSION['email'] = $email;

    // Query SQL per ottenere la domanda di sicurezza dell'utente
    $query = "SELECT security_question.question FROM utente 
    JOIN security_question ON utente.id_quest = security_question.id_question 
    WHERE utente.email = ?";

    // Prepara la query SQL, i parametri e la esegue
    $stmt = $conn_db->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();

    // Ottieni il risultato della query
    $result = $stmt->get_result();
    // Verifica se ci sono righe nel risultato e invia la domanda di sicurezza al frontend
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $security_question = $row['question'];
        echo json_encode($security_question);
        $result->free();
    } else {
        // Se non ci sono righe nel risultato, restituisce un messaggio di errore
        echo json_encode("No user found with that email.");
    }
    // si chiude lo statement e la conn. al DB
    $stmt->close();
    $conn_db->close();
}
?>
