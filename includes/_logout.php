<?php
    // Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
    include "Connessione_database.php"; 
    require_once 'config.php';
    
    // Si chiudono le variabili di sessione
    session_unset();
    // Si distrugge la sessione
    session_destroy();    

    // Se esiste il cookie della sessione, lo elimina
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-42000, '/');
    }

    // Si chiude la connessione al DB
    $conn_db->close();   
    
    // Si invia l'utente alla home ma non e' piu' loggato
    header("Location: ../home.php");
    exit();
?>
