<?php
require 'db.php';

$kategorieOptions = '';
$sql = "SELECT IDKategorie, NázevKategorie FROM kategorie";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $kategorieOptions .= "<option value='" . $row['IDKategorie'] . "'>" . htmlspecialchars($row['NázevKategorie']) . "</option>";
    }
} else {
    echo "Žádné kategorie nebyly nalezeny.";
}

$podkategorieOptions = '';
$sql = "SELECT IDPodkategorie, NázevPodkategorie FROM podkategorie";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $podkategorieOptions .= "<option value='" . $row['IDPodkategorie'] . "'>" . htmlspecialchars($row['NázevPodkategorie']) . "</option>";
    }
} else {
    echo "Žádné podkategorie nebyly nalezeny.";
}

if (isset($_POST['submit'])) {

    $nazevProduktu = $_POST['nazevProduktu'];
    $cena = $_POST['cena'];
    $popis = $_POST['popis'];
    $idKategorie = $_POST['idKategorie'];
    $idPodkategorie = $_POST['idPodkategorie'];
    $skladem = $_POST['skladem'];

    $nazevSouboruObrázku = null;
    if (isset($_FILES['obrazek']) && $_FILES['obrazek']['error'] == 0) {
        $nazevSouboruObrázku = basename($_FILES['obrazek']['name']);
        $cestaUlozeni = 'images/' . $nazevSouboruObrázku;
        move_uploaded_file($_FILES['obrazek']['tmp_name'], $cestaUlozeni);
    }

    $sql = "INSERT INTO produkty (NázevProduktu, Cena, Popis, IDKategorie, IDPodkategorie, nazevSouboruObrázku, Skladem)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sdssssi", $nazevProduktu, $cena, $popis, $idKategorie, $idPodkategorie, $nazevSouboruObrázku, $skladem);
        
        if ($stmt->execute()) {
            echo "Produkt byl úspěšně přidán.";
            header('Location: seznam_produktu.php');
        } else {
            echo "Nepodařilo se přidat produkt: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Nepodařilo se připravit SQL dotaz: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Vytvoření nového produktu</title>
    <link rel="stylesheet" href="produkty.css">
</head>
<body>

<div class="vytvorit-produkt-container">
    <h1>Přidat nový produkt</h1>
    <form action="vytvorit_produkt.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nazevProduktu">Název produktu:</label>
            <input type="text" id="nazevProduktu" name="nazevProduktu" required>
        </div>
        <div class="form-group">
            <label for="cena">Cena:</label>
            <input type="number" id="cena" name="cena" step=".01" required>
        </div>
        <div class="form-group">
            <label for="popis">Popis:</label>
            <textarea id="popis" name="popis" required></textarea>
        </div>
        <div class="form-group">
            <label for="idKategorie">Kategorie:</label>
            <select id="idKategorie" name="idKategorie" required>
                <?php echo $kategorieOptions; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="idPodkategorie">Podkategorie:</label>
            <select id="idPodkategorie" name="idPodkategorie">
                <?php echo $podkategorieOptions; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="obrazek">Obrázek produktu:</label>
            <input type="file" id="obrazek" name="obrazek">
        </div>
        <div class="form-group">
            <label for="skladem">Počet kusů Skladem:</label>
            <input type="number" id="skladem" name="skladem" required>
        </div>
        <button type="submit" name="submit">Přidat produkt</button>
    </form>
</div>

</body>
</html>