<?php
// Inclusione del file per la connessione al database e del file di configurazione
include 'Connessione_database.php';
require_once 'config.php';

// Verifica se sono presenti i dati dal form e si inizializzano le variabili
if (isset($_POST['titolo'], $_POST['testo'], $_POST['id_blog'], $_SESSION['id_user'])) {
    $titolo = $_POST['titolo'];
    $testo = $_POST['testo'];
    $id_b = $_POST['id_blog'];
    $id_u = $_SESSION['id_user'];

    // Vrifica se il link YouTube c'e'
    $youtube_link = isset($_POST['youtube_link']) ? $_POST['youtube_link'] : null;

    // Inizializza la variabile del momento della creazione
    $date = date('Y-m-d H:i:s');

    // Prepara e esegue la query utilizzando un prepared statement
    $stmt = $conn_db->prepare("INSERT INTO post (title_post, text_post, date_time, id_b, id_u, link) VALUES (?, ?, ?, ?, ?, ?)");
    // Se tutto OK allora
    if ($stmt) {
        // prepara i parametri ed esegue
        $stmt->bind_param('sssiss', $titolo, $testo, $date, $id_b, $id_u, $youtube_link);
        $stmt->execute();

        // Verifica se la query è stata eseguita con successo
        if ($stmt->affected_rows > 0) {
            echo "Post inserito con successo!";
        } else {
            echo "Errore durante l'inserimento del post.";
        }
        // Chiudi lo statement
        $stmt->close();
    } else {
        echo "Errore nella preparazione della query.";
    }
} else {
    echo "Tutti i campi del form devono essere compilati.";
}

$id_p = $conn_db ->insert_id;

// Verifica se sono state caricate delle immagini
if (isset($_FILES['immagine'])) {
    // Itera l'array ed esegue il caricamento
    for($i = 0; $i < count($_FILES['immagine']['name']); $i++) {
        // Se l'upload e' OK, allora
        if ($_FILES['immagine']['error'][$i] === UPLOAD_ERR_OK) {
            $immagine_nome = $_FILES['immagine']['name'][$i];
            $immagine_tmp = $_FILES['immagine']['tmp_name'][$i];
            $immagine_destinazione = '../post_images/' . $immagine_nome;
            // Sposta l'immagine nella directory post images
            move_uploaded_file($immagine_tmp, $immagine_destinazione);

            // prepara lo statement per l'insert dell'immagine e lo esegue
            $stmt = $conn_db->prepare("INSERT INTO `image` (image.path, id_pst) VALUES (?, ?)");
            $stmt->bind_param("si", $immagine_nome, $id_p); 
            $stmt->execute();
            $stmt->close();

            // Salva l'id dell'immagine appena inserita
            $id_image = $conn_db ->insert_id;

            // Rinomina il file immagine con un codice alfanumerico randomi di 10 caratteri
            $fileExtension = pathinfo($immagine_nome, PATHINFO_EXTENSION);
            $newImageName = substr(uniqid(), 0, 10) . '.' . $fileExtension;
            rename($immagine_destinazione, '../post_images/' . $newImageName);

            // Aggiorna il nome dell'immagine nel DB
            $query = "UPDATE `image` SET image.path = '".$newImageName."' WHERE id_image = ".$id_image;
            $result = $conn_db->query($query);
        }
    }
}

// Inizializza la var. di seesione per id_post
$_SESSION["id_post"] = $id_p;

// Verifica se l'utente e' premium
if (isset($_SESSION['id_user'])) {
    $id_u = $_SESSION['id_user'];
    $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_u'");

    // Verifica se la query ha avuto successo
    if ($result === false) {
        die('Errore nella query: ' . $conn_db->error);
    }

    // Se non e' premium allora lo rinidirizza verso la pagina con la pubblicita' fastidiosa
    if ($result->num_rows == 0) {

        // Chiusura della conn. al DB  e rindirizza l'utente
        $conn_db->close(); 
        header("Location: ../createAd.php");
        exit();

    }   elseif ($result->num_rows == 1) {

        // Chiusura della conn. al DB  e rindirizza l'utente
        $conn_db->close(); 
        header("Location: ../post.php");
        exit();

    }            
    $result->free();
}
?>
