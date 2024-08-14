<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

// Kontrola, zda byl formulář odeslán
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['prihlasit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $heslo = mysqli_real_escape_string($conn, $_POST['heslo']);

    // SQL dotaz na získání informací o uživateli
    $sql = "SELECT u.ID, u.Jmeno, u.Prijmeni, u.Email, u.Telefonnicislo, u.Mesto, u.Adresa, u.PSC, u.IDRole, r.NázevRole 
    FROM users u LEFT JOIN role r ON u.IDRole = r.IDRole 
    WHERE u.Email='$email' AND u.heslo='$heslo'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Nastavíme session proměnné
        $_SESSION['uzivatel_id'] = $row['ID'];
        $_SESSION['jmeno'] = $row['Jmeno'];
        $_SESSION['prijmeni'] = $row['Prijmeni'];
        $_SESSION['email'] = $row['Email'];
        $_SESSION['heslo'] = $row['Heslo'];
        $_SESSION['telefonnicislo'] = $row['Telefonnicislo'];
        $_SESSION['mesto'] = $row['Mesto'];
        $_SESSION['adresa'] = $row['Adresa'];
        $_SESSION['psc'] = $row['PSC'];
        $_SESSION['idrole'] = $row['IDRole'];
        $_SESSION['nazevrole'] = $row['NázevRole'];
        
        // Přesměrování na eshop.php
        header("Location: eshop.php");
        exit;
    } else {
        echo "Nesprávný email nebo heslo.";
    }
}

include "prihlasit.html";

$conn->close();
?>