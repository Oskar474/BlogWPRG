
<?php
include('../includes/Database.php');
include('../includes/functions.php');
session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'author')) {
    header('Location: ../public/index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author_id = $_SESSION['user_id'];
    $image = '';

    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image = basename($_FILES["image"]["name"]);
    }

    $db = Database::getInstance();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("INSERT INTO posts (title, content, author_id, image, publish_date) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param('ssis', $title, $content, $author_id, $image);

    if ($stmt->execute()) {
        header('Location: ../public/index.php');
        exit();
    } else {
        $error = "Błąd dodawania posta.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dodaj post</title>
    <link rel="stylesheet" type="text/css" href="../public/styles.css">
</head>
<body>
<header class="header">
    <h1>Dodawanie postu</h1>
    <nav>
        <a href="../public/index.php">Strona główna</a>
        <a href="../public/logout.php">Wyloguj się</a>

    </nav>
</header>
<div class="container">
    <form method="POST" enctype="multipart/form-data">
        <h2>Dodaj nowy post</h2>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
        <label for="title">Tytuł:</label>
        <input type="text" name="title" id="title" required><br>
        <label for="content">Treść:</label>
        <textarea name="content" id="content" rows="10" required></textarea><br>
        <label for="image">Zdjęcie:</label>
        <input type="file" name="image" id="image"><br><br>
        <input type="submit" value="Dodaj post">
    </form>
</div>
<footer>
    <p>&copy; 2024 Mój Piękny Blog. Wszystkie prawa zastrzeżone.</p>
</footer>
</body>
</html>
