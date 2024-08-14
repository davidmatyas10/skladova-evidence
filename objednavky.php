<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: prihlasit.php');
    exit();
}

require 'db.php';

$user_id = $_SESSION['uzivatel_id'];

$sql = "SELECT o.IDObjednávky, o.DatumObjednávky, o.CelkováCena, p.NázevProduktu, p.Cena, op.Množství 
        FROM objednávky o
        INNER JOIN objednávkyprodukty op ON o.IDObjednávky = op.IDObjednávky
        INNER JOIN produkty p ON op.IDProduktu = p.IDProduktu
        WHERE o.userID = ?
        ORDER BY o.DatumObjednávky DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$objednavky_detaily = [];
while ($row = $result->fetch_assoc()) {
    $objednavky_detaily[$row['IDObjednávky']]['produkty'][] = $row;
    if (!isset($objednavky_detaily[$row['IDObjednávky']]['CelkováCena'])) {
        $objednavky_detaily[$row['IDObjednávky']]['CelkováCena'] = 0;
    }
    $objednavky_detaily[$row['IDObjednávky']]['CelkováCena'] += $row['Cena'] * $row['Množství'];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Objednávky</title>
    <link rel="stylesheet" href="objednavka.css">
</head>
<body>

<?php include 'head.php'; ?>

<h1>Moje objednávky</h1>
<div class="objednavky-container">
    <?php if (!empty($objednavky_detaily)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Objednávky</th>
                    <th>Datum Objednávky</th>
                    <th>Produkty</th>
                    <th>Cena za kus</th>
                    <th>Množství</th>
                    <th>Celková cena</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($objednavky_detaily as $idObjednavky => $detaily): ?>
                <tr>
                    <td><?php echo htmlspecialchars($idObjednavky); ?></td>
                    <td><?php echo htmlspecialchars($detaily['produkty'][0]['DatumObjednávky']); ?></td>
                    <td>
                        <?php foreach ($detaily['produkty'] as $produkt): ?>
                            <p><?php echo htmlspecialchars($produkt['NázevProduktu']); ?></p>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($detaily['produkty'] as $produkt): ?>
                            <p><?php echo htmlspecialchars($produkt['Cena']); ?> Kč</p>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <?php foreach ($detaily['produkty'] as $produkt): ?>
                            <p><?php echo htmlspecialchars($produkt['Množství']); ?></p>
                        <?php endforeach; ?>
                    </td>
                    <td><?php echo $detaily['CelkováCena']; ?> Kč</td>
                    <td><a href="zrusit_objednavku.php?id=<?php echo $idObjednavky; ?>">Zrušit</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Žádné objednávky nebyly nalezeny.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
