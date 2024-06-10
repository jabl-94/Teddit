<?php   
// Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
include 'Connessione_database.php';
require_once 'config.php';

    $id_user = $_SESSION["id_user"];
    // Query per capire se l'utente e' gia' premium o meno
    $result = $conn_db->query("SELECT * FROM premium WHERE id_premium = '$id_user'");
    if ($result->num_rows == 0) {
        $data = $_POST;
        if (empty($data['card_number']) || empty($data['card_holder']) || empty($data['expiry_date']) || empty($data['card_type']))
            {
            die('Please fill all required fields!');
        }
        // Se va tutto bene allora inizializza la variabile del nº della carta ed elimina gli spazi
        else {
            $card_number = $data['card_number'];
            $card_number_without_spaces = str_replace(' ', '', $card_number);
            // Maschera tutti tranne gli ultimi 4 numerii della carta
            $masked_card_number = str_repeat('*', strlen($card_number_without_spaces) - 4) . substr($card_number_without_spaces, -4);
            
            // Salva il nome dell'intestatario della carta
            $card_holder = trim($data['card_holder']);
            $expiry_date = $data['expiry_date'] . '-01';
            $card_type = $data['card_type'];

            $date = date('Y-m-d'); // Data corrente
            $one_year_later = date('Y-m-d', strtotime("+1 year", strtotime($date))); // Data corrente più un anno

            // Si fa il prepared statement per prevenire la SQL injectiom
            $stmt = $conn_db->prepare("INSERT INTO premium(id_premium, expiry_sub, expiry_card, card_number, card_holder, card_type) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('isssss', $id_user, $one_year_later, $expiry_date, $masked_card_number, $card_holder, $card_type);
            $stmt->execute();
            $stmt->close();

            // Si chiude la conessione al DB e si invia alla schermata processing
            $conn_db->close();
            header("Location: ../processing.php");
            exit();
        }
    // Invece, se l'utente e' gia' premium, questo viene cancellato
    } elseif ($result->num_rows == 1) {
        $conn_db->query("DELETE FROM premium WHERE id_premium = '$id_user'");
        
        $conn_db->close();
        header("Location: ../profile.php");
        exit();
    }
    ?>