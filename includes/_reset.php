<?php 
  // Inclusione del file per la connessione al database e del file di configurazione che include la sessione
  include 'Connessione_database.php';
  require_once 'config.php';
?>

<?php
// Si ricevono le variabili tramite post e si verifica se ci sono e se si, si fa la trim per eliminare gli spazzi
$data = $_POST;
$password = isset($data['new_password']) ? trim($data['new_password']) : '';
$confirm_password = isset($data['new_password_confirm']) ? trim($data['new_password_confirm']) : '';

// Verifico se la password o la conferma della password sono vuote
if (empty($password) || empty($confirm_password)) {
    die('Please fill all required fields!');
} else {
    // Verifico se la password e la conferma della password corrispondono
    if ($password !== $confirm_password) {
        die("Le password non corrispondono");
    } else {
        // Si fa il hash della password
        $password = hash('sha256', $password);

        // Si prepara la query SQL
        $stmt = $conn_db->prepare("UPDATE utente SET pass = ? WHERE username = ?");
        // Si preparano i parametri
        $stmt->bind_param('ss', $password, $_SESSION['user']);
        // Si esegue lo statement
        $stmt->execute();
        $stmt->close();

        // Chiusura della connessione
        $conn_db->close();

        // Reindirizzamento alla pagina di login
        header("Location: ../login.php");
        exit();
    }
}
?>
