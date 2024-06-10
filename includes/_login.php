<?php 
    // Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
    include 'Connessione_database.php';
    require_once 'config.php';
?>

<?php
// Si inizializano le variabili inviate dal frontend e si fa il trim
$email = isset($_POST['email'])  ? trim($_POST['email']) : '';
$password = isset($_POST['password'])  ? trim($_POST['password']) : '';

// Verifico che l'email o la password non siano vuoti
if (empty($email)) {
    $error_message = "No email provided. Please insert one";
    echo "No email provided. Please insert one";
} elseif (empty($password)) {
    $error_message = "No password provided.";
    echo "No password provided.";
} else {
    $password = mysqli_real_escape_string($conn_db, $password);
}

// Si fa il hash della password
$password = hash('sha256', $password);

// Si prepara la query e lo statement 
$query = "SELECT * FROM utente WHERE email = ?";
$stmt = $conn_db->prepare($query);

// Verifico se tutto OK
if ($stmt === false) {
    die('Errore nella preparazione: ' . $conn_db->error);
}

// Si preparano i parametri
$stmt->bind_param('s', $email);

// Si esegue la query
$stmt->execute();

// Si inizializza la variabile con i risultati
$result = $stmt->get_result();

while($row = $result->fetch_assoc()){
    // Si itera per inizializzare le variabili con le info dell'utente
    $id_user = $row['id_user'];
    $username = $row['username'];
    $db_email = $row['email'];
    $db_pass = $row['pass'];
    $sec_answer = $row['sec_answer'];
    $propic = $row['propic'];
    $id_quest = $row['id_quest'];
}
$result->free(); 
$stmt->close();
// Verifica se l'email e la password corrispondono a quelle nel database
if($email === $db_email && $password === $db_pass){
    // Impostazione delle variabili di sessione per loggare l'utente direttamente
    $_SESSION['loggedIn'] = true;
    $_SESSION['id_user'] = $id_user;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $db_email;
    $_SESSION['propic'] = $propic;
    
    // Si chiude la connessione al database
    $conn_db->close();
    
    // Si invia l'utente alla home
    header("Location: ../home.php");
    exit();
} else {
    // Messaggio di errore se l'email o la password non sono corrette
    echo "email or password not correct";
    
    // Pulizia delle variabili di sessione
    session_unset();
    
    // Si chiude la connessione al database
    $conn_db->close(); 
    
    // Si invia l'utente alla login
    header("Location: ../login.php");
    exit();
}
?>
