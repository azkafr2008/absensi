<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Dashboard</h2>
        <?php if ($user_role == "guru"): ?>
            <a href="kelas.php" class="btn btn-success">Kelola Kelas</a>
            <a href="tambah_murid.php" class="btn btn-primary">Tambah Murid</a>
            <a href="lihat_absensi.php" class="btn btn-info">Lihat Data Absensi</a>
        <?php else: ?>
            <a href="absensi.php" class="btn btn-primary">Absen</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
