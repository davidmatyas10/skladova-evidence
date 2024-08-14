<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'db.php';

$searchTerm = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $searchTerm = $conn->real_escape_string($_POST['search']);
}

$sql = "SELECT IDProduktu, NázevProduktu, Cena, Skladem FROM produkty WHERE NázevProduktu LIKE '%$searchTerm%' ORDER BY NázevProduktu";
$result = $conn->query($sql);

$sqlAdmini = "SELECT u.ID, u.Jmeno, u.Prijmeni, r.NázevRole AS Role FROM users u JOIN role r ON u.IDRole = r.IDRole WHERE r.NázevRole = 'Admin'";
$resultAdmini = $conn->query($sqlAdmini);

$sqlManageri = "SELECT u.ID, u.Jmeno, u.Prijmeni, r.NázevRole AS Role FROM users u JOIN role r ON u.IDRole = r.IDRole WHERE r.NázevRole = 'Manager'";
$resultManageri = $conn->query($sqlManageri);

$sqlskladnici = "SELECT u.ID, u.Jmeno, u.Prijmeni, r.NázevRole AS Role FROM users u JOIN role r ON u.IDRole = r.IDRole WHERE r.NázevRole = 'Skladník'";
$resultskladnici = $conn->query($sqlskladnici);

$sqlZakaznici = "SELECT u.ID, u.Jmeno, u.Prijmeni, r.NázevRole AS Role FROM users u JOIN role r ON u.IDRole = r.IDRole WHERE r.NázevRole = 'Zákazník'";
$resultZakaznici = $conn->query($sqlZakaznici);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_styly.css">
</head>
<body>
<h1>Admin Dashboard</h1>
<div class="admin-dashboard">
    <aside class="sidebar">
            <div class="user-info">
                <?php if (isset($_SESSION['uzivatel_id'])) : ?>
                    <p>Přihlášený uživatel:<br> 
                        <?php echo $_SESSION['jmeno'] . " " . $_SESSION['prijmeni']; ?><br>
                        Role: <?php echo $_SESSION['nazevrole']; ?>
                    </p>
                <?php endif; ?>
            </div>
            <a href="seznam_produktu.php" class="btnSeznamProduktu">Seznam Produktů</a>
            <a href="vytvorit_produkt.php" class="btnVytvoritProdukt">Vytvořit Produkt</a>
            <a href="odstranit_produkt.php" class="btnOdstranitProdukt">Odstranit Produkt</a>
            <a href="pridat_zamestnance.php" class="btnPridatZamestnance">Přidat Zaměstnance</a>
            <a href="odebrat_zamestnance.php" class="btnOdebratZamestnance">Odebrat Zaměstnance</a>
            <a href="eshop.php" class="btnEshop">Přejít do e-shopu</a>
            <a href="odhlasit.php" class="btnOdhlasit">Odhlásit se</a>
    </aside>

    <div class="dashboard-container">
        <h2>Seznam adminů</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jméno</th>
                    <th>Příjmení</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultAdmini->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['Jmeno']; ?></td>
                    <td><?php echo $row['Prijmeni']; ?></td>
                    <td><?php echo $row['Role']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Seznam manažerů</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jméno</th>
                    <th>Příjmení</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultManageri->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['Jmeno']; ?></td>
                    <td><?php echo $row['Prijmeni']; ?></td>
                    <td><?php echo $row['Role']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Seznam skladníků</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jméno</th>
                    <th>Příjmení</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultskladnici->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['Jmeno']; ?></td>
                    <td><?php echo $row['Prijmeni']; ?></td>
                    <td><?php echo $row['Role']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Seznam zákazníků</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jméno</th>
                    <th>Příjmení</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultZakaznici->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['Jmeno']; ?></td>
                    <td><?php echo $row['Prijmeni']; ?></td>
                    <td><?php echo $row['Role']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>