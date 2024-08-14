<?php
require 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $successMessage = '';

    foreach ($_POST['id'] as $index => $id) {
        $skladem = $_POST['skladem'][$index];

        $sqlUpdate = "UPDATE produkty SET Skladem = ? WHERE IDProduktu = ?";
        $stmt = $conn->prepare($sqlUpdate);
        $stmt->bind_param("ii", $skladem, $id);
        if ($stmt->execute()) {
            $successMessage = "Produkty byly úspěšně aktualizovány.";
        } else {
            $successMessage = "Nepodařilo se aktualizovat produkty: " . $stmt->error;
            break;
        }
        $stmt->close();
    }

    header('Location: skladnik_dashboard.php');
    exit();
}

$sql = "SELECT * FROM produkty";
$result = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Skladník Dashboard</title>
    <link rel="stylesheet" href="skladnik_dashboard.css">
</head>
<body>

<h1>Skladník Dashboard</h1>
<div class="container">
    <aside class="sidebar">
        <div class="user-info">
            <?php if (isset($_SESSION['uzivatel_id'])) : ?>
                <p>Přihlášený uživatel:<br> 
                    <?php echo $_SESSION['jmeno'] . " " . $_SESSION['prijmeni']; ?><br>
                    Role: <?php echo $_SESSION['nazevrole']; ?>
                </p>
            <?php endif; ?>
        </div>
        <a href="zobrazit_objednavky.php" class="btn-zobrazit-objednavky">Zobrazit Objednávky</a>
        <a href="eshop.php" class="btn-eshop">Návrat na eshop</a>
        <a href="odhlasit.php" class="btn-odhlasit">Odhlásit se</a>
    </aside>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <table>
            <tr>
                <th>Název produktu</th>
                <th>Počet kusů skladem</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['NázevProduktu']; ?></td>
                    <td>
                        <input type="hidden" name="id[]" value="<?php echo $row['IDProduktu']; ?>">
                        <input type="number" name="skladem[]" value="<?php echo $row['Skladem']; ?>" required>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <button type="update" name="update">Aktualizovat</button>
    </form>
</div>
</body>
</html>