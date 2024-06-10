<?php
// Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
include 'Connessione_database.php';
require_once 'config.php';

// Verifica se le info neccessarie sono state inviate 
if (isset($_POST['title_post'], $_POST['text_post'], $_POST['id_post'])) {
    // Inizializza le variabili ricevute dalla form
    $titolo = $_POST['title_post'];
    $text = $_POST['text_post'];
    $id_post = $_POST['id_post'];
    $_SESSION['id_post'] = $id_post;

    $youtube_link = isset($_POST['youtube_link']) ? $_POST['youtube_link'] : null;

    // Verifica se sono state caricate delle immagini
    if ($_FILES['immagine']['error'][0] == UPLOAD_ERR_OK) {
        // Verifica se l'utente non e' premium
        $id_u = $_SESSION['id_user'];
        
        $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");
        // Verifica se la query ha avuto successo
        if ($result === false) {
            die('Errore nella query: ' . $conn_db->error);
        }
    
        if ($result->num_rows == 0) {    
            // L'utente non e' premium
            // Prelieva le immagini attuali dei post
            $result_img = $conn_db->query("SELECT * FROM `image` WHERE id_pst = '$id_post'");
            if ($result_img === false) {
                die('Errore nella query: ' . $conn_db->error);
            }
            // Se ci sono immagini nel post le cancella tramite unlink() e poi dal DB
            if ($result_img->num_rows != 0) {
                while ($row_img = $result_img->fetch_assoc()) {
                    $old_img_path = '../post_images/' . $row_img['path'];
                    
                    if (file_exists($old_img_path)) {
                        unlink($old_img_path);
                    }
                    
                    $query = "DELETE FROM image WHERE id_image = " . $row_img['id_image'];
                    $result = $conn_db->query($query);
                    if ($result === false) {
                        die('Errore nella query di cancellazione: ' . $conn_db->error);
                    }
                }
            }
        }        
    
        // Itera per accedere ad ogni immagine
        for($i = 0; $i < count($_FILES['immagine']['name']); $i++) {
            // Check if the upload is successful
            if ($_FILES['immagine']['error'][$i] === UPLOAD_ERR_OK) {
                $immagine_nome = $_FILES['immagine']['name'][$i];
                $immagine_tmp = $_FILES['immagine']['tmp_name'][$i];
                $immagine_destinazione = '../post_images/' . $immagine_nome;
                // Sposta il file caricato nella directory giusta
                move_uploaded_file($immagine_tmp, $immagine_destinazione);
            
                // prepara lo statement, i suoi parametri e lo esegue
                $stmt = $conn_db->prepare("INSERT INTO `image` (image.path, id_pst) VALUES (?, ?)");
                $stmt->bind_param("si", $immagine_nome, $id_post); 
                $stmt->execute();
            
                // salaviamo l'id dell'immagine prima di fare l'insert in image.
                $id_image = $conn_db ->insert_id;
            
                // Rinomina il file con un codice alfnumerico randomico di 10 caratteri
                $fileExtension = pathinfo($immagine_nome, PATHINFO_EXTENSION);
                $newImageName = substr(uniqid(), 0, 10) . '.' . $fileExtension;
                rename($immagine_destinazione, '../post_images/' . $newImageName);
            
                // Aggiorna l'immagine nel DB
                $query = "UPDATE `image` SET image.path = '$newImageName' WHERE id_image = $id_image";
                $result = $conn_db->query($query);
            }
        }
            // Prepara lo statement e i suoi parametri e lo esegue per aggiornare il POST dopo aver modificato le immagini
            $stmt = $conn_db->prepare("UPDATE post SET title_post = ?, text_post = ?, link = ? WHERE id_post = ?");
            $stmt->bind_param("sssi", $titolo, $text, $youtube_link, $id_post);
    
            if ($stmt->execute()) {
                echo "Post updated successfully!";
                $conn_db->close();
                header("Location: ../post.php");
                exit();
            } else {
                echo "An error occurred while updating the post: " . $conn_db->error;
            }
        
            $stmt->close();
            echo "Post modificato con successo";
            echo "$immagine_nome";
    } else {
            // Prepara lo statement e i suoi parametri e lo esegue per aggiornare il POST se direttamente se non sono state caricate immagini
            $stmt = $conn_db->prepare("UPDATE post SET title_post = ?, text_post = ?, link = ? WHERE id_post = ?");
            $stmt->bind_param("sssi", $titolo, $text, $youtube_link, $id_post);
    
            if ($stmt->execute()) {
                echo "Post updated successfully!";
                // Si chiude l conn. al DB e si invia l'utente a la pagina del post aggiornato
                $conn_db->close();
                header("Location: ../post.php");
                exit();
            } else {
                echo "An error occurred while updating the post: " . $conn_db->error;
            }
        
            $stmt->close();
            echo "Post modificato senza immagine";
        }
} else {
    echo "An error occurred: all required fields were not submitted.";
}

$conn_db->close();
?>