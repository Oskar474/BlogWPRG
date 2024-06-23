<?php
include('../includes/Database.php');
include('../includes/functions.php');
session_start();

$db = Database::getInstance();
$conn = $db->getConnection();

$date_filter = '';
if (isset($_GET['date'])) {
    $date_filter = $_GET['date'];
    $stmt = $conn->prepare("SELECT * FROM posts WHERE DATE(publish_date) = ? ORDER BY publish_date DESC");
    $stmt->bind_param('s', $date_filter);
} else {
    $stmt = $conn->prepare("SELECT * FROM posts ORDER BY publish_date DESC");
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mój Piękny Blog</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<header>
    <h1>Mój Piękny Blog</h1>
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
    <form method="GET" action="index.php" class="date-filter-form">
        <label for="date">Filtruj posty po dacie:</label>
        <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($date_filter); ?>">
        <input type="submit" value="Filtruj">
        <a href="index.php" class="clear-filter-button">Wyczyść filtry</a>
    </form>
    <?php while($post = $result->fetch_assoc()): ?>
        <div class="post">
            <h2><a href="post.php?id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></h2>
            <p><?php echo substr($post['content'], 0, 100); ?>...</p>
            <?php if ($post['image']): ?>
                <img class="post-image" src="../uploads/<?php echo $post['image']; ?>" alt="<?php echo $post['title']; ?>">
            <?php endif; ?>
            <p class="post-meta"><small>Opublikowano <?php echo $post['publish_date']; ?></small></p>
            <?php if (isset($_SESSION['user_id']) && ($_SESSION['role'] === 'admin' || $_SESSION['user_id'] == $post['author_id'])): ?>
                <a href="../admin/edit_post.php?id=<?php echo $post['id']; ?>">Edytuj</a><br><br>
                <a href="../admin/delete_post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Czy na pewno chcesz usunąć ten post?');">Usuń</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>
<footer>
    <p>&copy; 2024 Mój Piękny Blog. Wszystkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
