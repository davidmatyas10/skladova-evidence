<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

$searchTerm = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchTerm = $conn->real_escape_string($_POST['search']);
}

$sql = "SELECT IDProduktu, NázevProduktu, Cena, Skladem, Popis FROM produkty WHERE NázevProduktu LIKE '%$searchTerm%' ORDER BY NázevProduktu";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Seznam produktů</title>
    <link rel="stylesheet" href="admin_styly.css">
</head>
<body>
    <section class="search-and-products">
        <div class="products-container">
            <h1>Seznam Produktů</h1>

            <div class="search-container">
            <form action="seznam_produktu.php" method="post">
                <input type="text" name="search" placeholder="Vyhledat produkt..." value="<?php echo $searchTerm; ?>">
                <button type="submit">Vyhledat</button>
            </form>
            </div>

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
                    <?php while($product = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $product['IDProduktu']; ?></td>
                        <td><?php echo $product['NázevProduktu']; ?></td>
                        <td><?php echo $product['Cena']; ?> Kč</td>
                        <td><?php echo $product['Skladem']; ?></td>
                        <td><?php echo $product['Popis']; ?></td>
                        <td>
                            <a href="edit_produkty.php?id=<?php echo $product['IDProduktu']; ?>" class="edit-btn">Upravit</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>