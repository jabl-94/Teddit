<?php
// Inclusione del file per la connessione al database e del file di configurazione hce include la session_start
include 'Connessione_database.php';
require_once 'config.php';

$categoria = $_GET['categoria'];

// Esegue una query per contare il numero di sottocategorie
$query = "SELECT COUNT(*) as count FROM category WHERE id_parent = (SELECT id_cat FROM category WHERE category.name = '$categoria')";
$result = $conn_db->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $count = $row['count'];
    if ($count > 0) {
        // Se ci sono sottocategorie, restituisce le opzioni
        $query = "SELECT category.name FROM category WHERE id_parent = (SELECT id_cat FROM category WHERE category.name = '$categoria')";
        $result = $conn_db->query($query);

        // Le restituisce tramite json encode per la chiamata AJAX nel frontend
        if ($result) {
            $subcategories = array();
            while ($row = $result->fetch_assoc()) {
                $subcategories[] = $row['name'];
            }
            echo json_encode(array("hasSubcategories" => true, "subcategories" => $subcategories));
        } else {
            echo json_encode(array("error" => "Errore nel recupero delle sottocategorie"));
        }
    } else {
        // Se non ci sono sottocategorie, restituisce un flag per indicarlo
        echo json_encode(array("hasSubcategories" => false));
    }
    $result->free();
} else {
    echo json_encode(array("error" => "Errore nel conteggio delle sottocategorie"));
}
$conn_db->close();
?>