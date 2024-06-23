<?php
include('../includes/Database.php');
include('../includes/functions.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../public/index.php');
    exit();
}

$db = Database::getInstance();
$conn = $db->getConnection();

$sql = "SELECT users.username, users.email, COUNT(comments.id) AS comment_count 
        FROM users 
        LEFT JOIN comments ON users.id = comments.user_id 
        GROUP BY users.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $filename = "komentarze.txt";
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename=' . $filename);

    while ($row = $result->fetch_assoc()) {
        echo "Nazwa użytkownika: " . $row['username'] . " | Email: " . $row['email'] . " | Ilość komentarzy: " . $row['comment_count'] . "\n";
    }
} else {
    echo "Nie znaleziono użytkowników.";
}

$conn->close();
?>
