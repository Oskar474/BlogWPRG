<?php
include('../includes/Database.php');
include('../includes/functions.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $db = Database::getInstance();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit();
        } else {
            $error = "Nieprawidłowe hasło.";
        }
    } else {
        $error = "Nieprawidłowy nick.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logowanie</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<header class="header">
    <h1>Logowanie</h1>
    <nav>
        <a href="index.php">Strona główna</a>
        <a href="register.php">Zarejestruj się</a>
    </nav>
</header>
<div class="container">
    <form method="POST" class="universal-form">
        <h2>Logowanie</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" name="username" id="username" required><br>
        <label for="password">Hasło:</label>
        <input type="password" name="password" id="password" required><br>
        <input type="submit" value="Login">
        <a href="forgot_password.php" class="reset-password-link">Zapomniałeś hasła?</a>
    </form>
</div>
<footer>
    <p>&copy; 2024 Mój Piękny Blog. Wszystkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
