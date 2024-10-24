<?php
// Memulai sesi
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header('Location: login.php'); 
    exit();
}

include 'koneksi.php';

// Mengambil ID jurnal dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Jika ID tidak valid, arahkan kembali ke halaman life-reflection
if ($id <= 0) {
    header('Location: life-reflection.php');
    exit();
}

// Mengambil detail jurnal dari database berdasarkan ID
$sql = "SELECT judul, tanggal_published, journal_text, cover, kategori FROM journals WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Jika jurnal ditemukan, ambil data
if ($result->num_rows > 0) {
    $journal = $result->fetch_assoc();
} else {
    // Jika tidak ditemukan, arahkan kembali ke halaman life-reflection
    header('Location: life-reflection.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($journal['judul']); ?></title>
    <link rel="stylesheet" href="Style.css">
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
<div class="container-detail-journal">
    <h1><?= htmlspecialchars($journal['judul']); ?></h1>

    <!-- Tanggal Published -->
    <p class="published-date">Published on <?= date("d F, Y", strtotime($journal['tanggal_published'])); ?></p>

    <!-- Cover Image -->
    <?php if (!empty($journal['cover'])): ?>
        <div class="cover-image">
            <img src="images/<?= htmlspecialchars($journal['cover']); ?>" alt="Cover Image" style="max-width:90%; height:auto;">
        </div>
    <?php endif; ?>

    <!-- Isi Jurnal -->
    <div class="journal-content">
        <p><?= nl2br(htmlspecialchars($journal['journal_text'])); ?></p>
    </div>

    <div class="action-buttons">
        <!-- Tombol Back -->
        <div class="back-button">
            <?php if ($journal['kategori'] == 'life-reflection'): ?>
                <a href="life-reflection.php" class="btn-back">Back</a>
            <?php else: ?>
                <a href="daily-stories.php" class="btn-back">Back</a>
            <?php endif; ?>
        </div>

        <!-- Tombol Edit -->
        <div class="edit-button">
            <a href="edit-journal.php?id=<?= $id; ?>" class="btn-edit">Edit Journal</a>
        </div>

        <!-- Tombol Delete -->
        <div class="delete-button">
            <form action="delete-journal.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this journal?');">
                <input type="hidden" name="id" value="<?= $id; ?>">
                <input type="hidden" name="cover" value="<?= htmlspecialchars($journal['cover']); ?>">
                <button type="submit" class="btn-delete">Delete Journal</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
