<?php
// komentarz.php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 0) Upewnij się, że przekazano ID ogłoszenia w parametrze GET
if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
    die("Nieprawidłowe ID ogłoszenia.");
}
$zaginiecie_id = intval($_GET['id']);

// 1) Połączenie z bazą danych
$host     = "localhost";
$db_user  = "ytsilpwxpv_ziomek";
$db_pass  = "123Biedr@456";
$db_name  = "ytsilpwxpv_zwado";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// 2) Pobierz szczegóły wybranego ogłoszenia
$stmtZ = $conn->prepare("SELECT * FROM zaginiecia WHERE id = ? AND odnaleziony = 0");
$stmtZ->bind_param("i", $zaginiecie_id);
$stmtZ->execute();
$resZ = $stmtZ->get_result();
if ($resZ->num_rows === 0) {
    die("Nie znaleziono takiego ogłoszenia lub zostało oznaczone jako odnalezione.");
}
$zaginiecie = $resZ->fetch_assoc();
$stmtZ->close();

// 3) Funkcja pomocnicza: pobranie komentarzy dla tego ogłoszenia
function getKomentarze($conn, $zaginiecie_id) {
    $stmt = $conn->prepare("SELECT * FROM komentarze WHERE zaginiecie_id = ? ORDER BY data_dodania DESC");
    $stmt->bind_param("i", $zaginiecie_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $komentarze = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $komentarze;
}

// 4) Pobierz tablicę komentarzy
$komentarze = getKomentarze($conn, $zaginiecie_id);

// 5) Obsłuż ewentualny komunikat błędu przy dodawaniu komentarza
$commentError = '';
if (isset($_SESSION['comment_error'])) {
    $commentError = $_SESSION['comment_error'];
    unset($_SESSION['comment_error']);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Szczegóły ogłoszenia o zaginięciu</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="szata.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f7f7f7;
      margin: 0;
      padding: 0;
    }
    header, footer {
      background: #2c98f0;
      color: white;
      padding: 10px;
      text-align: center;
    }
    .container {
      max-width: 800px;
      margin: 20px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .zaginiecie-header {
      text-align: center;
      margin-bottom: 20px;
    }
    .zaginiecie-header h2 {
      margin: 0;
    }
    .zaginiecie-details img {
      width: 100%;
      max-height: 400px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    .zaginiecie-details p {
      margin: 8px 0;
    }
    .mapka {
      width: 100%;
      height: 300px;
      border-radius: 8px;
      margin-top: 15px;
    }
    .toggle-btn {
      background: #2c98f0;
      color: #fff;
      border: none;
      padding: 8px 14px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
      margin-top: 20px;
    }
    .toggle-btn:hover {
      background: #217bd0;
    }
    .komentarze-sekcja {
      margin-top: 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background: #fafafa;
      display: none; /* domyślnie ukryte */
      padding: 15px;
    }
    .komentarz-item {
      border-bottom: 1px solid #e0e0e0;
      padding: 10px 0;
    }
    .komentarz-item:last-child {
      border-bottom: none;
    }
    .komentarz-item strong {
      display: block;
      margin-bottom: 4px;
    }
    .btn-dodaj {
      margin-top: 10px;
      background-color: #2c98f0;
      color: white;
      padding: 8px 14px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1rem;
    }
    .btn-dodaj:hover {
      background-color: #217bd0;
    }
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      resize: vertical;
      font-size: 1rem;
      margin-top: 8px;
    }
    .comment-error {
      color: red;
      font-weight: bold;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
<header>
  <h1>Ogłoszenie o zaginięciu</h1>
</header>

<div class="container">
  <!-- Szczegóły ogłoszenia -->
  <div class="zaginiecie-header">
    <h2><?= htmlspecialchars($zaginiecie['nazwa']) ?></h2>
  </div>
  <div class="zaginiecie-details">
    <img src="<?= htmlspecialchars($zaginiecie['zdjecie']) ?>" alt="Zdjęcie zwierzaka">
    <p><strong>Data zaginięcia:</strong> <?= htmlspecialchars($zaginiecie['data_zaginiecia']) ?></p>
    <p><?= nl2br(htmlspecialchars($zaginiecie['opis'])) ?></p>
    <p><strong>Kontakt:</strong> <?= htmlspecialchars($zaginiecie['kontakt']) ?></p>
    <div id="map" class="mapka"></div>
  </div>

  <!-- Przycisk Pokaż/U­kryj komentarze -->
  <button class="toggle-btn" data-target="komentarze-<?= $zaginiecie_id ?>">
    Pokaż komentarze
  </button>

  <!-- Sekcja komentarzy (ukryta domyślnie) -->
  <div class="komentarze-sekcja" id="komentarze-<?= $zaginiecie_id ?>">
    <h4>Komentarze:</h4>

    <?php if (empty($komentarze)): ?>
      <p style="font-style: italic; color: #555;">Brak komentarzy.</p>
    <?php else: ?>
      <?php foreach ($komentarze as $kom): ?>
        <div class="komentarz-item">
          <strong><?= htmlspecialchars($kom['username']) ?>:</strong>
          <p><?= nl2br(htmlspecialchars($kom['tresc'])) ?></p>
          <small style="color:#777;"><?= htmlspecialchars($kom['data_dodania']) ?></small>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Komunikat o błędzie dodawania komentarza -->
    <?php if ($commentError !== ''): ?>
      <div class="comment-error"><?= htmlspecialchars($commentError) ?></div>
    <?php endif; ?>

    <!-- Formularz dodania komentarza (tylko dla zalogowanych) -->
    <?php if (isset($_SESSION['username'])): ?>
      <form action="dodaj_komentarz.php" method="POST" style="margin-top:15px;">
        <textarea name="tresc" rows="3" placeholder="Dodaj komentarz..." required></textarea>
        <input type="hidden" name="zaginiecie_id" value="<?= $zaginiecie_id ?>">
        <button type="submit" class="btn-dodaj">Dodaj komentarz</button>
      </form>
    <?php else: ?>
      <p style="font-style:italic; color:#555; margin-top:15px;">
        Zaloguj się, aby dodać komentarz.
      </p>
    <?php endif; ?>
  </div>
</div>

<footer>
  <p>© 2025 TwojaStrona.pl | <a href="index.php" style="color: white; text-decoration: underline;">Powrót na stronę główną</a></p>
</footer>

<script>
  // 1) Inicjalizacja mapy Leaflet
  const map = L.map('map').setView([
    <?= $zaginiecie['latitude'] ?>, 
    <?= $zaginiecie['longitude'] ?>
  ], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18
  }).addTo(map);
  L.circle([
    <?= $zaginiecie['latitude'] ?>, 
    <?= $zaginiecie['longitude'] ?>
  ], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.3,
    radius: <?= $zaginiecie['radius'] ?>
  }).addTo(map).bindPopup("Obszar zaginięcia");

  // 2) Obsługa przycisku Pokaż/U­kryj komentarze
  document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      const sekcja = document.getElementById(targetId);
      if (!sekcja) return;

      if (sekcja.style.display === 'block') {
        sekcja.style.display = 'none';
        this.textContent = "Pokaż komentarze";
      } else {
        sekcja.style.display = 'block';
        this.textContent = "Ukryj komentarze";
      }
    });
  });
</script>
</body>
</html>
