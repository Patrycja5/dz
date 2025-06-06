<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1) Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION['username'])) {
    header("Location: logowanie.html");
    exit();
}

// 2) Połączenie z bazą danych
$host    = "localhost";
$db_user = "ytsilpwxpv_ziomek";
$db_pass = "123Biedr@456";
$db_name = "ytsilpwxpv_zwado";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// 3) Pobierz dane użytkownika (ID, email, phone, address)
$username = $_SESSION['username'];
$stmtU = $conn->prepare("SELECT id, email, phone, address FROM users WHERE username = ?");
$stmtU->bind_param("s", $username);
$stmtU->execute();
$stmtU->bind_result($user_id, $user_email, $user_phone, $user_address);
if (!$stmtU->fetch()) {
    die("Nie znaleziono użytkownika w bazie.");
}
$stmtU->close();

// 4) Pobierz dane z formularza (POST)
$nazwa           = trim($_POST['nazwa']           ?? '');
$data_zaginiecia = trim($_POST['data_zaginiecia'] ?? '');
$opis            = trim($_POST['opis']            ?? '');
$latitude        = trim($_POST['latitude']        ?? '');
$longitude       = trim($_POST['longitude']       ?? '');
$radius          = intval($_POST['radius']        ?? 0);

// 5) Walidacja – czy podstawowe pola nie są puste
$errors = [];
if ($nazwa === '' || $data_zaginiecia === '' || $opis === '' 
    || $latitude === '' || $longitude === '' || $radius <= 0) {
    $errors[] = "Wszystkie pola formularza są wymagane.";
}

// 6) Obsługa uploadu zdjęcia
$zdjecie_tmp  = $_FILES['zdjecie']['tmp_name'] ?? '';
$zdjecie_name = basename($_FILES['zdjecie']['name'] ?? '');
if (!$zdjecie_tmp || $zdjecie_name === '') {
    $errors[] = "Zdjęcie jest wymagane.";
} else {
    // Utwórz folder na zdjęcia, jeśli nie istnieje
    $upload_dir = "uploads/";
    if (!file_exists($upload_dir) && !mkdir($upload_dir, 0777, true)) {
        $errors[] = "Nie udało się utworzyć katalogu uploads/";
    }
    if (empty($errors)) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($zdjecie_tmp);
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Dozwolone są tylko pliki JPG, PNG lub GIF.";
        } else {
            // Generuj unikalną nazwę pliku i przenieś plik
            $zdjecie_path = $upload_dir . time() . "_" . $zdjecie_name;
            if (!move_uploaded_file($zdjecie_tmp, $zdjecie_path)) {
                $errors[] = "Błąd podczas przesyłania zdjęcia.";
            }
        }
    }
}

// 7) Jeżeli są błędy walidacji lub uploadu – wyświetl je i zakończ
if (!empty($errors)) {
    echo "<div style='color:red; font-weight:bold;'>";
    foreach ($errors as $err) {
        echo "<p>" . htmlspecialchars($err) . "</p>";
    }
    echo "</div>";
    exit();
}

// 8) Komponowanie pola „kontakt”
$kontakt = "E-mail: $user_email; Telefon: $user_phone; Miejsce zam.: $user_address";

// 9) Wstawianie zgłoszenia do tabeli `zaginiecia`
$stmt = $conn->prepare(
    "INSERT INTO zaginiecia 
     (nazwa, data_zaginiecia, opis, kontakt, zdjecie, latitude, longitude, radius, uzytkownik_id, odnaleziony) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0)"
);
$stmt->bind_param(
    "ssssssddi",
    $nazwa,
    $data_zaginiecia,
    $opis,
    $kontakt,
    $zdjecie_path,
    $latitude,
    $longitude,
    $radius,
    $user_id
);

if ($stmt->execute()) {
    // 10) Jeżeli INSERT się powiedzie – pokazujemy alert i przekierowujemy na index.php
    ?>
    <!DOCTYPE html>
    <html lang="pl">
    <head>
      <meta charset="utf-8">
      <title>Potwierdzenie</title>
      <script>
        window.onload = function() {
          alert("Ogłoszenie zostało dodane pomyślnie.");
          window.location.href = "index.php";
        };
      </script>
    </head>
    <body>
      <noscript>
        <p>Ogłoszenie zostało dodane pomyślnie.</p>
        <p><a href="index.php">Kliknij tutaj, aby wrócić na stronę główną</a></p>
      </noscript>
    </body>
    </html>
    <?php
    exit();
} else {
    // 11) Jeżeli coś poszło nie tak – wyświetlemy błąd
    echo "<div style='color:red; font-weight:bold;'>";
    echo "Błąd podczas dodawania ogłoszenia: " . htmlspecialchars($stmt->error);
    echo "</div>";
}

$stmt->close();
$conn->close();
?>
