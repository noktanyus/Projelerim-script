<?php
// includes/functions.php

// Oturum kontrolü yapar. Kullanıcı oturum açmamışsa login sayfasına yönlendirir.
function check_auth() {
    session_start();
    if(!isset($_SESSION['user_id'])) {
        header("Location: admin/login.php");
        exit();
    }
}

// Veriyi güvenli hale getirir (XSS koruması için).
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Örneğin, proje başlığı ve açıklaması gibi metinleri güvenli hale getirmek için kullanılabilir.
?>
