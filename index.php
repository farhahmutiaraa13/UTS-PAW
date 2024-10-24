<?php
    session_start();

    // cek apakah pengguna sudah login
    if (!isset($_SESSION['login'])) {
        // jika belum login, redirect ke halaman login 
        header('Location: login.php');
        exit();
    }

    // set sesi timeout
    $inactive = 1800; // 30 minutes
    if (isset($_SESSION['timeout'])) {
        $session_life = time() - $_SESSION['timeout'];
        if ($session_life > $inactive) {
            session_destroy();
            header('Location: logout.php');
            exit();
        }
    }
    $_SESSION['timeout'] = time();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Website</title>
    <link rel="stylesheet" href="Style.css">
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="images/myJournal.png" alt="Website Logo"> <!-- Ganti dengan logo path -->
            </div>
            <div class="logout">
                <a href="logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <!-- Main content -->
    <div class="container-index">
        <h1>Welcome to myJournal</h1> 
        <p class="a">Tuangkan seluruh imajinasi dan fikiranmu dalam sebuah tulisan</p>
        <a href="upload.php" class="upload-btn">Write Now</a>
        <div class="content-wrapper">
            <!-- Left section - Categories -->
            <div class="left-section">
                <div class="category-box">
                    <h3>Life Reflection</h3>
                    <p>Berisi catatan kehidupan atau motivasi yang fokus untuk refleksi diri maupun pengembangan diri</p>
                    <a href="life-reflection.php" class="category-link">Explore</a>
                </div>
            </div>

            <!-- Right section - Categories -->
            <div class="right-section">
                <div class="category-box">
                    <h3>Daily Stories</h3>
                    <p>Berisi cerita kehidupan sehari-hari, mulai dari momen-momen sederhana hingga yang paling berkesan dalam satu hari</p>
                    <a href="daily-stories.php" class="category-link">Explore</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
