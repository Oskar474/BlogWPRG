<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
session_start();

$messageSent = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '';
        $mail->Password = '';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;


        $mail->setFrom($email, $name);
        $mail->addAddress('');

        $mail->isHTML(true);
        $mail->Subject = 'Formularz kontaktowy';
        $mail->Body    = '<h1>Wiadomość z formularza kontaktowego</h1><p><strong>Imię i nazwisko:</strong> ' . $name . '</p><p><strong>Email:</strong> ' . $email . '</p><p><strong>Treść:</strong> ' . $message . '</p>';

        $mail->send();
        $messageSent = true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kontakt</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<header>
    <h1>Kontakt</h1>
    <nav>
        <a href="index.php">Strona główna</a>
        <a href="contact.php">Kontakt</a>
        <?php if (isset($_SESSION['username'])): ?>
            <span>Witaj, jesteś zalogowany jako <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php">Wyloguj się</a>
            <?php if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'author'): ?>
                <a href="../admin/dashboard.php">Panel Administratora</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php">Zaloguj się</a>
            <a href="register.php">Zarejestruj się</a>
        <?php endif; ?>
    </nav>
</header>
<div class="container">
    <form method="POST" action="contact.php" class="universal-form">
        <h2>Skontaktuj się z nami</h2>
        <?php if ($messageSent): ?>
            <p style="color: green;">Wiadomość została pomyślnie wysłana!</p>
        <?php endif; ?>
        <label for="name">Imię i nazwisko:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="message">Treść:</label>
        <textarea id="message" name="message" required></textarea><br>
        <input type="submit" value="Wyślij wiadomość">
    </form>
</div>
<footer>
    <p>&copy; 2024 Mój Piękny Blog. Wszystkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
