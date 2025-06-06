<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: logowanie.html");
    exit();
}

// Dane do połączenia z bazą
$host = "localhost";
$db_user = "ytsilpwxpv_ziomek";
$db_pass = "123Biedr@456";
$db_name = "ytsilpwxpv_zwado";

// Połączenie
$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych.");
}

// Pobierz dane z formularza i przefiltruj
$name = trim($_POST['name'] ?? '');
$species = trim($_POST['species'] ?? '');
$breed = trim($_POST['breed'] ?? '');
$age = $_POST['age'] ?? null;
$description = trim($_POST['description'] ?? '');
$price = trim($_POST['price'] ?? null);
$username = $_SESSION['username'];

// Walidacja ceny
if (!empty($price) && !preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
    $_SESSION['error'] = "Cena musi być liczbą (maksymalnie 2 miejsca po przecinku).";
    header("Location: adopcja.php");
    exit();
}
$price = !empty($price) ? (float)$price : null;

// Obsługa przesyłania zdjęcia
$upload_dir = 'uploads/';
$image_name = $_FILES['animal_image']['name'];
$tmp_name = $_FILES['animal_image']['tmp_name'];
$target_file = $upload_dir . basename($image_name);

if (!empty($image_name) && move_uploaded_file($tmp_name, $target_file)) {
    $image_url = $target_file;
} else {
    $_SESSION['error'] = "Błąd przy przesyłaniu zdjęcia.";
    header("Location: adopcja.php");
    exit();
}

// Walidacja wymaganych pól
if (empty($name) || empty($species) || empty($description) || empty($image_url)) {
    $_SESSION['error'] = "Wypełnij wszystkie wymagane pola!";
    header("Location: adopcja.php");
    exit();
}

// Walidacja wieku
if (!empty($age) && !ctype_digit($age)) {
    $_SESSION['error'] = "Wiek musi być liczbą całkowitą.";
    header("Location: adopcja.php");
    exit();
}

// Walidacja imienia
if (!preg_match('/^[\p{L}\s-]+$/u', $name)) {
    $_SESSION['error'] = "Imię nie może zawierać cyfr ani znaków specjalnych.";
    header("Location: adopcja.php");
    exit();
}

// Pobierz user_id
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Błąd: użytkownik nie istnieje.");
}
$user_id = $user['id'];

// Zapis ogłoszenia do bazy
$stmt = $conn->prepare("INSERT INTO ogloszenia (user_id, name, species, breed, age, description, image_url, price, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("isssissd", $user_id, $name, $species, $breed, $age, $description, $image_url, $price);

if ($stmt->execute()) {
    header("Location: mojeogloszenia.php?status=sukces");
    exit();
} else {
    $_SESSION['error'] = "Błąd podczas zapisu ogłoszenia: " . $stmt->error;
    header("Location: adopcja.php");
    exit();
}
?>
