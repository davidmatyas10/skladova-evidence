<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: prihlasit.php');
    exit();
}

$jmeno = $_SESSION['jmeno'] ?? 'Nedefinováno';
$prijmeni = $_SESSION['prijmeni'] ?? 'Nedefinováno';
$email = $_SESSION['email'] ?? 'Nedefinováno';
$heslo = $_SESSION['heslo'] ?? 'Nedefinováno';
$telefonnicislo = $_SESSION['telefonnicislo'] ?? 'Nedefinováno';
$mesto = $_SESSION['mesto'] ?? 'Nedefinováno';
$adresa = $_SESSION['adresa'] ?? 'Nedefinováno';
$psc = $_SESSION['psc'] ?? 'Nedefinováno';

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Profil uživatele</title>
    <link rel="stylesheet" href="profil.css">
</head>
<body>

<?php include 'head.php';?>

<div class="profil-kontejner">
    <h1>Profil uživatele</h1>
    <div class="profil-radek">
        <span class="profil-nadpis">Jméno:</span>
        <span class="profil-hodnota"><?php echo htmlspecialchars($jmeno); ?></span>
    </div>

    <div class="profil-radek">
        <span class="profil-nadpis">Příjmení:</span>
        <span class="profil-hodnota"><?php echo htmlspecialchars($prijmeni); ?></span>
    </div>

    <div class="profil-radek">
        <span class="profil-nadpis">Email:</span>
        <span class="profil-hodnota"><?php echo htmlspecialchars($email); ?></span>
    </div>

    <div class="profil-radek">
        <span class="profil-nadpis">Telefonní číslo:</span>
        <span class="profil-hodnota"><?php echo htmlspecialchars($telefonnicislo); ?></span>
    </div>

    <div class="profil-radek">
        <span class="profil-nadpis">Město:</span>
        <span class="profil-hodnota"><?php echo htmlspecialchars($mesto); ?></span>
    </div>

    <div class="profil-radek">
        <span class="profil-nadpis">Adresa:</span>
        <span class="profil-hodnota"><?php echo htmlspecialchars($adresa); ?></span>
    </div>

    <div class="profil-radek">
        <span class="profil-nadpis">PSČ:</span>
        <span class="profil-hodnota"><?php echo htmlspecialchars($psc); ?></span>
    </div>

    <div class="profil-radek">
        <button class="edit-profile-btn" onclick="window.location.href = 'upravit_profil.php';">Upravit údaje</button>
        <button class="edit-profile-btn" onclick="document.getElementById('change-password-form').style.display = 'block';">Změnit heslo</button>
    </div>

    <div id="change-password-form" style="display: none;">
        <form action="zmenit_heslo.php" method="post">
            <div class="profil-radek">
                <label for="stare_heslo">Stávající heslo:</label>
                <input type="password" id="stare_heslo" name="stare_heslo" required>
            </div>
            <div class="profil-radek">
                <label for="nove_heslo">Nové heslo:</label>
                <input type="password" id="nove_heslo" name="nove_heslo" required>
            </div>
            <div class="profil-radek">
                <label for="potvrdit_nove_heslo">Potvrdit nové heslo:</label>
                <input type="password" id="potvrdit_nove_heslo" name="potvrdit_nove_heslo" required>
            </div>
            <div class="profil-radek">
                <button type="submit" name="submit">Uložit změny</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php';?>

</body>
</html>