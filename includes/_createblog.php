<?php
// Inclusione del file per la connessione al database e del file di configurazione
include 'Connessione_database.php';
require_once 'config.php';

// Verifica che le categorie siano state inviati dal frontend
//Se si e' scelta solo una categoria madre senza sottocategoria allora fa la query ed estrae l'id della categoria da salvare in blog
if (isset($_POST['categoria']) && !isset($_POST['sottocategoria']) || (isset($_POST['categoria']) && $_POST['sottocategoria'] == "Nessuna")){
    $categoria = $_POST['categoria'];
    $result = $conn_db->query("SELECT id_cat FROM category WHERE category.name = '$categoria'");
    $row = $result->fetch_assoc();
    $id_c = $row["id_cat"];
    $result->free();
   // altrimenti, se e' stata scelta una sottocategoria, allora questo id viene estrato per salvarlo in blog
} elseif (isset($_POST['categoria'], $_POST['sottocategoria']) && $_POST['sottocategoria'] != "Nessuna") {
    $categoria = $_POST['sottocategoria'];
    $result = $conn_db->query("SELECT id_cat FROM category WHERE category.name = '$categoria'");
    $row = $result->fetch_assoc();
    $id_c = $row["id_cat"];
    $result->free();
}

// Verifica ceh il titolo e la descrizione ci siano
if (isset($_POST['titolo'], $_POST['descrizione'])) {
    // Get the data from the form
    $titolo = $_POST['titolo'];
    $descrizione = $_POST['descrizione'];
    $id_autore = $_SESSION['id_user'];

    // Verifica che l'immagine sia stata caricata
    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {
        $immagine_nome = $_FILES['immagine']['name'];
        $immagine_tmp = $_FILES['immagine']['tmp_name'];
        $immagine_destinazione = '../images/' . $immagine_nome;
        // La sposta nella directory /images/
        move_uploaded_file($immagine_tmp, $immagine_destinazione);

        // Prepara lo statement e i soui parametri
        $stmt = $conn_db->prepare("INSERT INTO blog (title, blog.description, img, id_author, id_category) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $titolo, $descrizione, $immagine_nome, $id_autore, $id_c);

        // Se ok, allora si fa l'inserte e si salva l'id del blog nella variabile di sessione
        if ($stmt->execute()) {
            echo "Blog creato con successo!";
            $id_b = $conn_db ->insert_id;
            $_SESSION["id_blog"] = $id_b;

            // Si rinomina l'immagine  con il codice alfanumerico randomici di 10 caratteri
            $fileExtension = pathinfo($immagine_nome, PATHINFO_EXTENSION);
            $newImageName = substr(uniqid(), 0, 10) . '.' . $fileExtension;
            rename($immagine_destinazione, '../images/' . $newImageName);

            // Si aggiorna il nome dell'immagine nel DB
            $query = "UPDATE blog SET img = '".$newImageName."' WHERE id_blog = ".$id_b;
            $result = $conn_db->query($query);

            // Si chiude conn. al DB e si riindirizza alla pagina del blog
            $conn_db->close();
            header("Location: ../blog.php");
            exit();
        } else {
            echo "Si è verificato un errore durante la creazione del blog: " . $conn_db->error;
        }

        $stmt->close();
    } else {
        // Altrimenti, se non c'e' stata nessun'immagine, viene preparato lo statement e i parametri per l'insert
        $stmt = $conn_db->prepare("INSERT INTO blog (title, blog.description, id_author, id_category) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $titolo, $descrizione, $id_autore, $id_c);

        // Se ok, allora si fa l'inserte e si salva l'id del blog nella variabile di sessione
        if ($stmt->execute()) {
            echo "Blog creato con successo!";
            $id_b = $conn_db ->insert_id;
            $_SESSION["id_blog"] = $id_b;

            // Si chiude conn. al DB e si rindirizza a blog
            $conn_db->close();
            header("Location: ../blog.php");
            exit();
        } else {
            echo "Si è verificato un errore durante la creazione del blog: " . $conn_db->error;
        }

        $stmt->close();
    }
} else {
    echo "Si è verificato un errore: tutti i campi obbligatori non sono stati inviati.";
}

$conn_db->close();
?>
