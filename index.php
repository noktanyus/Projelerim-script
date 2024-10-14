<?php include 'includes/header.php'; ?>

<main>
    <section id="projects">
        <h2>Projelerim</h2>
        <?php
        // includes/db.php dosyasının olup olmadığını kontrol et
        if (!file_exists('includes/db.php')) {
            // Dosya yoksa setup.php sayfasına yönlendir
            header("Location: setup.php");
            exit(); // Yönlendirme sonrası scriptin devam etmesini engeller
        }

        // Veritabanı bağlantı dosyasını dahil et
        include 'includes/db.php';

        // Projeleri veritabanından çekme
        $stmt = $conn->prepare("SELECT * FROM projects ORDER BY created_at DESC");
        $stmt->execute();
        $projects = $stmt->fetchAll();

        // URL'leri düz metin olarak işleyen fonksiyon
        function escape_urls($text) {
            // URL regex kalıbı
            $pattern = "/\b(?:https?|ftp):\/\/[a-zA-Z0-9\-_]+\.[a-zA-Z]{2,}(?:\/[^\s]*)?/";
            
            // URL'leri düz metin olarak bırakıyoruz
            return preg_replace_callback($pattern, function ($matches) {
                return htmlspecialchars($matches[0]); // URL'yi HTML'de gösterilecek şekilde dönüştür
            }, $text);
        }

        // Projeleri ekrana yazdırma
        foreach ($projects as $project) {
            echo '<div class="project-card">';
            echo '<h3>' . htmlspecialchars($project['title']) . '</h3>';
            
            // Açıklamayı escape_urls fonksiyonu ile işliyoruz
            echo '<p>' . nl2br(escape_urls($project['description'])) . '</p>';

            if($project['image']) {
                echo '<img src="assets/images/' . htmlspecialchars($project['image']) . '" alt="' . htmlspecialchars($project['title']) . '">';
            }

            echo '</div>';
        }
        ?>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
