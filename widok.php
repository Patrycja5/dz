<?php
session_start();

// Usuń wszystkie dane sesji
session_unset();
session_destroy();

// Przekierowanie na stronę logowania
header("Location: index.php");
exit();
?>