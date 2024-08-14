<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (isset($_POST['pridat_do_kosiku'])) {
    $produkt_id = $_POST['produkt_id'];

    if (isset($_SESSION['kosik'][$produkt_id])) {
        $_SESSION['kosik'][$produkt_id]++;
    } else {
        $_SESSION['kosik'][$produkt_id] = 1;
    }

    header('Location: eshop.php');
    exit();
} else {
    header('Location: eshop.php');
    exit();
}

?>