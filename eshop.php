<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="eshop.css">
</head>
<body>
    <?php include 'head.php';?>

    <div class="produkty-kontejner">
    <?php include 'nactiProdukty.php'; nactiProdukty(); ?>
    </div>

    <?php include 'footer.php';?>
</body>
</html>