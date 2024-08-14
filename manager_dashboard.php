<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

$sql = "SELECT IDProduktu, NázevProduktu, Cena, Skladem, Popis FROM produkty";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Manažerský Dashboard</title>
    <link rel="stylesheet" href="manager_dashboard.css">
</head>
<body>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="user-info">
            <?php if (isset($_SESSION['uzivatel_id'])) : ?>
                <p>Přihlášený uživatel:<br> 
                <?php echo $_SESSION['jmeno'] . " " . $_SESSION['prijmeni']; ?><br>
                    Role: <?php echo $_SESSION['nazevrole']; ?>
                </p>
            <?php endif; ?>
        </div>
        <a href="seznam_produktu.php" class="btnSeznamProduktu">Seznam Produktů</a>
        <a href="vytvorit_produkt.php" class="btnVytvoritProdukt">Vytvořit Produkt</a>
        <a href="odstranit_produkt.php" class="btnOdstranitProdukt">Odstranit Produkt</a>
        <a href="eshop.php" class="btnEshop">Přejít do e-shopu</a>
        <a href="odhlasit.php" class="btnOdhlasit">Odhlásit se</a>
    </aside>

    <section class="content">
        <h1>Manažerský Dashboard</h1>
        <table>
            <thead>
            <tr>
                <th>ID Produktu</th>
                <th>Název Produktu</th>
                <th>Cena</th>
                <th>Skladem</th>
                <th>Popis</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['IDProduktu']); ?></td>
                <td><?php echo htmlspecialchars($row['NázevProduktu']); ?></td>
                <td><?php echo htmlspecialchars($row['Cena']); ?> Kč</td>
                <td><?php echo htmlspecialchars($row['Skladem']); ?></td>
                <td><?php echo htmlspecialchars($row['Popis']); ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</div>
</body>
</html>