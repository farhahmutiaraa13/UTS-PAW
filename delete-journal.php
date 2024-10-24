<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['login'])) {
    header('Location: login.php'); 
    exit();
}

// Cek apakah ID jurnal dan nama file cover telah dikirim
if (isset($_POST['id']) && isset($_POST['cover'])) {
    $id = intval($_POST['id']);
    $cover = $_POST['cover'];

    // Ambil kategori jurnal sebelum menghapusnya
    $sql = "SELECT kategori FROM journals WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $journal = $result->fetch_assoc();
        $kategori = $journal['kategori'];

        // Mulai transaksi untuk memastikan konsistensi data
        $conn->begin_transaction();

        try {
            // Hapus jurnal dari database
            $sql_delete = "DELETE FROM journals WHERE id = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bind_param("i", $id);
            $stmt_delete->execute();

            // Cek apakah jurnal dihapus dengan sukses
            if ($stmt_delete->affected_rows > 0) {
                // Hapus file gambar jika ada
                if (!empty($cover) && file_exists("images/" . $cover)) {
                    unlink("images/" . $cover);
                }

                // Commit transaksi jika semua berhasil
                $conn->commit();

                // Redirect sesuai kategori jurnal
                if ($kategori == 'life-reflection') {
                    header('Location: life-reflection.php');
                } elseif ($kategori == 'daily-stories') {
                    header('Location: daily-stories.php');
                } else {
                    header('Location: index.php'); // Jika kategori tidak dikenal, arahkan ke halaman utama
                }
                exit();
            } else {
                throw new Exception("Journal not found or could not be deleted.");
            }
        } catch (Exception $e) {
            // Rollback jika terjadi kesalahan
            $conn->rollback();
            echo "Gagal menghapus journal : " . $e->getMessage();
        }
    } else {
        // Jika jurnal tidak ditemukan, arahkan kembali ke halaman utama
        header('Location: index.php');
        exit();
    }
} else {
    // Jika ID atau file cover tidak ditemukan, kembali ke halaman utama
    header('Location: index.php');
    exit();
}
