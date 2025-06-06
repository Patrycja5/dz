<?php
session_start();

$host = "localhost";
$db_user = "ytsilpwxpv_ziomek";
$db_pass = "123Biedr@456";
$db_name = "ytsilpwxpv_zwado";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych.");
}

$nadawca_id = $_SESSION['user_id'];
$odbiorca_id = (int)$_POST['odbiorca_id'];
$ogloszenie_id = (int)$_POST['ogloszenie_id'];
$tresc = trim($_POST['tresc']);

if (empty($tresc)) {
    echo "Treść wiadomości nie może być pusta.";
    exit;
}

$conn = new mysqli("localhost", "ytsilpwxpv_ziomek", "123Biedr@456", "ytsilpwxpv_zwado");

$stmt = $conn->prepare("INSERT INTO wiadomosci (nadawca_id, odbiorca_id, ogloszenie_id, tresc) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $nadawca_id, $odbiorca_id, $ogloszenie_id, $tresc);
$stmt->execute();

echo "Wiadomość została wysłana. <a href='ogloszenie.php?id=$ogloszenie_id'>Powrót</a>";
?>