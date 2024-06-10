<?php
// Inclusione del file per la connessione al database e del file di configurazione
include 'Connessione_database.php';
require_once 'config.php';

// verifica che ci siano i valori inviati dal frontend ed inizializza le variabili
if (isset($_POST['testo_commento'],$_POST['id_post'],$_SESSION['id_user'],)) {
    $testo = $_POST['testo_commento'];
    $id_p = $_POST['id_post'];
    $id_u = $_SESSION['id_user'];
}
// inizializza le variabili per la data
$data = date('Y-m-d H:i:s');
// prepara loo statement da eseguire
$stmt = $conn_db->prepare("INSERT INTO commento (testo, dt_tm, id_us, id_p) VALUES (?, ?, ?, ?)");

// Se ok, allora prepara i parametri, lo esegue e lo chiude
if ($stmt) {
    $stmt->bind_param('ssii', $testo, $data, $id_u, $id_p);
    $stmt->execute();
    $stmt->close();
}
// Salva la variabile di sessione per post, chiude DB e riindirizza a post
$_SESSION["id_post"] = $id_p;
$conn_db->close();
header("Location: ../post.php");
exit();
?>