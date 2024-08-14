<?php

require 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$sqlEmployees = "SELECT ID, Jmeno, Prijmeni FROM users";
$resultEmployees = $conn->query($sqlEmployees);

$sqlRoles = "SELECT IDRole, NázevRole FROM role";
$resultRoles = $conn->query($sqlRoles);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $id = $_POST['ID'];

    $sql = "DELETE FROM users WHERE ID = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "Zaměstnanec byl úspěšně odebrán.";
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Nepodařilo se odebrat zaměstnance: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Nepodařilo se připravit SQL dotaz: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Odebrat zaměstnance</title>
    <link rel="stylesheet" href="zamestnanci.css">
</head>
<body>

<div class="odebrat-zamestnance-container">
    <h1>Odebrat zaměstnance</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="ID">Vyberte zaměstnance:</label>
            <select id="ID" name="ID" required>
                <option value="">Vyberte zaměstnance</option>
                <?php
                $sql = "SELECT users.ID, users.Jmeno, users.Prijmeni, role.NázevRole FROM 
                users INNER JOIN role ON users.IDRole = role.IDRole WHERE role.NázevRole = 'Manager' OR role.NázevRole = 'Skladník'";
                $result = $conn->query($sql);
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['ID'] . "'>" . $row['Jmeno'] . ' ' . $row['Prijmeni'] . ' (' . $row['NázevRole'] . ")</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" name="submit">Odebrat zaměstnance</button>
    </form>
</div>

</body>
</html>