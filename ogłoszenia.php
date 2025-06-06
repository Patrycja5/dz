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

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $radius = (int)$_POST['radius'];
    $stmt = $conn->prepare("UPDATE users SET search_radius_km = ? WHERE id = ?");
    $stmt->bind_param("ii", $radius, $user_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: promien.php?success=1");
    exit();
}

$stmt = $conn->prepare("SELECT search_radius_km FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($radius);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<h2>Ustawienia powiadomień</h2>
<form method="POST">
    <label for="radius">Promień powiadomień (w km):</label>
    <input type="number" name="radius" id="radius" value="<?= $radius ?>" min="1" max="100" required>
    <button type="submit">Zapisz</button>
</form>

<?php if (isset($_GET['success'])): ?>
    <p>Ustawienia zapisane pomyślnie!</p>
<?php endif; ?>
