<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header('Location: login.php'); 
    exit();
}

// Mengambil ID jurnal dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Jika ID tidak valid, arahkan kembali ke halaman utama
if ($id <= 0) {
    header('Location: life-reflection.php');
    exit();
}

// Mengambil detail jurnal dari database
$sql = "SELECT judul, journal_text, cover, kategori FROM journals WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $journal = $result->fetch_assoc();
} else {
    header('Location: life-reflection.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Journal</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>

<div class="container-form">
    <h1>Edit Journal</h1>
    <form action="update-journal.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id; ?>">
        <input type="hidden" name="old_cover" value="<?= htmlspecialchars($journal['cover']); ?>">

        <label for="judul">Title:</label>
        <input type="text" name="judul" id="judul" value="<?= htmlspecialchars($journal['judul']); ?>" required>

        <label for="journal_text">Content:</label>
        <textarea name="journal_text" id="journal_text" rows="10" required><?= htmlspecialchars($journal['journal_text']); ?></textarea>

        <label for="cover">Change Cover Image (optional):</label>
        <input type="file" name="cover" id="cover" accept="image/*">

        <button type="submit" class="btn-save">Save Changes</button>
    </form>
</div>

</body>
</html>
