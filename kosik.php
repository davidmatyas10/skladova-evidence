<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

if (isset($_POST['odebrat']) && isset($_POST['id_produktu'])) {
    $idProduktu = $_POST['id_produktu'];

    if (isset($_SESSION['kosik'][$idProduktu])) {
        unset($_SESSION['kosik'][$idProduktu]);

        $celkova_cena = 0;
        foreach ($_SESSION['kosik'] as $id => $mnozstvi) {
            $sql = "SELECT Cena FROM produkty WHERE IDProduktu = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($produkt = $result->fetch_assoc()) {
                    $celkova_cena += $produkt['Cena'] * $mnozstvi;
                }
                $stmt->close();
            }
        }

        $_SESSION['celkova_cena'] = $celkova_cena;

        header('Location: kosik.php');
        exit();
    }
}

if (isset($_POST['aktualizovat_kosik']) && isset($_POST['Množství'])) {
    foreach ($_POST['Množství'] as $idProduktu => $mnozstvi) {
        if (is_numeric($mnozstvi) && $mnozstvi > 0) {
            $_SESSION['kosik'][$idProduktu] = $mnozstvi;
        } else {
            unset($_SESSION['kosik'][$idProduktu]);
        }
    }
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
$conn->close();
?>

<?php include 'head.php';?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Váš košík</title>
    <link rel="stylesheet" href="kosik.css">
    <link rel="stylesheet" href="eshop.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div class="kosik-container">
    <h1>Váš košík</h1>
    <?php if (!empty($produkty_v_kosiku)): ?>
        <form action="kosik.php" method="post">
            <table>
                <thead>
                    <tr>
                        <th>Obrázek</th>
                        <th>Produkt</th>
                        <th>Cena</th>
                        <th>Množství</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($produkty_v_kosiku as $idProduktu => $produkt): ?>
                <tr>    
                    <td><img src="images/<?php echo htmlspecialchars($produkt['nazevSouboruObrázku']); ?>" 
                    alt="<?php echo htmlspecialchars($produkt['NázevProduktu'])?>" class="produkt-obrazek"></td>
                    <td><?php echo htmlspecialchars($produkt['NázevProduktu']); ?></td>
                    <td><?php echo htmlspecialchars($produkt['Cena']); ?> Kč</td>
                    <td>
                        <form action="kosik.php" method="post">
                            <input type="hidden" name="id_produktu" value="<?php echo $idProduktu; ?>">
                            <input type="number" name="Množství[<?php echo $idProduktu; ?>]" 
                            value="<?php echo $produkt['Množství']; ?>" min="1" class="mnozstvi-input">
                            <input type="submit" name="aktualizovat_kosik" value="Aktualizovat množství">
                            <input type="submit" name="odebrat" value="Odebrat" class="odebrat-btn">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
            <p class="kosik-text">Celková cena: <?php echo $celkova_cena; ?> Kč</p>
            <a href="objednavka.php" class="btn-primary">Objednat</a>
        </form>
    <?php else: ?>
        <p class="kosik-text">Váš košík je prázdný.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php include 'footer.php'; ?>