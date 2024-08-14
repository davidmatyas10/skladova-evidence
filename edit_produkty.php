<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

$idProduktu = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['IDProduktu']) ? $_POST['IDProduktu'] : null);

if($idProduktu) {
    $sql = "SELECT IDProduktu, NázevProduktu, Cena, Skladem, Popis FROM produkty WHERE IDProduktu = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $idProduktu);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['IDProduktu'])) {
    $idproduktu = $_POST['IDProduktu'];
    $nazevproduktu = $_POST['NázevProduktu'];
    $cena = $_POST['Cena'];
    $skladem = $_POST['Skladem'];
    $popis = $_POST['Popis'];

    $sql = "UPDATE produkty SET NázevProduktu=?, Cena=?, Skladem=?, Popis=? WHERE IDProduktu=?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("siisi", $nazevproduktu, $cena, $skladem, $popis, $idproduktu);
    $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            header('Location: seznam_produktu.php');
            exit();
        } else {
            echo "Nebyly provedeny žádné změny nebo produkt s tímto id neexistuje.";
        }
        
        $stmt->close();
    } else {
        echo "Něco se pokazilo: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Úprava Produktu</title>
    <link rel="stylesheet" href="edit_produkty.css">
</head>
<body>
<div class="edit-product-container">
    <h1>Úprava Produktu</h1>
    <form action="edit_produkty.php" method="post">
        <input type="hidden" name="IDProduktu" value="<?php echo $idProduktu; ?>">

        <label for="NázevProduktu">Název Produktu:</label>
        <input type="text" id="NázevProduktu" name="NázevProduktu" value="<?php echo htmlspecialchars($product['NázevProduktu']); ?>" required>

        <label for="Cena">Cena:</label>
        <input type="number" id="Cena" name="Cena" value="<?php echo htmlspecialchars($product['Cena']); ?>" required>

        <label for="Skladem">Skladem:</label>
        <input type="number" id="Skladem" name="Skladem" value="<?php echo htmlspecialchars($product['Skladem']); ?>" required>

        <label for="Popis">Popis:</label>
        <input type="text" id="Popis" name="Popis" value="<?php echo htmlspecialchars($product['Popis']); ?>" required>

        <input type="submit" value="Aktualizovat">
    </form>
</div>
</body>
</html>