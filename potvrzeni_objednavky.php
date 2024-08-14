<?php
session_start();
require 'db.php';

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: prihlasit.php');
    exit();
}
$uzivatel_id = $_SESSION['uzivatel_id'];
$produkty_v_kosiku = $_SESSION['kosik'];
$celkova_cena = 0;
$conn->begin_transaction();

try {
    $stmt = $conn->prepare("INSERT INTO objednávky (userID, DatumObjednávky) VALUES (?, NOW())");
    $stmt->bind_param("i", $uzivatel_id);
    $stmt->execute();
    $stmt->close();

    $objednavka_id = $conn->insert_id;
    $celkova_cena = 0;

    foreach ($produkty_v_kosiku as $produkt_id => $mnozstvi) {
        $stmt = $conn->prepare("SELECT Cena FROM produkty WHERE IDProduktu = ?");
        $stmt->bind_param("i", $produkt_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $produkt = $result->fetch_assoc();
        $stmt->close();
        $celkova_cena += $produkt['Cena'] * $mnozstvi;
        $stmt = $conn->prepare("INSERT INTO objednávkyprodukty (IDObjednávky, IDProduktu, Množství) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $objednavka_id, $produkt_id, $mnozstvi);
        $stmt->execute();
        $stmt->close();
    }
    $stmt = $conn->prepare("UPDATE objednávky SET CelkováCena = ? WHERE IDObjednávky = ?");
    $stmt->bind_param("di", $celkova_cena, $objednavka_id);
    $stmt->execute();
    $stmt->close();
    $conn->commit();

    unset($_SESSION['kosik']);
    echo "Objednávka číslo $objednavka_id byla úspěšně vytvořena.";
    header("Refresh:5; url=eshop.php");
} catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    throw $exception;
} finally {
    $conn->close();
}
?>





