<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include '../includes/header.php';
include '../includes/db.php';

// Projeleri çekme
$stmt = $conn->prepare("SELECT * FROM projects ORDER BY created_at DESC");
$stmt->execute();
$projects = $stmt->fetchAll();
?>

<style>
    .dashboard-container {
        padding: 100px 20px 60px 20px;
    }
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .project-table {
        width: 100%;
        border-collapse: collapse;
    }
    .project-table th, .project-table td {
        border: 1px solid #333;
        padding: 10px;
        text-align: left;
    }
    .project-table th {
        background-color: #2c2c2c;
    }
    .project-table tr:nth-child(even) {
        background-color: #1e1e1e;
    }
    .btn {
        padding: 5px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
    .btn-edit {
        background-color: #4CAF50;
        color: white;
    }
    .btn-delete {
        background-color: #f44336;
        color: white;
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h2>Yönetim Paneli</h2>
        <a href="add_project.php" class="btn btn-edit">Proje Ekle</a>
    </div>
    <table class="project-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Başlık</th>
                <th>Açıklama</th>
                <th>Görsel</th>
                <th>Oluşturulma Tarihi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($projects as $project): ?>
            <tr>
                <td><?php echo htmlspecialchars($project['id']); ?></td>
                <td><?php echo htmlspecialchars($project['title']); ?></td>
                <td><?php echo htmlspecialchars(substr($project['description'], 0, 50)) . '...'; ?></td>
                <td>
                    <?php if($project['image']): ?>
                        <img src="../assets/images/<?php echo htmlspecialchars($project['image']); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" width="50">
                    <?php else: ?>
                        - 
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($project['created_at']); ?></td>
                <td>
                    <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="btn btn-edit">Düzenle</a>
                    <a href="delete_project.php?id=<?php echo $project['id']; ?>" class="btn btn-delete" onclick="return confirm('Bu projeyi silmek istediğinizden emin misiniz?');">Sil</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
