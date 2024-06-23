<?php
include('../includes/Database.php');
include('../includes/functions.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!validate_password($password)) {
        $_SESSION['error'] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        $db = Database::getInstance();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['error'] = "Nazwa użytkownika jest już zajęta.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $_SESSION['error'] = "Adres email jest już zajęty.";
            } else {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
                $stmt->bind_param('sss', $username, $email, $password_hash);

                if ($stmt->execute()) {
                    header('Location: login.php');
                    exit();
                } else {
                    $_SESSION['error'] = "Rejestracja nie powiodła się. Spróbuj ponownie.";
                }
            }
        }

        $stmt->close();
        $conn->close();
    }

    header('Location: register.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rejestracja</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<header class="header">
    <h1>Rejestracja</h1>
    <nav>
        <a href="index.php">Strona główna</a>
        <a href="login.php">Zaloguj się</a>
    </nav>
</header>
<div class="container">
    <form method="POST" class="universal-form">
        <h2>Register</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <p style="color: red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>
        <label for="username">Nazwa użytkownika:</label>
        <input maxlength="20" type="text" name="username" id="username" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>
        <label for="password">Hasło:</label>
        <input maxlength="20" type="password" name="password" id="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" title="        Hasło musi mieć co najmniej 8 znaków, oraz zawieć przynajmniej jedną dużą literę, jedną małą literę, jedną cyfrę oraz jeden znak specjalny." required><br>
        <a >        Hasło musi mieć co najmniej 8 znaków, oraz zawieć przynajmniej jedną dużą literę, jedną małą literę, jedną cyfrę oraz jeden znak specjalny.</a> <br><br>
        <input type="submit" value="Rejestracja">
    </form>
</div>
<footer>
    <p>&copy; 2024 Mój Piękny Blog. Wszystkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
