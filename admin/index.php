<?php
// admin/index.php

session_start();

// Eğer kullanıcı oturum açmışsa dashboard'a yönlendir
if(isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    // Oturum açılmamışsa login sayfasına yönlendir
    header("Location: login.php");
    exit();
}
?>
