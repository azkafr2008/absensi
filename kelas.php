<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete'])) {
    $class_id = $_GET['delete'];
    $conn->query("DELETE FROM classes WHERE id=$class_id");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $teacher_id = $_SESSION['user_id'];

    $query = "INSERT INTO classes (name, subject, teacher_id) VALUES ('$name', '$subject', '$teacher_id')";
    $conn->query($query);
}

$classes = $conn->query("SELECT * FROM classes WHERE teacher_id=" . $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola Kelas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Kelola Kelas</h2>
        <form method="POST" class="mb-4">
            <input type="text" name="name" placeholder="Nama Kelas" class="form-control mb-2" required>
            <input type="text" name="subject" placeholder="Mata Pelajaran" class="form-control mb-2" required>
            <button type="submit" class="btn btn-success">Tambah Kelas</button>
        </form>
        
        <h4>Daftar Kelas</h4>
        <table class="table table-striped">
            <tr>
                <th>ID</th>
                <th>Nama Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Aksi</th>
            </tr>
            <?php while ($row = $classes->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['subject'] ?></td>
                    <td>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>
