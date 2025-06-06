<?php
// =======================================================
// edytuj_profil.php
// =======================================================

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1) Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['username'])) {
    header("Location: logowanie.html");
    exit();
}

// 2) Połączenie z bazą
$host     = "localhost";
$db_user  = "ytsilpwxpv_ziomek";
$db_pass  = "123Biedr@456";
$db_name  = "ytsilpwxpv_zwado";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// 3) Pobranie bieżących danych użytkownika (email, phone, address)
$username = $conn->real_escape_string($_SESSION['username']);
$stmt     = $conn->prepare("SELECT id, email, phone, address FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id, $email, $phone, $address);
if (!$stmt->fetch()) {
    die("Nie znaleziono danych użytkownika w bazie.");
}
$stmt->close();

// 4) Obsługa formularza (edycja danych: tylko phone i address)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobranie nowych wartości i zabezpieczenie ich
    $nowyPhone   = $conn->real_escape_string(trim($_POST['phone']   ?? ''));
    $nowyAddress = $conn->real_escape_string(trim($_POST['address'] ?? ''));

    // Prosta walidacja: sprawdź, czy pola nie są puste
    if ($nowyPhone === '' || $nowyAddress === '') {
        $error = "Pole telefonu i miejsce zamieszkania są wymagane.";
    } else {
        // Aktualizacja w tabeli users tylko kolumn phone i address
        $updateStmt = $conn->prepare("
            UPDATE users 
            SET phone = ?, address = ? 
            WHERE id = ?
        ");
        $updateStmt->bind_param("ssi", $nowyPhone, $nowyAddress, $user_id);
        if ($updateStmt->execute()) {
            $success = "Dane profilu zostały pomyślnie zaktualizowane.";
            // Odświeżamy zmienne, by od razu wyświetlić nowe wartości
            $phone   = $nowyPhone;
            $address = $nowyAddress;
        } else {
            $error = "Błąd podczas aktualizacji: " . $updateStmt->error;
        }
        $updateStmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Edytuj profil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="szata.css">
  <style>
    body { font-family: 'Inter', sans-serif; background: #f7f7f7; }
    .container {
      max-width: 500px;
      margin: 50px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h2 { text-align: center; margin-bottom: 20px; }
    form label { display: block; margin-top: 10px; }
    form input, form button {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }
    form input[disabled] {
      background-color: #eee;
      cursor: not-allowed;
    }
    form button {
      background-color: #2c98f0;
      color: white;
      border: none;
      cursor: pointer;
    }
    form button:hover { background-color: #217bd0; }
    .feedback {
      margin-top: 15px;
      padding: 10px;
      border-radius: 6px;
      font-size: 0.95rem;
    }
    .feedback.error   { background: #fdecea; color: #b71c1c; }
    .feedback.success { background: #e8f5e9; color: #2e7d32; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Edytuj profil</h2>

    <?php if (!empty($error)): ?>
      <div class="feedback error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="feedback success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" action="edytuj_profil.php">
      <label>Nazwa użytkownika:
        <input type="text" value="<?= htmlspecialchars($_SESSION['username']) ?>" disabled>
      </label>

      <label>E‑mail (zarejestrowany):
        <input type="email" value="<?= htmlspecialchars($email) ?>" disabled>
      </label>

      <label>Telefon:
        <input type="text" name="phone" value="<?= htmlspecialchars($phone) ?>" required>
      </label>

      <label>Miejsce zamieszkania:
        <input type="text" name="address" value="<?= htmlspecialchars($address) ?>" required>
      </label>

      <button type="submit">Zapisz zmiany</button>
    </form>
  </div>
</body>
</html>
