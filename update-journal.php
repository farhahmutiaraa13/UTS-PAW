<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

// Periksa apakah form sudah dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $judul = $_POST['judul'];
    $journal_text = $_POST['journal_text'];
    $old_cover = $_POST['old_cover'];

    // Inisialisasi variabel cover baru
    $new_cover = $old_cover;

    // Proses file gambar jika diunggah
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] == 0) {
        $upload_dir = 'images/';
        $cover_name = basename($_FILES['cover']['name']);
        $target_file = $upload_dir . $cover_name;
        
        // Pindahkan file yang diupload ke direktori tujuan
        if (move_uploaded_file($_FILES['cover']['tmp_name'], $target_file)) {
            // Hapus cover lama jika ada
            if (!empty($old_cover) && file_exists($upload_dir . $old_cover)) {
                unlink($upload_dir . $old_cover);
            }
            // Set cover baru
            $new_cover = $cover_name;
        }
    }

    // Update jurnal di database
    $sql = "UPDATE journals SET judul = ?, journal_text = ?, cover = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $judul, $journal_text, $new_cover, $id);

    if ($stmt->execute()) {
        // Redirect sesuai kategori setelah update
        $sql_kategori = "SELECT kategori FROM journals WHERE id = ?";
        $stmt_kategori = $conn->prepare($sql_kategori);
        $stmt_kategori->bind_param("i", $id);
        $stmt_kategori->execute();
        $result = $stmt_kategori->get_result();
        $journal = $result->fetch_assoc();

        if ($journal['kategori'] == 'life-reflection') {
            header('Location: life-reflection.php');
        } elseif ($journal['kategori'] == 'daily-stories') {
            header('Location: daily-stories.php');
        } else {
            header('Location: index.php');
        }
    } else {
        echo "Failed to update journal.";
    }
} else {
    header('Location: life-reflection.php');
    exit();
}
?>
