<?php
    // memulai sesi
    session_start();
    include 'koneksi.php';

    $error_message = ''; // Inisialisasi variabel pesan error

    // Jika pengguna sudah login, alihkan ke halaman index
    if (isset($_SESSION['login'])) {
        header('Location: index.php');
        exit();
    }

    // meriksa status login
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Ambil data pengguna dari database
        $query = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                $_SESSION['login'] = $username;
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php');
                exit();
            } else {
                $error_message = "Password is wrong!"; // Menyimpan pesan kesalahan
            }
        } else {
            $error_message = "Username not found!"; // Menyimpan pesan kesalahan
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5; 
        }

        .container {
            display: flex;
            justify-content: space-between;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 900px; /* Perlebar kontainer */
            text-align: center;
        }

        .left, .right {
            width: 45%; /* Set 45% untuk membagi area kiri dan kanan */
        }

        label {
            display: block;
            margin-top: 5px;
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

        input[type="submit"]:hover {
            background-color: #555;
        }

        .error-message {
            color: black; /* Mengatur warna pesan kesalahan */
            margin-top: 25px; 
            text-align: center; 
        }

        .welcome-message {
            font-size: 18px;
            color: #555;
        }

        .right {
            width: 45%; /* Tetap 45% dari lebar kontainer */
            display: flex;
            flex-direction: column;
            justify-content: center; /* Membuat konten berada di tengah secara vertikal */
            align-items: center; /* Membuat konten berada di tengah secara horizontal */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Login</h1>
            <hr> <br>
            <form action="login.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" required><br>
                <label for="password">Password:</label>
                <input type="password" name="password" required><br>
                <input type="submit" name="submit" value="Submit">
            </form>
            <?php if ($error_message): ?> <!-- Memeriksa apakah ada pesan kesalahan -->
                <div class="error-message">
                    <?php echo $error_message; ?> <!-- Menampilkan pesan kesalahan -->
                </div>
            <?php endif; ?>
        </div>
        <div class="right">
            <h1>Welcome Back!</h1>
            <div class="welcome-message">
                <p>We're glad to see you again. <br>  Log in and write a story, experience, or motivation according to your thoughts or feelings.</p>
            </div>
        </div>
    </div>
</body>
</html>
