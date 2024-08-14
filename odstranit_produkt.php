<?php
include 'db.php';

$sql = "SELECT IDProduktu, NázevProduktu FROM Produkty";
$result = $conn->query($sql);

$produkty = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produkty[$row["IDProduktu"]] = $row["NázevProduktu"];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id_produktu"]) && !empty($_POST["id_produktu"])) {
        $id_produktu = $_POST["id_produktu"];

        $sql = "DELETE FROM produkty WHERE IDProduktu = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id_produktu);

            if ($stmt->execute()) {
                header("Location: seznam_produktu.php");
                exit();
            } else {
                echo "Chyba při odstraňování produktu: " . $conn->error;
            }
        }

        $stmt->close();
    } else {
        echo "Nebylo vybráno žádné ID produktu.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odstranit produkt</title>
    <link rel="stylesheet" href="produkty.css">
</head>
<body>
    <h2>Odstranit produkt</h2>
    <form method="post">
        <label for="id_produktu">Vyberte produkt k odstranění:</label><br>
        <select name="id_produktu" id="id_produktu">
            <?php
            foreach ($produkty as $id => $nazev) {
                echo "<option value='" . $id . "'>" . $nazev . "</option>";
            }
            ?>
        </select><br><br>
        <input type="submit" value="Odebrat produkt">
    </form>
</body>
</html>