<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/header.php';
include '../includes/db.php';

if(!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

// Mevcut projeyi çek
$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();

if(!$project) {
    header("Location: dashboard.php");
    exit();
}

if(isset($_POST['update_project'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Görsel güncelleme
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg','jpeg','png','gif'];
        $filename = $_FILES['image']['name'];
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(in_array(strtolower($file_ext), $allowed)) {
            $new_filename = uniqid() . '.' . $file_ext;
            move_uploaded_file($_FILES['image']['tmp_name'], '../assets/images/' . $new_filename);
            
            // Eski görseli sil
            if($project['image']) {
                unlink('../assets/images/' . $project['image']);
            }
        } else {
            $new_filename = $project['image'];
        }
    } else {
        $new_filename = $project['image'];
    }

    $stmt = $conn->prepare("UPDATE projects SET title = ?, description = ?, image = ? WHERE id = ?");
    $stmt->execute([$title, $description, $new_filename, $id]);
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
    <h2>Proje Düzenle</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>
        <textarea name="description" rows="5" required><?php echo htmlspecialchars($project['description']); ?></textarea>
        <?php if($project['image']): ?>
            <p>Mevcut Görsel:</p>
            <img src="../assets/images/<?php echo htmlspecialchars($project['image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" width="100">
        <?php endif; ?>
        <input type="file" name="image" accept="image/*">
        <button type="submit" name="update_project">Güncelle</button>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
