<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: prihlasit.php');
    exit();
}

if (!isset($_GET['id'])) {
    echo "Nebylo zadáno ID objednávky.";
    exit();
}

$objednavka_id = $_GET['id'];
$uzivatel_id = $_SESSION['uzivatel_id'];
$sql = "DELETE FROM objednávkyprodukty WHERE IDObjednávky = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $objednavka_id);
$stmt->execute();
$stmt->close();
$sql = "DELETE FROM objednávky WHERE IDObjednávky = ? AND userID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $objednavka_id, $uzivatel_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Objednávka číslo $objednavka_id byla úspěšně zrušena.";
} else {
    echo "Objednávku se nepodařilo zrušit nebo objednávka neexistuje.";
}

$stmt->close();
$conn->close();
header("Location: potvrzeni_zruseni.php?id=$objednavka_id");
exit();
?>