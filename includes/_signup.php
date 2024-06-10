<?php 
// Conessione al DB
include 'Connessione_database.php';
// File config per la session_start
require_once 'config.php';
   
    // Verifichaimo che le variabili non siano vuote
    $data = $_POST;
    if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['confirm_password']) || empty($data['security_question']) || empty($data['security_answer']))
        {
        die('Please fill all required fields!');
        }
    else {
    
    // Si fa il trim per eliminare gli spazzi e si inizializzano le variabili
    $username = trim($data['username']);
    $email = trim($data['email']);
    $password = trim($data['password']);
    $confirm_password = trim($data['confirm_password']);
    $security_question = ($data['security_question']);
    $security_answer = trim($data['security_answer']);

    // Si verifica che le password siano uguali e poi si esegue l'insert tramite prep. statement
    if ($password !== $confirm_password)
        {
        die("Le password non corrispondono");
        }
    else {
        $result = $conn_db->query("SELECT id_question FROM security_question WHERE question = '$security_question'");
        $row = $result->fetch_assoc();
        $id_q = $row["id_question"];
        $result->free();

        $password = hash('sha256', $password);

        $stmt = $conn_db->prepare("INSERT INTO utente(username, email, pass, id_quest, sec_answer) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssis', $username, $email, $password, $id_q, $security_answer);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn_db->prepare("SELECT * FROM utente WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        // S'inizializzano le variabili di sessione per loggare l'utente subito
        $_SESSION['loggedIn'] = true;
        $_SESSION['id_user'] = $row["id_user"];
        $_SESSION['username'] = $row["username"];
        $_SESSION['email'] = $row["email"];
        $_SESSION['propic'] = $row["propic"];

        // Free result, connessione chiusa, e s'invia l'utente alla home
        $result->free();
        $stmt->close();
        $conn_db->close();
        header("Location: ../home.php");
        exit();
        }
    }
    ?>
