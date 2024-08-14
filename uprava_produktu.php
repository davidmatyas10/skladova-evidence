<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Zkontrolujte, zda je uživatel přihlášený a má oprávnění manažera
if (!isset($_SESSION['uzivatel_id']) || $_SESSION['nazevrole'] !== 'Manager') {
    header('Location: prihlasit.php');
    exit();
}

require 'db.php';

// Zkontrolujte, zda byl formulář odeslán
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Předpokládáme, že formulář obsahuje ID produktu a jeho aktualizované údaje
    $idProduktu = $_POST['IDProduktu'];
    $nazevProduktu = $_POST['nazev'];
    $cena = $_POST['cena'];
    $skladem = $_POST['skladem'];

    // Příprava SQL příkazu
    $sql = "UPDATE produkty SET NázevProduktu=?, Cena=?, Skladem=? WHERE IDProduktu=?";

    // Příprava a provedení dotazu
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("siii", $nazevProduktu, $cena, $skladem, $idProduktu);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Data byla aktualizována
            echo "Produkt byl úspěšně aktualizován.";
        } else {
            // Žádné změny nebyly provedeny
            echo "Nebyly provedeny žádné změny nebo produkt s tímto ID neexistuje.";
        }

        $stmt->close();
    } else {
        // V případě chyby při přípravě dotazu
        echo "Něco se pokazilo: " . $conn->error;
    }
}

$conn->close();

?>