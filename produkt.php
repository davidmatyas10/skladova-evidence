<?php
include 'db.php';

$nazevProduktu = '';
$popisProduktu = '';
$cenaProduktu = '';
$obrazekProduktu = '';

$produktId = isset($_GET['id']) ? $_GET['id'] : die("Produkt nebyl specifikován.");

$produktId = $conn->real_escape_string($produktId);

$sql = "SELECT NázevProduktu, Popis, Cena, nazevSouboruObrázku FROM produkty WHERE IDProduktu = '$produktId'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nazevProduktu = htmlspecialchars($row["NázevProduktu"], ENT_QUOTES, 'UTF-8');
    $popisProduktu = htmlspecialchars($row["Popis"], ENT_QUOTES, 'UTF-8');
    $cenaProduktu = htmlspecialchars($row["Cena"], ENT_QUOTES, 'UTF-8');
    $obrazekProduktu = "images/" . htmlspecialchars($row["nazevSouboruObrázku"], ENT_QUOTES, 'UTF-8');
} else {
    echo "Produkt s ID $produktId nebyl nalezen.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Detail produktu</title>
    <link rel="stylesheet" href="eshop.css">
</head>
<body>
<?php include 'head.php'; ?>

<div class="produkt-detail">
    <div class="produkt-obrazek-wrapper">
        <img src="<?php echo $obrazekProduktu; ?>" alt="<?php echo $nazevProduktu; ?>" class="produkt-obrazek-detail">
    </div>
    <div class="produkt-info">
        <h1><?php echo $nazevProduktu; ?></h1>
        <p><?php echo $popisProduktu; ?></p>
        <p class="produkt-cena">Cena: <?php echo $cenaProduktu; ?> Kč</p>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>