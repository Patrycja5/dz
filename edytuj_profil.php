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

$latest = [];
$result = $conn->query("SELECT id, name, image_url FROM ogloszenia ORDER BY created_at DESC LIMIT 5");
if ($result) {
    $latest = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tu będzie tytuł jak go wymyślimy</title>
  <link rel="stylesheet" href="szata.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <style>
    .dropdown {
      position: relative;
      display: inline-block;
      z-index: 200; /* wyższy niż reszta */
    }

    .dropdown-toggle {
      cursor: pointer;
      font-weight: bold;
      user-select: none; /* uniemożliwia zaznaczanie tekstu */
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      top: 110%; /* trochę niżej, żeby nie nachodziło */
      left: 0;
      background-color: white;
      border: 1px solid #ccc;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      z-index: 1000; /* bardzo wysoko, żeby być na wierzchu */
      padding: 5px 0;
      border-radius: 6px;
      min-width: 150px;
      white-space: nowrap;
    }

    .dropdown:hover .dropdown-menu {
      display: block;
    }

    .dropdown-menu a {
      display: block;
      padding: 8px 12px;
      text-decoration: none;
      color: black;
      transition: background-color 0.3s ease;
    }

    .dropdown-menu a:hover {
      background-color: #f0f0f0;
    }

    .submenu {
      position: relative;
      padding-left: 12px;
      font-weight: normal;
      cursor: pointer;
      user-select: none;
    }

    .submenu-menu {
      display: none;
      position: absolute;
      top: 0;
      right: 100%;
      background-color: white;
      border: 1px solid #ccc;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      z-index: 1100;
      border-radius: 6px;
      min-width: 150px;
      white-space: nowrap;
    }

    .submenu:hover .submenu-menu {
      display: block;
    }

    .submenu-menu a {
      padding: 8px 12px;
    }

    /* Opcjonalnie dla responsywności - można wyłączyć hover na mobile */
    @media (hover: none) {
      .dropdown:hover .dropdown-menu,
      .submenu:hover .submenu-menu {
        display: none;
      }
      /* Można dodać obsługę kliknięcia, jeśli potrzeba */
    }
  </style>
</head>
<body>
  <h1>
    <img src="logo.png" alt="logo strony" class="logo">
    <span class="tytul">Bądź zwierzęcym <i>bohaterem</i></span>
  </h1>


  <div style="position: absolute; top: 20px; right: 20px; z-index: 300;">
    <?php if (isset($_SESSION['username'])): ?>
      <div class="dropdown">
        <span class="dropdown-toggle">Witaj, <?= htmlspecialchars($_SESSION['username']) ?> ▼</span>
        <div class="dropdown-menu">
          <a href="mojeogloszenia.php">Moje ogłoszenia</a>
          <div class="submenu">
            <span>Profil ▶</span>
            <div class="submenu-menu">
              <a href="edytuj_profil.php">Edytuj profil</a>
              <a href="wiadomosci.php">Wiadomości</a>
            </div>
          </div>
          <a href="wylogowanie.php">Wyloguj</a>
        </div>
      </div>
    <?php else: ?>
      <a href="rejestr.html">Rejestracja</a>
      <a href="logowanie.html">Logowanie</a>
    <?php endif; ?>
  </div>
<a href="https://donate.stripe.com/test_5kQ7sL2zm4xq09C7Sec3m00" class="btn-donation" target="_blank">
  ❤️ Wesprzyj nas darowizną
</a>

  <nav>
    <a href="adopcja.php"><button class="add-report"><span class="plus">➕</span> Utwórz zgłoszenie</button></a>
    <a href="zaginięcia.php"><button class="urgent active">Najnowsze zgłoszenia</button></a>
    <a href="widok.php"><button>Adopcja</button></a>
    <a href="sklep.php"><button>Sklep zoologiczny</button></a>
  </nav>

  <main>
    <section class="gallery">
      <h2>Najnowsze ogłoszenia</h2>
      <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;">
        <?php foreach ($latest as $item): ?>
          <a href="widok.php?id=<?= $item['id'] ?>" style="text-decoration: none; color: black;">
            <div style="width: 200px; background: white; padding: 10px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); text-align: center;">
              <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Zdjęcie" style="width: 100%; border-radius: 8px;">
              <h4><?= htmlspecialchars($item['name']) ?></h4>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-content">
      <p>Kontakt: kontakt@twojastrona.pl | Tel: +48 123 456 789</p>
      <p>
        Śledź nas:
        <a href="#" target="_blank">Facebook</a> |
        <a href="#" target="_blank">Instagram</a> |
        <a href="#" target="_blank">Twitter</a>
      </p>
      <p>© 2025 TwojaStrona.pl - Wszelkie prawa zastrzeżone.</p>
      <p><a href="#">Regulamin</a> | <a href="#">Polityka prywatności</a></p>
    </div>
  </footer>
</body>
</html>
