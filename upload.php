<?php
    // Koneksi ke database
    $host = 'localhost';
    $dbname = 'project_uts';
    $username = 'root';
    $password = '';

    $conn = new mysqli($host, $username, $password, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Jika form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $judul = $conn->real_escape_string($_POST['judul']);
        $tanggal = $conn->real_escape_string($_POST['tanggal']);
        $kategori = $conn->real_escape_string($_POST['kategori']);
        $journal_text = $conn->real_escape_string($_POST['journal_text']);
        
        // Upload cover
        $cover_name = $_FILES['cover']['name'];
        $cover_tmp = $_FILES['cover']['tmp_name'];
        $cover_folder = 'images/' . basename($cover_name);

        if (move_uploaded_file($cover_tmp, $cover_folder)) {
            // Simpan data ke database
            $sql = "INSERT INTO journals (judul, tanggal_published, kategori, cover, journal_text) 
                    VALUES ('$judul', '$tanggal', '$kategori', '$cover_name', '$journal_text')";

            if ($conn->query($sql) === TRUE) {
                // Jika kategori life-reflection, arahkan ke halaman jurnal life-reflection
                if ($kategori == 'life-reflection') {
                    header('Location: life-reflection.php');
                } else {
                    // Jika kategori daily-stories, arahkan ke halaman lain (misalnya daftar daily-stories)
                    header('Location: daily-stories.php');
                }
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Gagal meng-upload cover journal.";
        }

        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Journal</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <div class="container-form">
        <h1>Simpan Ceritamu Disini</h1>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <!-- Cover Journal -->
            <label for="cover">Cover :</label>
            <input type="file" name="cover" id="cover" required><br><br>

            <!-- Judul Journal -->
            <label for="judul">Judul :</label>
            <input type="text" name="judul" id="judul" required><br><br>

            <!-- Tanggal Published -->
            <label for="tanggal">Tanggal Published :</label>
            <input type="date" name="tanggal" id="tanggal" required><br><br>

            <!-- Kategori -->
            <label for="kategori">Kategori :</label>
            <select name="kategori" id="kategori" required>
                <option value="life-reflection">Life Reflection</option>
                <option value="daily-stories">Daily Stories</option>
            </select><br><br>

            <!-- Isi Journal -->
            <label for="journal_text">Isi Journal :</label>
            <textarea name="journal_text" id="journal_text" rows="10" cols="50" required></textarea><br><br>

            <!-- Submit Button -->
            <input type="submit" value="Upload Journal">
        </form>
    </div>

</body>
</html>