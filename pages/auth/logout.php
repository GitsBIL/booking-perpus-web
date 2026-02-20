<?php
session_start();
session_unset();
session_destroy();

// Karena file ini ada di folder 'auth', dan 'login.php' juga di 'auth'
// Maka panggil langsung nama filenya saja.
header("Location: login.php");
exit();
?>