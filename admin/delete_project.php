<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/db.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Projeyi silmeden önce görseli kaldır
    $stmt = $conn->prepare("SELECT image FROM projects WHERE id = ?");
    $stmt->execute([$id]);
    $project = $stmt->fetch();
    if($project && $project['image']) {
        unlink('../assets/images/' . $project['image']);
    }
    
    // Projeyi sil
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: dashboard.php");
exit();
?>
