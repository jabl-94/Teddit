<?php 
include 'Connessione_database.php';
require_once 'config.php';

// Verifica che la variabile non sia vuota
if (!empty($_POST["keyword"])) {
    $search = "%" . $_POST['keyword'] . "%";
    
    // Prepara il prepared statement
    $stmt = $conn_db->prepare("
        (SELECT username AS result FROM utente WHERE username LIKE ?)
        UNION
        (SELECT name AS result FROM category WHERE name LIKE ?)
        UNION
        (SELECT title_post AS result FROM post WHERE title_post LIKE ?)
        UNION
        (SELECT title AS result FROM blog WHERE title LIKE ?)
        ORDER BY result LIMIT 0,10
    ");
    
    // parametri per la query
    $stmt->bind_param("ssss", $search, $search, $search, $search);
    
    // Esegue l'statement
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    // Se il risultato non e' vuoto, allora viene generata la lista delle opzioni
    if ($result->num_rows > 0) {?>
        <ul id="autocomplete-list"><?php
        foreach ($result as $row) {?>
            <li onClick="selectResult('<?php echo mysqli_real_escape_string($conn_db, $row["result"]); ?>');">
            <?php echo $row["result"]; ?>
        </li><?php
    }?>
        </ul><?php
    } else {
        echo "Nessun risultato trovato.";
    }
    $stmt->close();
}

$conn_db->close();
?>
