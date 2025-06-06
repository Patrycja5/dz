<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rejestracja</title>
  <link rel="stylesheet" href="szata.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
  <style>
    .register-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin-top: 40px;
    }

    .register-box {
      background-color: #fff;
      padding: 30px 40px;
      border-radius: 20px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
      max-width: 450px;
      width: 90%;
    }

    .register-box h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .register-box label {
      display: block;
      margin-top: 15px;
      font-weight: 500;
    }

    .register-box input[type="text"],
    .register-box input[type="email"],
    .register-box input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 10px;
      border: 1px solid #ccc;
    }

    .register-box button {
      width: 100%;
      padding: 12px;
      background-color: #ffd983;
      border: none;
      border-radius: 10px;
      margin-top: 25px;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .register-box button:hover {
      background-color: #f4c146;
    }

    .extra-links {
      text-align: center;
      margin-top: 20px;
    }

    .extra-links a {
      text-decoration: none;
      color: #333;
      font-size: 14px;
    }

    .extra-links a:hover {
      text-decoration: underline;
    }

    .logo {
      width: 150px;
      height: auto;
      margin-bottom: 10px;
    }

    .tytul {
      font-size: 20px;
      text-align: center;
    }

    h1 {
      text-align: center;
      margin-top: 40px;
    }

    .site-footer {
      margin-top: 60px;
    }
  </style>
</head>
<body>

  <h1>
    <img src="logo.png" alt="logo strony" class="logo" />
    <div class="tytul">Dołącz do bohaterów zwierząt!</div>
  </h1>


  <h2>Zarejestruj się</h2>
  <form method="POST" action="rejestracja.php">
    <label for="username">Nazwa użytkownika</label>
    <input type="text" id="username" name="username" required>

    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Hasło</label>
    <input type="password" id="password" name="password" required>
    <br>
    <label for="address">Twoje Miasto</label>
    <input type="address" id="address" name="address" required>

    <button type="submit" name="signUp">Zarejestruj się</button>
  </form>

  <p>Masz już konto? <a href="logowanie.html">Zaloguj się</a></p>

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
