<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "Email e password sono obbligatorie.";
        exit();
    }

    $existingUsers = file("utenti.txt", FILE_IGNORE_NEW_LINES);
    if ($existingUsers !== false && in_array("$email,$password", $existingUsers)) {
        echo "Questo utente è già registrato.";
        exit();
    }

  
    $file = fopen("utenti.txt", "a");

    if ($file) {
        fwrite($file, "$email,$password\n");

        // Chiudi il file
        fclose($file);

        echo "Registrazione avvenuta con successo.";
    } else {
        echo "Errore durante la registrazione.";
    }
} else {
    echo "Accesso non autorizzato.";
}
?>
