<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: logowanie.html");
    exit();
}

// Produkty (powinny być pobierane z bazy, tu przykładowa tablica)
$produkty = [
    'kot1' => ['nazwa' => 'Zabawka dla kota', 'cena' => 29.99, 'obrazek' => 'produkty/kot1.jpg'],
    'pies1' => ['nazwa' => 'Karma dla psa', 'cena' => 49.99, 'obrazek' => 'produkty/karma-pies.jpg'],
    'kot2' => ['nazwa' => 'Transporter kota', 'cena' => 99.99, 'obrazek' => 'produkty/kot2.jpg'],
    'higiena1' => ['nazwa' => 'Żwirek dla kota', 'cena' => 24.50, 'obrazek' => 'produkty/higiena1.jpg'],
];

$cart = $_SESSION['cart'] ?? [];
$koszyk_zgrupowany = [];

// Grupowanie produktów (ilość sztuk)
foreach ($cart as $id) {
    if (!isset($koszyk_zgrupowany[$id])) {
        $koszyk_zgrupowany[$id] = 1;
    } else {
        $koszyk_zgrupowany[$id]++;
    }
}

$wartosc_laczna = 0.0;
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Twój koszyk</title>
    <link rel="stylesheet" href="szata.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fff5e1;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #ffd983;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            height: 60px;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h1 {
            margin-top: 0;
        }

        .product {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .product img {
            height: 80px;
            width: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 20px;
        }

        .product-info {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .product-details {
            flex-grow: 1;
        }

        .remove-button {
            background-color: red;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .total {
            font-weight: bold;
            font-size: 20px;
            text-align: right;
            margin-top: 20px;
        }

        .order-button {
            display: block;
            background-color: #0077cc;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            text-align: center;
            margin: 30px auto 0;
            text-decoration: none;
            width: fit-content;
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
    <div class="top-bar">
        <a href="sklep.php">← Wróć do sklepu</a>
    </div>
    <img src="logo.png" alt="Logo" class="logo">
</header>

<div class="container">
    <h1>🛒 Twój koszyk</h1>

    <?php if (empty($koszyk_zgrupowany)): ?>
        <p>Twój koszyk jest pusty.</p>
    <?php else: ?>
        <?php foreach ($koszyk_zgrupowany as $id => $ilosc): 
            if (!isset($produkty[$id])) continue;
            $produkt = $produkty[$id];
            $suma = $produkt['cena'] * $ilosc;
            $wartosc_laczna += $suma;
        ?>
            <div class="product">
                <div class="product-info">
                    <img src="<?= $produkt['obrazek'] ?>" alt="<?= $produkt['nazwa'] ?>">
                    <div class="product-details">
                        <strong><?= $produkt['nazwa'] ?></strong><br>
                        Cena: <?= number_format($produkt['cena'], 2) ?> zł<br>
                        Ilość: <?= $ilosc ?><br>
                        Razem: <?= number_format($suma, 2) ?> zł
                    </div>
                </div>
                <form method="post" action="usun_z_koszyka.php">
                    <input type="hidden" name="produkt_id" value="<?= $id ?>">
                    <button type="submit" class="remove-button">Usuń</button>
                </form>
            </div>
        <?php endforeach; ?>

        <div class="total">
            Łączna wartość: <?= number_format($wartosc_laczna, 2) ?> zł
        </div>

        <a href="zamowienie.php" class="order-button">Złóż zamówienie</a>
    <?php endif; ?>
</div>

</body>
</html>
