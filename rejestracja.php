<?php
session_start(); // ZAWSZE jako pierwsza linia

if (!isset($_SESSION['username'])) {
    header("Location: logowanie.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sklep Zoologiczny</title>
  <link rel="stylesheet" href="szata.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #fff5e1;
      font-family: 'Inter', sans-serif;
      margin: 0;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #ffd983;
      padding: 20px 40px;
    }

    .logo {
      height: 60px;
    }

    .cart {
      font-size: 18px;
      position: relative;
      cursor: pointer;
    }

    .cart-count {
      position: absolute;
      top: -10px;
      right: -10px;
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 4px 8px;
      font-size: 12px;
    }

    .container {
      display: flex;
      padding: 30px;
      gap: 30px;
    }

    .sidebar {
      width: 220px;
      background-color: #fff;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .sidebar h3 {
      margin-bottom: 10px;
    }

    .sidebar ul {
      list-style: none;
      padding: 0;
    }

    .sidebar li {
      margin: 8px 0;
      cursor: pointer;
    }

    .content {
      flex: 1;
    }

    .filters {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 15px;
    }

    .filters input[type="text"],
    .filters select {
      padding: 10px;
      border-radius: 10px;
      border: 1px solid #ccc;
      min-width: 180px;
    }

    .products {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }

    .product {
      background-color: #fff;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      text-align: center;
    }

    .product img {
      width: 100%;
      height: 160px;
      object-fit: cover;
      border-radius: 10px;
    }

    .product h4 {
      margin: 10px 0 5px;
    }

    .product p {
      margin: 0;
      font-weight: bold;

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
    }
  </style>
</head>
<body>

<header>


<div class="top-bar">
    <a href="index.php">🏠 Strona główna</a>
</div>
  <img src="logo.png" alt="Logo" class="logo">
  <div class="cart">
    🛒 Koszyk <span class="cart-count">0</span>
  </div>
</header>

<div class="container">
  <aside class="sidebar">
    <h3>Kategorie</h3>
    <ul>
      <li>Karma</li>
      <li>Zabawki</li>
      <li>Akcesoria</li>
      <li>Transportery</li>
      <li>Higiena</li>
    </ul>
  </aside>

  <main class="content">
    <div class="filters">
      <input type="text" placeholder="Szukaj produktu..." />
      <select>
        <option>Sortuj wg: Domyślnie</option>
        <option>Cena rosnąco</option>
        <option>Cena malejąco</option>
        <option>Nazwa A-Z</option>
        <option>Nazwa Z-A</option>
      </select>
    </div>

    <section class="products">
      <div class="product">
        <img src="produkty/kot1.jpg" alt="Produkt 1" />
        <h4>Zabawka dla kota</h4>
        <p>29,99 zł</p>
      </div>
      <div class="product">
        <img src="produkty/karma-pies.jpg" alt="Produkt 2" />
        <h4>Karma dla psa</h4>
        <p>49,99 zł</p>
      </div>
      <!-- Możesz dodać więcej produktów tutaj -->
    </section>
  </main>
</div>

</body>
</html>
