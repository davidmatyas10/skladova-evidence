<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['uzivatel_id'])) {
    header('Location: prihlasit.php');
    exit();
}

$jmeno = $_SESSION['jmeno'] ?? '';
$prijmeni = $_SESSION['prijmeni'] ?? '';
$email = $_SESSION['email'] ?? '';
$heslo = $_SESSION['heslo'] ?? '';
$telefonnicislo = $_SESSION['telefonnicislo'] ?? '';
$mesto = $_SESSION['mesto'] ?? '';
$adresa = $_SESSION['adresa'] ?? '';
$psc = $_SESSION['psc'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nove_jmeno = $_POST['jmeno'];
    $nove_prijmeni = $_POST['prijmeni'];
    $novy_email = $_POST['email'];
    $nove_heslo = $_POST['heslo'];
    $nove_telefonni_cislo = $_POST['telefonni_cislo'];
    $nove_mesto = $_POST['mesto'];
    $nova_adresa = $_POST['adresa'];
    $nove_psc = $_POST['psc'];
    require 'db.php';
    
    $sqlUpdate = "UPDATE users SET Jmeno = ?, Prijmeni = ?, Email = ?, Heslo = ?, TelefonniCislo = ?, Mesto = ?, Adresa=?, PSC = ? WHERE ID = ?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("sssssssii", $nove_jmeno, $nove_prijmeni, $novy_email, $nove_heslo, $nove_telefonni_cislo, $nove_mesto, $nova_adresa, 
    $nove_psc, $_SESSION['uzivatel_id']);
    if ($stmt->execute()) {
        $_SESSION['jmeno'] = $nove_jmeno;
        $_SESSION['prijmeni'] = $nove_prijmeni;
        $_SESSION['email'] = $novy_email;
        $_SESSION['heslo'] = $nove_heslo;
        $_SESSION['telefonnicislo'] = $nove_telefonni_cislo;
        $_SESSION['mesto'] = $nove_mesto;
        $_SESSION['adresa'] = $nova_adresa;
        $_SESSION['psc'] = $nove_psc;
        
        header('Location: profil.php');
        exit();
    } else {
        echo "Nastala chyba při aktualizaci údajů: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Upravit profil</title>
    <link rel="stylesheet" href="upravit_profil.css">
</head>
<body>
<?php include 'head.php';?>

<div class="profil-kontejner">
    <h1>Upravit profil</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="profil-radek">
            <label for="jmeno">Jméno:</label>
            <input type="text" id="jmeno" name="jmeno" value="<?php echo htmlspecialchars($jmeno); ?>" required>
        </div>

        <div class="profil-radek">
            <label for="prijmeni">Příjmení:</label>
            <input type="text" id="prijmeni" name="prijmeni" value="<?php echo htmlspecialchars($prijmeni); ?>" required>
        </div>

        <div class="profil-radek">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="profil-radek">
            <label for="telefonni_cislo">Telefonní číslo:</label>
            <input type="text" id="telefonni_cislo" name="telefonni_cislo" value="<?php echo htmlspecialchars($telefonnicislo); ?>" required>
        </div>

        <div class="profil-radek">
            <label for="mesto">Město:</label>
            <input type="text" id="mesto" name="mesto" value="<?php echo htmlspecialchars($mesto); ?>" required>
        </div>

        <div class="profil-radek">
            <label for="adresa">Adresa:</label>
            <input type="text" id="adresa" name="adresa" value="<?php echo htmlspecialchars($adresa); ?>" required>
        </div>

        <div class="profil-radek">
            <label for="psc">PSČ:</label>
            <input type="text" id="psc" name="psc" value="<?php echo htmlspecialchars($psc); ?>" required>
        </div>

        <div class="profil-radek">
            <button type="submit" name="submit">Uložit změny</button>
        </div>
    </form>
</div>
<?php include 'footer.php';?>
</body>
</html>