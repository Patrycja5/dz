<?php
session_start();

// Sprawdzanie, czy użytkownik jest zalogowany
if (!isset($_SESSION['username'])) {
    echo "Musisz być zalogowany, aby wyświetlić tę stronę.";
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

// Sprawdzenie ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Nieprawidłowe ID ogłoszenia.";
    exit;
}

$id = (int)$_GET['id'];
$typ = 'adoptowane'; // 👈 Ustawiamy typ dla tej strony

// Pobranie ogłoszenia
$stmt = $conn->prepare("SELECT * FROM ogloszenia WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Ogłoszenie nie zostało znalezione.";
    exit;
}

$ad = $result->fetch_assoc();

// Obsługa komentarza
if (isset($_POST['submit_comment'])) {
    $username = $_SESSION['username'];
    $tresc = trim($_POST['tresc']);

    if (!empty($tresc)) {
        $stmt_add = $conn->prepare("INSERT INTO komentarze (zaginiecie_id, username, tresc, data_dodania, typ) VALUES (?, ?, ?, NOW(), ?)");
        $stmt_add->bind_param("isss", $id, $username, $tresc, $typ);
        $stmt_add->execute();
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}

// Pobranie komentarzy (z uwzględnieniem typu!)
$komentarze_stmt = $conn->prepare("SELECT * FROM komentarze WHERE zaginiecie_id = ? AND typ = ? ORDER BY data_dodania DESC");
$komentarze_stmt->bind_param("is", $id, $typ);
$komentarze_stmt->execute();
$komentarze_result = $komentarze_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($ad['name']) ?> – Ogłoszenie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .ad-img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        h1 {
            margin-top: 0;
        }

        .info {
            margin-bottom: 20px;
        }

        .info strong {
            color: #333;
        }

        .description {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
            font-size: 1rem;
            color: #444;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            text-decoration: none;
            color: white;
            background: #007bff;
            padding: 10px 15px;
            border-radius: 6px;
        }

        /* Komentarze */
        form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        form button {
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        form button:hover {
            background: #218838;
        }

        .comment {
            margin-bottom: 15px;
            padding: 10px;
            background: #f3f3f3;
            border-radius: 6px;
        }

        .comment small {
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
    <a href="index.php">🏠 Strona główna</a>
</div>

<div class="container">
    <img src="<?= htmlspecialchars($ad['image_url']) ?>" alt="Zdjęcie ogłoszenia" class="ad-img">

    <h1><?= htmlspecialchars($ad['name']) ?></h1>

    <div class="info">
        <p><strong>Gatunek:</strong> <?= htmlspecialchars($ad['species']) ?></p>
        <p><strong>Rasa:</strong> <?= htmlspecialchars($ad['breed']) ?></p>
        <p><strong>Wiek:</strong> <?= htmlspecialchars($ad['age']) ?></p>
        <p><strong>Cena:</strong> <?= $ad['price'] !== null ? number_format($ad['price'], 2) . ' zł' : 'Za darmo' ?></p>
        <p><strong>Kontakt:</strong> <?= htmlspecialchars($ad['contact']) ?></p>
    </div>

    <div class="description">
        <strong>Opis:</strong><br>
        <?= nl2br(htmlspecialchars($ad['description'])) ?>
    </div>

    <a class="back-link" href="widok.php">← Wróć do ogłoszeń</a>

    <hr><br>
    <h2>Komentarze</h2>

    <form method="post" action="">
        <p><strong>Komentujesz jako:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>

        <label for="tresc">Treść komentarza:</label><br>
        <textarea id="tresc" name="tresc" rows="4" required></textarea><br><br>

        <button type="submit" name="submit_comment">Dodaj komentarz</button>
    </form>

    <br>

    <!-- Wyświetlanie komentarzy -->
    <?php if ($komentarze_result->num_rows > 0): ?>
        <?php while ($kom = $komentarze_result->fetch_assoc()): ?>
            <div class="comment">
                <strong><?= htmlspecialchars($kom['username']) ?></strong> napisał(a):<br>
                <p><?= nl2br(htmlspecialchars($kom['tresc'])) ?></p>
                <small><?= htmlspecialchars($kom['data_dodania']) ?></small>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Brak komentarzy — bądź pierwszą osobą, która skomentuje!</p>
    <?php endif; ?>
</div>

</body>
</html>
