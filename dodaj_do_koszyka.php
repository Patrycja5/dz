<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['produkt_id'])) {
    $_SESSION['cart'][] = $_POST['produkt_id'];
}

header("Location: sklep.php");
exit();
