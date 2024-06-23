<?php
include('../includes/Database.php');
include('../includes/functions.php');
session_start();

if (!isset($_GET['token'])) {
    die("Token nie został przekazany w URL.");
}

$token = $_GET['token'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->bind_param('ss', $new_password, $token);

    if ($stmt->execute()) {
        echo "Hasło zostało zresetowane.";
        header('Location: ../public/index.php');
    } else {
        echo "Błąd: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Resetowanie hasła</title>
    <link rel="stylesheet" href="../public/styles.css">
</head>
<body>
<div class="container">
    <form method="POST" class="universal-form">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" required>
        Nowe hasło:<input type="password" name="new_password"  pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="Hasło musi mieć co najmniej 8 znaków, oraz zawieć przynajmniej jedną dużą literę, jedną małą literę, jedną cyfrę oraz jeden znak specjalny." required><br>
        <a >        Hasło musi mieć co najmniej 8 znaków, oraz zawieć przynajmniej jedną dużą literę, jedną małą literę, jedną cyfrę oraz jeden znak specjalny.</a> <br><br>
        <input type="submit" value="Zresetuj hasło">
    </form>
</div>
</body>
</html>