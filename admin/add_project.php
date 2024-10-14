<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/header.php';
include '../includes/db.php';

if(isset($_POST['add_project'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Görsel yükleme işlemi
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg','jpeg','png','gif'];
        $filename = $_FILES['image']['name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(in_array(strtolower($file_ext), $allowed)) {
            $new_filename = uniqid() . '.' . $file_ext;
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $new_filename);
        } else {
            $new_filename = null;
        }
    } else {
        $new_filename = null;
    }

    $stmt = $conn->prepare("INSERT INTO projects (title, description, image) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $new_filename]);
    header("Location: dashboard.php");
    exit();
}
?>

<style>
    .form-container {
        max-width: 600px;
        margin: 100px auto;
        padding: 30px;
        background-color: #1f1f1f;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.5);
    }
    .form-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    .form-container input, .form-container textarea {
        width: 100%;
        margin-bottom: 15px;
    }
    .form-container button {
        width: 100%;
    }
</style>

<div class="form-container">
    <h2>Proje Ekle</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Proje Başlığı" required>
        <textarea name="description" placeholder="Proje Açıklaması" rows="5" required></textarea>
        <input type="file" name="image" accept="image/*">
        <button type="submit" name="add_project">Ekle</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
