<?php
// Includi il file di configurazione per la connessione al database (assicurati di avere un database configurato)
include "config.php"; 

// Controlla se il modulo è stato inviato via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Controlla se i campi email e password non sono vuoti
    if (empty($email) || empty($password)) {
        echo "Email e password sono obbligatorie.";
        exit();
    }

    // Verifica che l'email abbia un formato valido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email non valida.";
        exit();
    }

    // Verifica se l'utente esiste già nel database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Questo utente è già registrato.";
        exit();
    }

    // Hash della password per sicurezza (usiamo password_hash con costante BCRYPT)
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Inserimento del nuovo utente nel database
    $insertQuery = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmtInsert = $con->prepare($insertQuery);
    $stmtInsert->bind_param("ss", $email, $hashedPassword);

    if ($stmtInsert->execute()) {
        echo "Registrazione avvenuta con successo.";
    } else {
        echo "Errore durante la registrazione. Riprova più tardi.";
    }

    // Chiudi le connessioni al database
    $stmt->close();
    $stmtInsert->close();
} else {
    echo "Accesso non autorizzato.";
}
?>
