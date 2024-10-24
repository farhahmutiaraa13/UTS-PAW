<?php
    include 'koneksi.php';

    $error_message = ''; // Inisialisasi variabel pesan error kosong

    // Meriksa form registrasi
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password untuk keamanan

        // Memeriksa username yang sudah ada
        $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        // Mengenali username yang sudah terdaftar
        if ($result->num_rows > 0) {
            $error_message = "Username sudah terdaftar!"; // Pesan kesalahan
        } else {
            // Menyimpan data registrasi baru
            $query = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $query->bind_param("ss", $username, $password);

            // Eksekusi query dan hasilnya
            if ($query->execute()) {
                echo "Registrasi berhasil!";
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Registrasi gagal!";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Registrasi</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            text-align: left;
        }

        input[type="text"], input[type="password"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px; /* Tambahkan margin untuk memberi jarak dari kontainer */
            text-align: center; /* Memusatkan teks kesalahan */
        }

        .login-link {
            margin-top: 20px; /* Menambahkan jarak 20px dari tombol submit */
            font-size: 14px;
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registrasi</h1>
        <hr> <br>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" name="password" required><br>
            <input type="submit" name="submit" value="Submit">

            <?php if ($error_message): ?> <!-- Memeriksa apakah ada pesan kesalahan -->
                <div class="error-message">
                    <?php echo $error_message; ?> <!-- Menampilkan pesan kesalahan -->
                </div>
            <?php endif; ?>
        
            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a> 
            </div>
        </form>
    </div>
</body>
</html>
