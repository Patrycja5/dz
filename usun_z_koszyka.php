<?php
session_start();

if (isset($_POST['produkt_id']) && isset($_SESSION['cart'])) {
    $id = $_POST['produkt_id'];
    $index = array_search($id, $_SESSION['cart']);
    if ($index !== false) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Resetowanie indeksów
    }
}

header("Location: koszyk.php");
exit();
