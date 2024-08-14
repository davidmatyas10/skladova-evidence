<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: prihlasit.php');
    exit();
}

require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $stare_heslo = $_POST['stare_heslo'];
    $nove_heslo = $_POST['nove_heslo'];
    $potvrdit_nove_heslo = $_POST['potvrdit_nove_heslo'];

    $sql = "SELECT Heslo FROM users WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['uzivatel_id']);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($heslo_v_databazi);
        $stmt->fetch();

        if ($heslo_v_databazi === $stare_heslo) {
            $sql_update = "UPDATE users SET Heslo = ? WHERE ID = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("si", $nove_heslo, $_SESSION['uzivatel_id']);
            
            if ($stmt_update->execute()) {
                header('Refresh:3;url=profil.php');
                echo "Heslo bylo úspěšně změněno. Budete přesměrováni na profil.";
                exit();
            } else {
                echo "Nastala chyba při změně hesla: " . $stmt_update->error;
            }
        } else {
            echo "Zadané stávající heslo není platné.";
        }
    }
    $stmt->close();
}
$conn->close();
?>