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

/*
 * Aby pobrać z użytkownika również telefon i adres (miasto),
 * łączymy tabelę `ogloszenia` z tabelą `users` po `user_id`.
 */
$sql = "
    SELECT 
        o.*,
        u.phone,
        u.address
    FROM ogloszenia AS o
    LEFT JOIN users AS u ON o.user_id = u.id
    ORDER BY o.created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$ads    = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wszystkie ogłoszenia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 30px;
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
        .contact-info {
            margin-top: 8px;
            font-size: 0.9rem;
        }
        .contact-info strong {
            color: #333;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <a href="index.php">🏠 Strona główna</a>
</div>

<h1>Ogłoszenia</h1>

<div class="ads-container">
    <?php if (count($ads) === 0): ?>
        <p style="text-align: center; width: 100%;">Brak ogłoszeń do wyświetlenia.</p>
    <?php else: ?>
        <?php foreach ($ads as $ad): ?>
            <div class="ad-card">
                <?php if (!empty($ad['image_url'])): ?>
                    <img src="<?= htmlspecialchars($ad['image_url']) ?>" alt="Zdjęcie zwierzaka">
                <?php endif; ?>

                <h3><?= htmlspecialchars($ad['name']) ?></h3>
                <p><strong>Gatunek:</strong> <?= htmlspecialchars($ad['species']) ?></p>
                <p><strong>Rasa:</strong> <?= htmlspecialchars($ad['breed']) ?></p>
                <p><strong>Wiek:</strong> <?= htmlspecialchars($ad['age']) ?></p>
                <p><strong>Cena:</strong> 
                    <?= $ad['price'] !== null 
                        ? number_format($ad['price'], 2, ',', ' ') . ' zł' 
                        : 'Za darmo' 
                    ?>
                </p>
                <!-- Wyświetlamy kontakt: telefon i adres -->
                <div class="contact-info">
                    <?php if (!empty($ad['phone'])): ?>
                        <p><strong>Telefon:</strong> <?= htmlspecialchars($ad['phone']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($ad['address'])): ?>
                        <p><strong>Miasto:</strong> <?= htmlspecialchars($ad['address']) ?></p>
                    <?php endif; ?>
                </div>

                <a href="ogłoszenia.php?id=<?= $ad['id'] ?>" 
                   style="display: inline-block; margin-top: 10px; 
                          background-color: #10b981; color: white; 
                          padding: 6px 10px; border-radius: 5px; 
                          text-decoration: none;">
                    🔎 Zobacz więcej
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
