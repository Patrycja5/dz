<?php
// dodaj_zaginiecie.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: logowanie.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Dodaj zgłoszenie zaginięcia</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="szata.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    body { font-family: 'Inter', sans-serif; background: #f7f7f7; }
    #map { height: 400px; width: 100%; margin-bottom: 1em; }
    form {
      max-width: 700px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    label { display: block; margin-top: 10px; }
    input, textarea, button {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }
    button {
      background-color: #2c98f0;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover { background-color: #217bd0; }
    .info {
      font-size: 0.9rem;
      color: #555;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <h1 style="text-align:center;"><img src="logo.png" class="logo"> Dodaj zgłoszenie zaginięcia</h1>
  <main>
    <h2 style="text-align: center;">Formularz zgłoszenia</h2>
    <form action="zapisz_zaginiecie.php" method="POST" enctype="multipart/form-data">
      <label>Nazwa zwierzaka:
        <input type="text" name="nazwa" required>
      </label>

      <label>Data zaginięcia:
        <input type="date" name="data_zaginiecia" required>
      </label>

      <label>Opis:
        <textarea name="opis" rows="4" required></textarea>
      </label>

      <label>Zdjęcie:
        <input type="file" name="zdjecie" accept="image/*" required>
      </label>

      <label>Latitude:
        <input type="text" name="latitude" placeholder="np. 52.2297" required>
      </label>

      <label>Longitude:
        <input type="text" name="longitude" placeholder="np. 21.0122" required>
      </label>

      <label>Promień obszaru (metry):
        <input type="number" name="radius" min="10" value="500" required>
      </label>

      <p class="info">
        Dane kontaktowe (e-mail, telefon, miejsce zamieszkania) zostaną pobrane automatycznie z Twojego profilu.
      </p>

      <label>Wskaż pozycję na mapie (opcjonalnie kliknij, aby wypełnić latitude/longitude):</label>
      <div id="map"></div>
      <input type="hidden" name="lat" id="lat">
      <input type="hidden" name="lng" id="lng">

      <button type="submit">Dodaj zgłoszenie</button>
    </form>
  </main>

  <footer class="site-footer">
    <div class="footer-content">
      <p>Kontakt: kontakt@twojastrona.pl | Tel: +48 123 456 789</p>
      <p>© 2025 TwojaStrona.pl</p>
    </div>
  </footer>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    const map = L.map('map').setView([52.237, 21.017], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    let marker;

    map.on('click', function(e) {
      const { lat, lng } = e.latlng;
      if (marker) {
        marker.setLatLng(e.latlng);
      } else {
        marker = L.marker(e.latlng).addTo(map);
      }
      document.getElementById('lat').value = lat;
      document.getElementById('lng').value = lng;
      // Dla wygody: nadpisujemy pola latitude/longitude w formularzu
      document.querySelector('input[name="latitude"]').value = lat;
      document.querySelector('input[name="longitude"]').value = lng;
    });
  </script>
</body>
</html>
