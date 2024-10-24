<?php
    // Memulai sesi
    session_start();

    // Meriksa status login
    if (!isset($_SESSION['login'])) {
        header('Location: login.php'); 
        exit();
    }

    // Sertakan file koneksi ke database
    include 'koneksi.php'; // Ini untuk menyertakan file koneksi.php

    // Mengambil jurnal dengan kategori life-reflection dari database
    $sql = "SELECT id, judul, tanggal_published FROM journals WHERE kategori = 'daily-stories' ORDER BY tanggal_published DESC";
    $result = mysqli_query($conn, $sql);

    // Mengecek apakah query berhasil
    if ($result):
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Reflection Journals</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navbar -->
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="images/myJournal.png" alt="Website Logo">
            </div>
            <div class="logout">
                <a href="logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <!-- Konten Utama -->
    <div class="container-jurnal">
        <h1 class="lr">Daily Stories Journals</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="journal-title">
                    <!-- Menampilkan judul jurnal -->
                    <a href="detail-jurnal.php?id=<?= $row['id'] ?>">
                        <?= htmlspecialchars($row['judul']) ?>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-journal">Belum ada catatan untuk kategori Life Reflection.</p>
        <?php endif; ?>

        <!-- Tombol Back -->
        <div class="back-button">
            <a href="index.php" class="btn-back">Back</a>
        </div>
    </div>
</body>
</html>

<?php
// Jika query gagal
else:
    echo "Gagal mengambil data jurnal.";
endif;

// Tutup koneksi ke database
mysqli_close($conn);
?>
