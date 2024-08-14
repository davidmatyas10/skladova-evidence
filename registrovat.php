<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php';
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $telefonniCislo = $_POST['telefonniCislo'];
    $mesto = $_POST['mesto'];
    $psc = $_POST['psc'];
    $adresa = $_POST['adresa'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    
    if ($result->num_rows > 0) {
        echo "Uživatel s tímto emailem již existuje.";
    } else {
        $roleID = 2;
        $stmt = $conn->prepare("INSERT INTO users (Email, Heslo, Jmeno, Prijmeni, TelefonniCislo, Mesto, PSC, Adresa, IDRole)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $email, $password, $jmeno, $prijmeni, $telefonniCislo, $mesto, $psc, $adresa, $roleID);
        $stmt->close();
        
        header('Location: prihlasit.php');
        exit();
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Registrace</title>
    <link rel="stylesheet" href="registrovat.css">
</head>
<body>
    <div class="registrace-kontejner">
        <h1>Registrace nového zákazníka</h1>
        <form method="post" action="registrovat.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Heslo:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="jmeno">Jméno:</label>
                <input type="text" id="jmeno" name="jmeno" required>
            </div>
            <div class="form-group">
                <label for="prijmeni">Příjmení:</label>
                <input type="text" id="prijmeni" name="prijmeni" required>
            </div>
            <div class="form-group">
                <label for="telefonniCislo">Telefonní číslo:</label>
                <input type="text" id="telefonniCislo" name="telefonniCislo" required>
            </div>
            <div class="form-group">
                <label for="mesto">Město:</label>
                <input type="text" id="mesto" name="mesto" required>
            </div>
            <div class="form-group">
                <label for="psc">PSČ:</label>
                <input type="text" id="psc" name="psc" required>
            </div>
            <div class="form-group">
                <label for="adresa">Adresa:</label>
                <input type="text" id="adresa" name="adresa" required>
            </div>
            <button type="submit">Registrovat</button>
        </form>
    </div>
</body>
</html>