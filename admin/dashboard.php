<?php
include('../includes/Database.php');
include('../includes/functions.php');
session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'author')) {
    header('Location: ../public/index.php');
    exit();
}

$db = Database::getInstance();
$conn = $db->getConnection();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel Administratora</title>
    <link rel="stylesheet" type="text/css" href="../public/styles.css">
</head>
<body>
<header class="header">
    <h1>Administracyjny Panel Sterowania</h1>
    <nav>
        <a href="../public/index.php">Strona główna</a>
        <a href="add_post.php">Dodaj Post</a>
        <a href="../public/logout.php">Wyloguj się</a>
    </nav>
</header>
<div class="container">
    <h2>Witaj w administracyjnym panelu sterowania!</h2>
    <p>Użyj przycisków powyżej aby zarządzać blogiem.</p>
    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="export_users.php" class="button">Pobierz raport użytkowników</a>
    <?php endif; ?>
</div>
<footer>
    <p>&copy; 2024 Mój Piękny Blog. Wszystkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
