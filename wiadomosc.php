<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: logowanie.html");
    exit;
}

$host = "localhost";
$db_user = "ytsilpwxpv_ziomek";
$db_pass = "123Biedr@456";
$db_name = "ytsilpwxpv_zwado";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych.");
}

$user_id = $_SESSION['user_id'];

// Obsługa wysyłania odpowiedzi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['odpowiedz_tresc'], $_POST['nadawca_id'], $_POST['ogloszenie_id'])) {
    $tresc = $_POST['odpowiedz_tresc'];
    $nadawca_id = $user_id; // Ty jesteś nadawcą teraz
    $odbiorca_id = (int)$_POST['nadawca_id']; // Kto wysłał do Ciebie wiadomość - teraz odbiorca Twojej odpowiedzi
    $ogloszenie_id = (int)$_POST['ogloszenie_id'];

    $stmt = $conn->prepare("INSERT INTO wiadomosci (tresc, nadawca_id, odbiorca_id, ogloszenie_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $tresc, $nadawca_id, $odbiorca_id, $ogloszenie_id);
    $stmt->execute();
    $stmt->close();

    header("Location: wiadomosci.php?sent=1");
    exit;
}

// Pobierz wiadomości do Ciebie
$stmt = $conn->prepare("
    SELECT w.id, w.tresc, w.nadawca_id, u.username AS nadawca_login, o.name AS ogloszenie_nazwa, w.ogloszenie_id
    FROM wiadomosci w
    JOIN users u ON w.nadawca_id = u.id
    LEFT JOIN ogloszenia o ON w.ogloszenie_id = o.id
    WHERE w.odbiorca_id = ?
    ORDER BY w.id DESC
    LIMIT 10
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$wiadomosci = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="utf-8">
<title>Wiadomości</title>
<style>
  body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
  .wiadomosc { background: white; padding: 15px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
  .odpowiedz-form { margin-top: 10px; }
  textarea { width: 100%; height: 80px; }
  button { margin-top: 5px; padding: 8px 12px; cursor: pointer; }
  .sent-msg { color: green; }
</style>
</head>
<body>

<h1>Twoje wiadomości</h1>
<?php if (isset($_GET['sent'])): ?>
    <p class="sent-msg">Wiadomość została wysłana.</p>
<?php endif; ?>

<?php if ($wiadomosci->num_rows > 0): ?>
    <?php while ($msg = $wiadomosci->fetch_assoc()): ?>
        <div class="wiadomosc">
            <p><strong>Od:</strong> <?= htmlspecialchars($msg['nadawca_login']) ?></p>
            <p><strong>Ogłoszenie:</strong>
                <a href="widok.php?id=<?= $msg['ogloszenie_id'] ?>">
                    <?= htmlspecialchars($msg['ogloszenie_nazwa'] ?? 'Nieznane') ?>
                </a>
            </p>
            <p><strong>Treść:</strong><br><?= nl2br(htmlspecialchars($msg['tresc'])) ?></p>

            <form class="odpowiedz-form" method="POST" action="wiadomosci.php">
                <input type="hidden" name="nadawca_id" value="<?= $msg['nadawca_id'] ?>">
                <input type="hidden" name="ogloszenie_id" value="<?= $msg['ogloszenie_id'] ?>">
                <textarea name="odpowiedz_tresc" placeholder="Napisz odpowiedź..." required></textarea>
                <button type="submit">Wyślij odpowiedź</button>
            </form>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Brak wiadomości.</p>
<?php endif; ?>

</body>
</html>
