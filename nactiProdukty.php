<?php
function nactiProdukty() {
    include 'db.php';

    $sql = "SELECT IDProduktu, NázevProduktu, Cena, Popis, nazevSouboruObrázku FROM Produkty";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='produkty-kontejner'>";
        while($row = $result->fetch_assoc()) {
            echo "<div class='produkt'>";

            echo "<h3 class='produkt-nazev'>" . htmlspecialchars($row["NázevProduktu"], ENT_QUOTES, 'UTF-8'). "</h3>";
            echo "<a href='produkt.php?id=" . $row["IDProduktu"] . "'>";
            if($row["nazevSouboruObrázku"]) {
                echo "<img src='images/" . htmlspecialchars($row["nazevSouboruObrázku"], ENT_QUOTES, 'UTF-8'). "' alt='" 
                . htmlspecialchars($row["NázevProduktu"], ENT_QUOTES, 'UTF-8'). "' class='produkt-obrazek'>";

            }
            echo "<a>";

            echo "<p class='produkt-cena'>Cena: " . htmlspecialchars($row["Cena"], ENT_QUOTES, 'UTF-8'). " Kč</p>";
                echo "<form action='pridat_do_kosiku.php' method='post'>";
                echo "<input type='hidden' name='produkt_id' value='".$row["IDProduktu"]."'>";
                echo "<input type='submit' name='pridat_do_kosiku' value='Přidat do košíku'>";
                echo "</form>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "0 results";
    }
    $conn->close();
}
?>