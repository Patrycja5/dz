<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: logowanie.html");
    exit();
}

$host = "localhost";
$db_user = "ytsilpwxpv_ziomek";         
$db_pass = "123Biedr@456";       
$db_name = "ytsilpwxpv_zwado";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    die("Nieprawidłowe ID ogłoszenia.");
}

// Pobierz ogłoszenie
$stmt = $conn->prepare("SELECT * FROM ogloszenia WHERE id = ? AND user_id = (SELECT id FROM users WHERE username = ?)");
$stmt->bind_param("is", $id, $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$ad = $result->fetch_assoc();

if (!$ad) {
    die("Ogłoszenie nie istnieje lub nie masz do niego dostępu.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $species = trim($_POST['species']);
    $breed = trim($_POST['breed']);
    $age = $_POST['age'];
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $price = $price !== '' ? (float)$price : null;

    $errors = [];

if (empty($name) || empty($species) || empty($description)) {
    $errors[] = "Imię, gatunek i opis są wymagane.";
}

if (!preg_match('/^[\p{L}\s-]+$/u', $name)) {
    $errors[] = "Imię może zawierać tylko litery, spacje i myślniki.";
}

if (!empty($age) && !ctype_digit($age)) {
    $errors[] = "Wiek musi być liczbą całkowitą.";
}

if (!empty($price) && !preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
    $errors[] = "Cena musi być liczbą (maksymalnie 2 miejsca po przecinku).";
}

if (empty($errors)) {
    $update = $conn->prepare("UPDATE ogloszenia SET name=?, species=?, breed=?, age=?, description=?, price=? WHERE id=?");
    $update->bind_param("sssissi", $name, $species, $breed, $age, $description, $price, $id);
    if ($update->execute()) {
        header("Location: mojeogloszenia.php?status=edytowano");
        exit();
    } else {
        echo "Błąd edycji: " . $update->error;
    }
} else {
    foreach ($errors as $error) {
        echo "<p style='color: red;'>❌ $error</p>";
    }
}
}
?>

<h2>Edytuj ogłoszenie</h2>
<form method="post">
    Imię: <input type="text" name="name" value="<?= htmlspecialchars($ad['name']) ?>" required pattern="^[\p{L}\s-]+$" title="Tylko litery, spacje i myślniki"><br>
    Gatunek: <input type="text" name="species" value="<?= htmlspecialchars($ad['species']) ?>" required><br>
    Rasa: <input type="text" name="breed" value="<?= htmlspecialchars($ad['breed']) ?>"><br>
    Wiek: <input type="number" name="age" value="<?= htmlspecialchars($ad['age']) ?>" min="0"><br>
    Opis: <textarea name="description" required><?= htmlspecialchars($ad['description']) ?></textarea><br>
    Cena: <input type="text" name="price" value="<?= htmlspecialchars($ad['price']) ?>" pattern="^\d+(\.\d{1,2})?$" title="Liczba z maks. 2 miejscami po przecinku"><br>
    <button type="submit">Zapisz zmiany</button>
</form>

<a href="mojeogloszenia.php">⬅ Wróć</a>
