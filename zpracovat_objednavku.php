<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

if (empty($_SESSION['kosik'])) {
    echo "Váš košík je prázdný.";
    exit();
}

$produkty_v_kosiku = [];
$celkova_cena = 0;
foreach ($_SESSION['kosik'] as $produkt_id => $mnozstvi) {
    $stmt = $conn->prepare("SELECT NázevProduktu, Cena FROM produkty WHERE IDProduktu = ?");
    $stmt->bind_param("i", $produkt_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($produkt = $result->fetch_assoc()) {
        $produkty_v_kosiku[] = [
            'nazev' => $produkt['NázevProduktu'], 
            'cena' => $produkt['Cena'], 
            'mnozstvi' => $mnozstvi
        ];
        $celkova_cena += $produkt['Cena'] * $mnozstvi;
    }
    $stmt->close();
}

if (isset($_SESSION['uzivatel_id']) && $_SESSION['uzivatel_id'] > 0) {
    $uzivatel_id = $_SESSION['uzivatel_id'];

    $stmt = $conn->prepare("SELECT Jmeno, Prijmeni, Email, TelefonniCislo, Mesto, Adresa, PSC FROM users WHERE ID = ?");
    $stmt->bind_param("i", $uzivatel_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($uzivatel = $result->fetch_assoc()) {
        $fakturacni_udaje = [
            'jmeno' => $uzivatel['Jmeno'],
            'prijmeni' => $uzivatel['Prijmeni'],
            'email' => $uzivatel['Email'],
            'telefon' => $uzivatel['TelefonniCislo'],
            'mesto' => $uzivatel['Mesto'],
            'adresa' => $uzivatel['Adresa'],
            'psc' => $uzivatel['PSC']
        ];
    } else {
        header('Location: prihlasit.php');
        exit();
    }
    $stmt->close();
} else {
    header('Location: prihlasit.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Objednávka</title>
    <link rel="stylesheet" href="objednavka.css">
</head>
<body>
<div class="objednavka-container">
    <h1>Objednávka</h1>
    <div class="objednavka-podrobnosti">
        <h2>Produkty v objednávce:</h2>
        <table>
            <thead>
                <tr>
                    <th>Produkt</th>
                    <th>Cena</th>
                    <th>Množství</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produkty_v_kosiku as $produkt): ?>
                <tr>
                    <td><?php echo htmlspecialchars($produkt['nazev']); ?></td>
                    <td><?php echo htmlspecialchars($produkt['cena']); ?> Kč</td>
                    <td><?php echo htmlspecialchars($produkt['mnozstvi']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Celková cena:</td>
                    <td><?php echo $celkova_cena; ?> Kč</td>
                </tr>
            </tfoot>
        </table>
        <h2>Fakturační údaje:</h2>
        <p>Jméno: <?php echo htmlspecialchars($fakturacni_udaje['jmeno']); ?></p>
        <p>Příjmení: <?php echo htmlspecialchars($fakturacni_udaje['prijmeni']); ?></p>
        <p>Email: <?php echo htmlspecialchars($fakturacni_udaje['email']); ?></p>
        <p>Telefonní číslo: <?php echo htmlspecialchars($fakturacni_udaje['telefon']); ?></p>

        <h2>Dodací údaje:</h2>
        <p>Město: <?php echo htmlspecialchars($fakturacni_udaje['mesto']); ?></p>
        <p>Adresa: <?php echo htmlspecialchars($fakturacni_udaje['adresa']); ?></p>
        <p>PSČ: <?php echo htmlspecialchars($fakturacni_udaje['psc']); ?></p>
    </div>
    <div class="objednavka-akce">
        <button><a href="potvrzeni_objednavky.php" class="btn-primary">Potvrdit objednávku</a></button>
    </div>
</div>
</body>
</html>