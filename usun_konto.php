<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['username'])) {
    header("HTTP/1.0 401 Unauthorized");
    echo "Musisz być zalogowany, aby usunąć konto.";
    exit();
}

$host     = "localhost";
$db_user  = "ytsilpwxpv_ziomek";
$db_pass  = "123Biedr@456";
$db_name  = "ytsilpwxpv_zwado";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents('php://input'), $deleteData);

    // Pobierz username z sesji
    $username = $_SESSION['username'];

    // Usuń użytkownika
    $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        // Wyczyść sesję po usunięciu konta
        session_destroy();
        echo "Konto zostało usunięte.";
    } else {
        echo "Błąd przy usuwaniu konta: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("HTTP/1.0 405 Method Not Allowed");
    echo "Użyj metody DELETE, aby usunąć konto.";
}

$conn->close();
?>
