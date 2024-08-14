<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="eshop.css">
</head>
<body>

    <header class="site-header">
        <div class="logo">
            <a href="eshop.php">
            <img src="images/logo.webp" alt="logo firmy" class="logo">
            </a>
        </div>

        <div id="search-container">
            <form action="hledat.php" method="get">
                <input type="text" name="q" placeholder="Hledat produkty..." class="search-input">
                <button type="submit" class="search-button">Vyhledat</button>
            </form>
        </div>

        <nav class="main-nav">
            <ul>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="objednavky.php">Objednávky</a></li>
                <li><a href="kosik.php">Košík</a></li>
                <?php if (isset($_SESSION['jmeno']) && isset($_SESSION['prijmeni'])): ?>
                    <li><a href="odhlasit.php">Odhlásit se</a></li>
                    <li class="user-name"><?php echo htmlspecialchars($_SESSION['jmeno']) . ' ' . htmlspecialchars($_SESSION['prijmeni']); ?></li>
                <?php else: ?>
                    <li><a href="prihlasit.php">Přihlásit se</a></li>
                <?php endif; ?>

                <?php
                if (isset($_SESSION['idrole']) && $_SESSION['idrole'] == 1) {
                    echo '<li><a href="manager_dashboard.php">Manažerský dashboard</a></li>';
                }

                if (isset($_SESSION['idrole']) && $_SESSION['idrole'] == 3) {
                    echo '<li><a href="admin_dashboard.php">Admin dashboard</a></li>';
                }

                if (isset($_SESSION['idrole']) && $_SESSION['idrole'] == 4) {
                    echo '<li><a href="skladnik_dashboard.php">Skladnik dashboard</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>
</body>
</html>