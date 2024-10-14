<?php
session_start();
include '../includes/db.php';

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Geçersiz kullanıcı adı veya şifre.";
    }
}
?>

<?php include '../includes/header.php'; ?>
<style>
    .login-container {
        max-width: 400px;
        margin: 100px auto;
        padding: 30px;
        background-color: #1f1f1f;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.5);
    }
    .login-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .login-container input {
        width: 100%;
        margin-bottom: 15px;
    }
    .error {
        color: red;
        text-align: center;
    }
</style>
<div class="login-container">
    <h2>Yönetim Girişi</h2>
    <?php if(isset($error)) echo '<p class="error">'.$error.'</p>'; ?>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Kullanıcı Adı" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <button type="submit" name="login">Giriş Yap</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>
