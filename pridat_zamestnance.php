<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'db.php';

    $email = $_POST['email'];
    $heslo = $_POST['heslo'];
    $jmeno = $_POST['jmeno'];
    $prijmeni = $_POST['prijmeni'];
    $telefonniCislo = $_POST['telefonniCislo'];
    $mesto = $_POST['mesto'];
    $psc = $_POST['psc'];
    $idRole = $_POST['idRole'];

    $sql = "INSERT INTO users (Email, Heslo, Jmeno, Prijmeni, TelefonniCislo, Mesto, PSC, IDRole)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssi", $email, $heslo, $jmeno, $prijmeni, $telefonniCislo, $mesto, $psc, $idRole);
        
        if ($stmt->execute()) {
            echo "Zaměstnanec byl úspěšně přidán.";
            header('Location: admin_dashboard.php');
        } else {
            echo "Nepodařilo se přidat zaměstnance: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Nepodařilo se připravit SQL dotaz: " . $conn->error;
    }

    $conn->close();
} else {
    require 'db.php';

    $roleOptions = '';
    $sql = "SELECT IDRole, NázevRole FROM role"; 
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $roleOptions .= "<option value='" . $row['IDRole'] . "'>" . htmlspecialchars($row['NázevRole']) . "</option>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Přidat zaměstnance</title>
    <link rel="stylesheet" href="zamestnanci.css">
</head>
<body>

<div class="pridat-zamestnance-container">
    <h1>Přidat zaměstnance</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="heslo">Heslo:</label>
            <input type="password" id="heslo" name="heslo" required>
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
            <input type="text" id="telefonniCislo" name="telefonniCislo">
        </div>
        <div class="form-group">
            <label for="mesto">Město:</label>
            <input type="text" id="mesto" name="mesto">
        </div>
        <div class="form-group">
            <label for="psc">PSČ:</label>
            <input type="text" id="psc" name="psc">
        </div>
        <div class="form-group">
            <label for="idRole">Role:</label>
            <select id="idRole" name="idRole" required>
                <?php
                $sql = "SELECT IDRole, NázevRole FROM role";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['IDRole'] . "'>" . htmlspecialchars($row['NázevRole']) . "</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit">Přidat zaměstnance</button>
    </form>
</div>

</body>
</html>