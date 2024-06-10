<?php
// Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
include 'Connessione_database.php';
require_once 'config.php';

// Verifica se tutti i dati sono stati inviati ed inizializza le variabili neccessarie
if (isset($_POST['title'], $_POST['description'], $_POST['id_blog'])) {
    $titolo = $_POST['title'];
    $descrizione = $_POST['description'];
    $id_blog = $_POST['id_blog'];
    $_SESSION['id_blog'] = $id_blog;

    // Verifica se un'immagine e' stata caricata
    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {
        // Estrae il nome dell'immagine precedente
        $query = "SELECT img FROM blog WHERE id_blog = " . $id_blog;
        $result = $conn_db->query($query);
        $row = $result->fetch_assoc();
        $oldImageName = $row['img'];

        // Cancella l'immagine vecchia dal server
        if (file_exists('../images/' . $oldImageName)) {
            // MA prima verifica che l'immagine non si chiami "1.png" prima di cancellarla
            if ($oldImageName != "1.png") {
                unlink('../images/' . $oldImageName);
            }
        }

        $result->free();

        $immagine_nome = $_FILES['immagine']['name'];
        $immagine_tmp = $_FILES['immagine']['tmp_name'];
        $immagine_destinazione = '../images/' . $immagine_nome;
        // Sposta la nuova immagine nella directory giusta
        move_uploaded_file($immagine_tmp, $immagine_destinazione);

        // Prepara lo statement e i suoi parametri
        $stmt = $conn_db->prepare("UPDATE blog SET title = ?, description = ?, img = ? WHERE id_blog = ?");
        $stmt->bind_param("sssi", $titolo, $descrizione, $immagine_nome, $id_blog);

        if ($stmt->execute()) {
            echo "Blog updated successfully!";
            // Rinominare il file immagine con un codice alfanumerico randomico di 10 caratteri
            $fileExtension = pathinfo($immagine_nome, PATHINFO_EXTENSION);
            $newImageName = substr(uniqid(), 0, 10) . '.' . $fileExtension;
            rename($immagine_destinazione, '../images/' . $newImageName);

            // Aggiorna il nome dell'immagine nel DB
            $query = "UPDATE blog SET img = '" . $newImageName . "' WHERE id_blog = " . $id_blog;
            $result = $conn_db->query($query);
            // Chiude conn. e invia l'utente a la pagina del blgo aggiornato
            $conn_db->close();
            header("Location: ../blog.php");
            exit();
        } else {
            echo "An error occurred while updating the blog: " . $conn_db->error;
        }

        $stmt->close();
    // Altrimenti aggiorna il blog direttamente se non e' stata caricata nessuna nuova immagine
    } else {
        // Prepara lo statement e i suoi parametri 
        $stmt = $conn_db->prepare("UPDATE blog SET title = ?, blog.description = ? WHERE id_blog = ?");
        $stmt->bind_param("ssi", $titolo, $descrizione, $id_blog);

        if ($stmt->execute()) {
            echo "Blog updated successfully!";
            // Chiude conn. e invia l'utente a la pagina del blgo aggiornato
            $conn_db->close();
            header("Location: ../blog.php");
            exit();
        } else {
            echo "An error occurred while updating the blog: " . $conn_db->error;
        }

        $stmt->close();
    }
} else {
    echo "An error occurred: all required fields were not submitted.";
}

$conn_db->close();
?>