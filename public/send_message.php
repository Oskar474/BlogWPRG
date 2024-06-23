<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $filename = "../messages.txt";
    $file = fopen($filename, "a");

    $msg = "Name: $name\nEmail: $email\nMessage: $message\n\n";
    fwrite($file, $msg);
    fclose($file);

    echo "Wiadomość wysłana.";
}
?>
