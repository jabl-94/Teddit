<?php 
  // Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
  include 'Connessione_database.php';
  require_once 'config.php';
?>

<?php
// Si inizializano le variabili inviati dal front-end
$email = isset($_POST['email']) ? trim($_POST['email']): '';
$answer = isset($_POST['answer'])  ? trim($_POST['answer']) : '';

// Verifica se la risposta è vuota
if (empty($answer)) {
    $error_message = "No answer was provided. Please insert one";
} else{
    $answer = mysqli_real_escape_string($conn_db, $answer);
}

// Preparazione della query SQL
$query = "SELECT sec_answer,username FROM utente WHERE email = ?";
$stmt = $conn_db->prepare($query);

// Verifica che la preparazione della query sia andata a buon fine
if ($stmt === false) {
    die('Errore nella preparazione: ' . $conn_db->error);
}

// Preparazione dei parametri per la query SQL
$stmt->bind_param('s', $email);

// Si esegue la query
$stmt->execute();

// Si salvano i risultati
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    // Si assegnano i risultati alle variabili
    $db_username = $row['username'];
    $db_sec_answer = $row['sec_answer'];
}
$result->free();

// Verifica se la risposta corrisponde a quella nel DB
if($answer === $db_sec_answer){
    // Se coincidono, allora viene inizializzata la variabile di sessione
        echo "Success";
    $_SESSION['user'] = $db_username;
    
    // Si chiude la connessione al DB
    $conn_db->close();
    
    // Si invia l'utente alla pagina di reset della password
    header("Location: ../reset.php");
    exit();
} else {
    // Messaggio di errore se la risposta non corrisponde
    echo "Security answer does not match";
    
    // Si eliminano le variabili di sessione
    session_unset();
    
    // Si chiude la connessione al DB
    $conn_db->close(); 
    
    // Si invia l'utente alla pagina di recupero della password
    header("Location: ../forgot.php");
    exit();
    
}
?>