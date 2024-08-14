<?php
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['kosik'])) {
    header('Location: kosik.php');
    exit();
}

$celkova_cena = 0;
$produkty_v_kosiku = [];

if (!empty($_SESSION['kosik'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['kosik']), '?'));
    $sql = "SELECT IDProduktu, NázevProduktu, Cena, nazevSouboruObrázku FROM produkty WHERE IDProduktu IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $params = array_keys($_SESSION['kosik']);
    $types = str_repeat('i', count($params));
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($produkt = $result->fetch_assoc()) {
        $produkty_v_kosiku[$produkt['IDProduktu']] = $produkt;
        $produkty_v_kosiku[$produkt['IDProduktu']]['Množství'] = $_SESSION['kosik'][$produkt['IDProduktu']];
        $celkova_cena += ($produkt['Cena'] * $_SESSION['kosik'][$produkt['IDProduktu']]);
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Potvrzení objednávky</title>
    <link rel="stylesheet" href="objednavka.css">
</head>
<body>
<div class="objednavka-kontejner">
    <h1>Potvrzení objednávky</h1>
    <table>
    <thead>
        <tr>
            <th>Produkt</th>
            <th>Cena za kus</th>
            <th>Množství</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produkty_v_kosiku as $produkt): ?>
        <tr>
            <td><?php echo htmlspecialchars($produkt['NázevProduktu']); ?></td>
            <td><?php echo htmlspecialchars($produkt['Cena']); ?> Kč</td>
            <td><?php echo htmlspecialchars($produkt['Množství']); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td>Celková cena:</td>
            <td><?php echo $celkova_cena; ?> Kč</td>
        </tr>
    </tbody>
</table>
    <form action="zpracovat_objednavku.php" method="post">
        <button type="submit" name="potvrdit_objednavku">Zpracovat objednávku</button>
    </form>
</div>
</body>
</html>