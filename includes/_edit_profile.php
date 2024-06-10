<?php 
// Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
include 'Connessione_database.php';
require_once 'config.php';



    // Verifica se l'immagine e' stata caricata
    if (isset($_FILES['immagine']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {

        // Inizializza una variabile con la variabile di sessione
        $id_user = $_SESSION['id_user'];
        // Prende l'immagine precedente dal DB
        $query = "SELECT propic FROM utente WHERE id_user = $id_user";
        $result = $conn_db->query($query);
        $row = $result->fetch_assoc();
        $oldImageName = $row['propic'];

        // Cancella l'immagine precedente (Se non e' quella di default)
        if (file_exists('../propics/' . $oldImageName) && ($oldImageName != '0.png')) {
            unlink('../propics/' . $oldImageName);
        }

        $result->free();

        $immagine_nome = $_FILES['immagine']['name'];
        $immagine_tmp = $_FILES['immagine']['tmp_name'];
        $immagine_destinazione = '../propics/' . $immagine_nome;
        // Sposta l'immagine dall path temporaneo nella directory giusta
        move_uploaded_file($immagine_tmp, $immagine_destinazione);

        // Prepara la query e lo statement e i rispettivi parametri
        $stmt = $conn_db->prepare("UPDATE utente SET propic = ? WHERE id_user = ?");
        $stmt->bind_param("si", $immagine_nome, $id_user);

        // Verifica se lo statement ha avuto successo
        if ($stmt->execute()) {
            echo "User updated successfully!";
            // Rinomina il file immagine con un codice alfanumerico randomico di 10 caratteri
            $fileExtension = pathinfo($immagine_nome, PATHINFO_EXTENSION);
            $newImageName = substr(uniqid(), 0, 10) . '.' . $fileExtension;
            rename($immagine_destinazione, '../propics/' . $newImageName);

            // Aggiorna il nome dell'immagine appena caricata
            $query = "UPDATE utente SET propic = '$newImageName' WHERE id_user = $id_user";
            $conn_db->query($query);
        } else {
            echo "An error occurred while updating the user: " . $conn_db->error;
        }
        $stmt->close();
    }

    // s'inizializzano le variabili inviate tramite post
    $data = $_POST;
    $id_user = $_SESSION['id_user'];
    
    // Si fa la query per estrarre le info dell'utente attuale
    $result = $conn_db->query("SELECT * FROM utente WHERE id_user = '$id_user'");
    $row = $result->fetch_assoc();
    
    // Se i campi non sono vuoti, li aggiorna. Altrimenti mantiene i vecchi dati.
    $username = !empty($data['username']) ? trim($data['username']) : $row['username'];
    $email = !empty($data['email']) ? trim($data['email']) : $row['email'];
    $bio = !empty($data['bio']) ? trim($data['bio']) : $row['bio'];

    // Controlla se le password coincidono e se si, le  hasha, ma 
    // se non e' stata modificata allora matiene quella vecchia
    if (!empty($data['password']) && !empty($data['confirm_password'])) {
        if ($data['password'] !== $data['confirm_password']) {
            die("Le password non corrispondono");
        } else {
            $password = hash('sha256', trim($data['password']));
        }
    } else {
        $password = $row['pass'];
    }

    $result->free();
    
    // Prepara la query e lo statement per aggiornare i dati nel DB e poi aggiorna le variabili di sessione dell'utente
    $stmt = $conn_db->prepare("UPDATE utente SET username = ?, email = ?, bio = ?, pass = ? WHERE id_user = ?");
    $stmt->bind_param('ssssi', $username, $email, $bio, $password, $id_user);
    $stmt->execute();
    $stmt->close();
    
    $result = $conn_db->query("SELECT * FROM utente WHERE id_user = '$id_user'");
    $row = $result->fetch_assoc();
    $_SESSION['id_user'] = $row["id_user"];
    $_SESSION['username'] = $row["username"];
    $_SESSION['email'] = $row["email"];
    $_SESSION['bio'] = $row["bio"];
    $_SESSION['propic'] = $row["propic"];
    $result->free();
    
    // Si chiude la conn. e si rimanda l'utente alla pagina del suo profilo.
    $conn_db->close();
    header("Location: ../profile.php");
    exit();
    ?>