<?php
include('../includes/Database.php');
include('../includes/functions.php');
include('../includes/mail_config.php');
session_start();

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<form method="POST" class="universal-form">
    Email: <input type="email" name="email" required><br>
    <input type="submit" value="Wyślij link resetujący hasło">
</form>

<p style="text-align: center">
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$email = $_POST['email'];
$token = bin2hex(openssl_random_pseudo_bytes(16));
$db = Database::getInstance();
$conn = $db->getConnection();

$stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
$stmt->bind_param('ss', $token, $email);
if ($stmt->execute()) {
send_reset_email($email, $token);
} else {
echo "Błąd: " . $stmt->error;
}
}
?>
</p>
</html>