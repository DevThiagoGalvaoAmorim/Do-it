<?php
session_start();
$_SESSION = array(); // Esvazia o array de sessão
session_destroy();   // Destrói a sessão
header('Location: login.php');
exit;
?>