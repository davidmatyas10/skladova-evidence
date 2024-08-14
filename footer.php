<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="eshop.css">
</head>
<body>
    
<footer class="site-footer">
    <nav class="footer-nav">
        <ul>
            <?php if ($current_page != 'o_nas.php'): ?>
                <li><a href="o_nas.php">O nás</a></li>
            <?php endif; ?>
            <?php if ($current_page != 'podminky.php'): ?>
                <li><a href="podminky.php">Obchodní podmínky</a></li>
            <?php endif; ?>
            <?php if ($current_page != 'private.php'): ?>
                <li><a href="private.php">Soukromí a cookies</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <p>&copy; 2024 David Matyáš. Všechna práva vyhrazena.</p>
</footer>

</body>
</html>