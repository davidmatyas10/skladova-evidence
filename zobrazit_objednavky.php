<?php
require 'db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$sql = "SELECT o.*, u.Jmeno, u.Prijmeni, p.NázevProduktu, op.Množství, o.CelkováCena FROM objednávky o JOIN users u ON o.UserID = u.ID
        JOIN objednávkyprodukty op ON o.IDObjednávky = op.IDObjednávky JOIN produkty p ON op.IDProduktu = p.IDProduktu GROUP BY o.IDObjednávky";
$result = $conn->query($sql);
$conn->close();
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Zobrazit Objednávky</title>
    <link rel="stylesheet" href="zobrazit_objednavky.css">
</head>
<body>
<h1>Seznam Objednávek</h1>
<div class="container">
    <table>
        <tr>
            <th>ID Objednávky</th>
            <th>Jméno zákazníka</th>
            <th>Datum objednávky</th>
            <th>Produkty</th>
            <th>Množství</th>
            <th>Celková cena</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['IDObjednávky']; ?></td>
                <td><?php echo $row['Jmeno'] ." ". $row['Prijmeni']; ?></td>
                <td><?php echo $row['DatumObjednávky']; ?></td>
                <td><?php echo $row['NázevProduktu']; ?></td>
                <td><?php echo $row['Množství']; ?></td>
                <td><?php echo $row['CelkováCena']; ?> Kč</td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="skladnik_dashboard.php" class="btn-zpet">Zpět na Dashboard</a>
</div>
</body>
</html>