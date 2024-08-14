<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: prihlasit.php');
    exit();
}

$objednavka_id = $_GET['id'] ?? '';
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Zrušení objednávky</title>
    <link rel="stylesheet" href="eshop.css">
</head>
<body>

<?php include 'head.php'; ?>

<div class="objednavka-container">
    <h1>Zrušení objednávky</h1>
    <?php if ($objednavka_id): ?>
        <p>Objednávka číslo <?php echo htmlspecialchars($objednavka_id); ?> byla úspěšně zrušena.</p>
    <?php else: ?>
        <p>Nebylo zadáno číslo objednávky.</p>
    <?php endif; ?>
    <a href="eshop.php">Zpět na hlavní stránku</a>
</div>

<?php include 'footer.php'; ?>

</body>
</html>