<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: logowanie.html");
    exit();
}

// Produkty (te same co wcześniej)
$produkty = [
    'kot1' => ['nazwa' => 'Zabawka dla kota', 'cena' => 29.99],
    'pies1' => ['nazwa' => 'Karma dla psa', 'cena' => 49.99],
    'kot2' => ['nazwa' => 'Transporter kota', 'cena' => 99.99],
    'higiena1' => ['nazwa' => 'Żwirek dla kota', 'cena' => 24.50],
];

$cart = $_SESSION['cart'] ?? [];

$koszyk_zgrupowany = [];
foreach ($cart as $id) {
    if (!isset($koszyk_zgrupowany[$id])) {
        $koszyk_zgrupowany[$id] = 1;
    } else {
        $koszyk_zgrupowany[$id]++;
    }
}

$wartosc_laczna = 0.0;

// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imie = $_POST['imie'] ?? '';
    $adres = $_POST['adres'] ?? '';
    $email = $_POST['email'] ?? '';

    // Można dodać walidację

    // Tu można np. zapisać zamówienie do bazy danych lub wysłać maila

    // Wyczyść koszyk po złożeniu zamówienia
    unset($_SESSION['cart']);

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>Zamówienie złożone</title>
        <link rel="stylesheet" href="szata.css">
        <style>
            body { font-family: 'Inter', sans-serif; background-color: #fff5e1; padding: 40px; text-align: center; }
            .potwierdzenie {
                background-color: #fff;
                padding: 30px;
                border-radius: 12px;
                max-width: 600px;
                margin: auto;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }
        </style>
    </head>
    <body>
        <div class="potwierdzenie">
            <h1>Dziękujemy za zamówienie! 🎉</h1>
            <p>Twoje zamówienie zostało złożone pomyślnie.</p>
            <p>Wysłane na adres: <strong>{$adres}</strong></p>
            <p>Potwierdzenie wysłane na e-mail: <strong>{$email}</strong></p>
            <a href="sklep.php">Powrót do sklepu</a>
        </div>
    </body>
    </html>
    HTML;
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zamówienie</title>
    <link rel="stylesheet" href="szata.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff5e1;
            margin: 0;
        }

        header {
            background-color: #ffd983;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        form input, form textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        form button {
            background-color: #0077cc;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        .podsumowanie {
            margin-bottom: 30px;
        }

        .top-bar a {
            text-decoration: none;
            color: #fff;
            background-color: #0077cc;
            padding: 8px 16px;
            border-radius: 5px;
        }

        .logo {
            height: 60px;
        }
    </style>
</head>
<body>

<header>
    <div class="top-bar">
        <a href="koszyk.php">← Wróć do koszyka</a>
    </div>
    <img src="logo.png" alt="Logo" class="logo">
</header>

<div class="container">
    <h1>📝 Złóż zamówienie</h1>

    <?php if (empty($koszyk_zgrupowany)): ?>
        <p>Twój koszyk jest pusty.</p>
    <?php else: ?>
        <div class="podsumowanie">
            <h3>Podsumowanie zamówienia:</h3>
            <ul>
                <?php foreach ($koszyk_zgrupowany as $id => $ilosc):
                    if (!isset($produkty[$id])) continue;
                    $p = $produkty[$id];
                    $suma = $p['cena'] * $ilosc;
                    $wartosc_laczna += $suma;
                ?>
                    <li><?= $p['nazwa'] ?> × <?= $ilosc ?> – <?= number_format($suma, 2) ?> zł</li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Łącznie: <?= number_format($wartosc_laczna, 2) ?> zł</strong></p>
        </div>

        <form method="post">
            <label>Imię i nazwisko:</label>
            <input type="text" name="imie" required>

            <label>Adres dostawy:</label>
            <textarea name="adres" required></textarea>

            <label>Adres e-mail:</label>
            <input type="email" name="email" required>

            <button type="submit">Złóż zamówienie</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
