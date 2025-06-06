<?php
// włączamy raportowanie błędów, startujemy sesję
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// 1) Połączenie z bazą
$host     = "localhost";
$db_user  = "ytsilpwxpv_ziomek";
$db_pass  = "123Biedr@456";
$db_name  = "ytsilpwxpv_zwado";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// 2) Pobranie wszystkich ogłoszeń nieodnalezionych
$sql = "SELECT * FROM zaginiecia WHERE odnaleziony = 0 ORDER BY data_zaginiecia DESC";
$result = $conn->query($sql);
if ($result === false) {
    die("Błąd zapytania: " . $conn->error);
}
$zaginiecia = $result->fetch_all(MYSQLI_ASSOC);

// 3) Funkcja pomocnicza: pobranie komentarzy dla konkretnego ogłoszenia
/**
 * Każdy komentarz ma w bazie zaginiecie_id, czyli ID ogłoszenia (zaginiecia.id).
 * Dzięki temu komentarze są przypisane do konkretnego ogłoszenia.
 * Poniższa funkcja pobiera komentarze powiązane z danym ogłoszeniem.
 */
function getKomentarze($conn, $zaginiecie_id) {
    $stmt = $conn->prepare("SELECT * FROM komentarze WHERE zaginiecie_id = ? ORDER BY data_dodania DESC");
    $stmt->bind_param("i", $zaginiecie_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $komentarze = $res->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $komentarze;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Zaginione zwierzaki</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="szata.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
  <style>
    .zaginiecie-box {
      display: flex;
      background: #f9f9f9;
      border-radius: 12px;
      margin: 20px auto;
      padding: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      max-width: 1000px;
    }
    .zaginiecie-box img {
      width: 200px;
      border-radius: 10px;
      margin-right: 20px;
    }
    .zaginiecie-info { flex: 1; position: relative; }
    .mapka {
      width: 300px;
      height: 200px;
      border-radius: 10px;
    }
    .komentarze-sekcja {
      margin-top: 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      background: #fafafa;
      display: none; /* domyślnie ukryta */
      padding: 10px;
    }
    .komentarz-item {
      border-bottom: 1px solid #e0e0e0;
      padding: 8px 0;
    }
    .komentarz-item:last-child {
      border-bottom: none;
    }
    .komentarz-item strong {
      display: block;
      margin-bottom: 4px;
    }
    .btn-dodaj {
      margin-top: 8px;
      background-color: #2c98f0;
      color: white;
      padding: 6px 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .btn-dodaj:hover {
      background-color: #217bd0;
    }
    .toggle-btn {
      background: #2c98f0;
      color: #fff;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
      font-size: 0.9rem;
    }
    .toggle-btn:hover {
      background: #217bd0;
    }
    textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      resize: vertical;
    }
    .comment-error {
      color: red;
      font-weight: bold;
      text-align: center;
      margin-bottom: 10px;
    }

.top-bar {
            text-align: right;
            margin-bottom: 20px;
        }
        .top-bar a {
            text-decoration: none;
            color: #fff;
            background-color: #0077cc;
            padding: 8px 16px;
            border-radius: 5px;
        }
  </style>
</head>
<body>

<header>
  <h1>
    <img src="logo.png" alt="logo" class="logo">
    <span>Bądź zwierzęcym <i>bohaterem</i></span>
  </h1>
  <div style="position: absolute; top: 20px; right: 20px;">
    <?php if (isset($_SESSION['username'])): ?>
      <div class="dropdown">
        <span>Witaj, <?= htmlspecialchars($_SESSION['username']) ?> ▼</span>
        <div class="dropdown-menu">
          <a href="mojeogloszenia.php">Moje ogłoszenia</a>
          <a href="profil.php">Profil</a>
          <a href="wylogowanie.php">Wyloguj</a>
        </div>
      </div>
<div class="top-bar">
    <a href="index.php">🏠 Strona główna</a>
</div>
    <?php else: ?>
      <a href="rejestr.html">Rejestracja</a>
      <a href="logowanie.html">Logowanie</a>
    <?php endif; ?>
  </div>
</header>

<main>
  <section class="gallery">
    <?php if (isset($_SESSION['username'])): ?>
      <a href="dodaj_zaginiecie.php">
        <button style="background-color:#ff6b6b; color:white; margin-bottom:20px;">➕ Dodaj zgłoszenie zaginięcia</button>
      </a>
    <?php endif; ?>

    <?php if (isset($_SESSION['comment_error'])): ?>
      <div class="comment-error"><?= htmlspecialchars($_SESSION['comment_error']) ?></div>
      <?php unset($_SESSION['comment_error']); ?>
    <?php endif; ?>

    <?php if (empty($zaginiecia)): ?>
      <p>Brak zgłoszeń zaginięcia.</p>
    <?php else: ?>
      <?php foreach ($zaginiecia as $item): ?>
        <div class="zaginiecie-box">
          <img src="<?= htmlspecialchars($item['zdjecie']) ?>" alt="Zdjęcie zwierzaka">
          <div class="zaginiecie-info">
            <h3><?= htmlspecialchars($item['nazwa']) ?></h3>
            <p><strong>Data zaginięcia:</strong> <?= htmlspecialchars($item['data_zaginiecia']) ?></p>
            <p><?= nl2br(htmlspecialchars($item['opis'])) ?></p>
            <p><strong>Kontakt:</strong> <?= htmlspecialchars($item['kontakt']) ?></p>

            <button class="toggle-btn" data-target="komentarze-<?= $item['id'] ?>">
              Pokaż komentarze
            </button>

            <div class="komentarze-sekcja" id="komentarze-<?= $item['id'] ?>">
              <h4>Komentarze:</h4>
              <?php
                $komentarze = getKomentarze($conn, $item['id']);
                if (empty($komentarze)):
              ?>
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

              <?php if (isset($_SESSION['username'])): ?>
                <form action="dodaj_komentarz.php" method="POST" style="margin-top:10px;">
                  <textarea name="tresc" rows="2" placeholder="Dodaj komentarz..." required></textarea>
                  <input type="hidden" name="zaginiecie_id" value="<?= $item['id'] ?>">
                  <button type="submit" class="btn-dodaj">Dodaj komentarz</button>
                </form>
              <?php else: ?>
                <p style="font-style:italic; color:#555;">Zaloguj się, aby dodać komentarz.</p>
              <?php endif; ?>
            </div>
          </div>

          <div class="mapka" id="map-<?= $item['id'] ?>"></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</main>

<footer class="site-footer">
  <div class="footer-content">
    <p>Kontakt: kontakt@twojastrona.pl | Tel: +48 123 456 789</p>
    <p>© 2025 TwojaStrona.pl</p>
  </div>
</footer>

<script>
  <?php foreach ($zaginiecia as $item): ?>
    const map<?= $item['id'] ?> = L.map('map-<?= $item['id'] ?>').setView([<?= $item['latitude'] ?>, <?= $item['longitude'] ?>], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map<?= $item['id'] ?>);
    L.circle([<?= $item['latitude'] ?>, <?= $item['longitude'] ?>], {
      color: 'red',
      fillColor: '#f03',
      fillOpacity: 0.3,
      radius: <?= $item['radius'] ?>
    }).addTo(map<?= $item['id'] ?>).bindPopup("Obszar zaginięcia");
  <?php endforeach; ?>

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
