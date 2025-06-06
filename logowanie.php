<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: logowanie.html");
    exit();
}

$username = $_SESSION['username'];

// Wyświetlenie komunikatu po dodaniu ogłoszenia
$successMessage = '';
if (isset($_GET['status']) && $_GET['status'] === 'sukces') {
    $successMessage = '<div style="background-color: #d1fae5; color: #065f46; padding: 12px; border-radius: 8px; margin: 20px auto; max-width: 600px; text-align: center;">
        ✅ Ogłoszenie zostało pomyślnie dodane!
    </div>';
}

// Połączenie z bazą
$host = "localhost";
$db_user = "ytsilpwxpv_ziomek";
$db_pass = "123Biedr@456";
$db_name = "ytsilpwxpv_zwado";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych.");
}

// Pobierz user_id
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'] ?? 0;

// Pobierz ogłoszenia użytkownika
$ads = [];
if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM ogloszenia WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $ads = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje ogłoszenia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 30px;
            margin: 0;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .ads-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .ad-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            width: 300px;
            padding: 15px;
            text-align: left;
        }
        .ad-card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .ad-card h3 {
            margin: 10px 0 5px;
            color: #111;
        }
        .ad-card p {
            margin: 4px 0;
            color: #555;
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

<div class="top-bar">
    <span>Witaj, <?= htmlspecialchars($username) ?>!</span>
    <a href="index.php" style="margin-left: 10px;">🏠 Strona główna</a>
    <a href="wylogowanie.php" style="margin-left: 10px;">🚪 Wyloguj się</a>
</div>

<?= $successMessage ?>

<h1>Twoje ogłoszenia</h1>

<div class="ads-container">
    <?php if (count($ads) === 0): ?>
        <p style="text-align: center; width: 100%;">Nie masz jeszcze żadnych ogłoszeń.</p>
    <?php else: ?>
        <?php foreach ($ads as $ad): ?>
          <div class="ad-card">
    <img src="<?= htmlspecialchars($ad['image_url']) ?>" alt="Zdjęcie zwierzaka">
    <h3><?= htmlspecialchars($ad['name']) ?></h3>
    <p><strong>Gatunek:</strong> <?= htmlspecialchars($ad['species']) ?></p>
    <p><strong>Rasa:</strong> <?= htmlspecialchars($ad['breed']) ?></p>
    <p><strong>Wiek:</strong> <?= htmlspecialchars($ad['age']) ?></p>
    <p><strong>Opis:</strong> <?= nl2br(htmlspecialchars($ad['description'])) ?></p>
    <p><strong>Cena:</strong> <?= $ad['price'] !== null ? number_format($ad['price'], 2) . ' zł' : 'Za darmo' ?></p>
    <p><em>Dodano: <?= htmlspecialchars($ad['created_at']) ?></em></p>

    <!-- PRZYCISKI -->
    <div style="margin-top: 10px; display: flex; gap: 6px; flex-wrap: wrap;">
        <a href="edycja.php?id=<?= $ad['id'] ?>" style="background-color: #fbbf24; color: #000; padding: 6px 10px; border-radius: 5px; text-decoration: none;">✏️ Edytuj</a>
        <a href="usuń.php?id=<?= $ad['id'] ?>" onclick="return confirm('Czy na pewno chcesz usunąć to ogłoszenie?');" style="background-color: #ef4444; color: white; padding: 6px 10px; border-radius: 5px; text-decoration: none;">❌ Usuń</a>
        <a href="ogloszenie.php?id=<?= $ad['id'] ?>" target="_blank" style="background-color: #10b981; color: white; padding: 6px 10px; border-radius: 5px; text-decoration: none;">🔗 Udostępnij</a>
    </div>
</div>

        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
