<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

// Mendapatkan ID guru
$teacher_id = $_SESSION['user_id'];

// Mendapatkan daftar kelas yang diajarkan oleh guru
$classes = $conn->query("SELECT * FROM classes WHERE teacher_id = $teacher_id");

if (isset($_GET['class_id'])) {
    $class_id = $_GET['class_id'];
    // Mendapatkan data absensi berdasarkan kelas
    $attendance_query = $conn->query("
        SELECT a.date, a.status, u.username, a.student_id, a.attendance_code 
        FROM attendance a 
        JOIN users u ON a.student_id = u.id 
        WHERE a.class_id = $class_id
    ");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Absensi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Data Absensi Siswa</h2>
        
        <h5>Pilih Kelas:</h5>
        <form method="GET" class="mb-4">
            <select name="class_id" class="form-select mb-2" required>
                <option value="">Pilih Kelas</option>
                <?php while ($row = $classes->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-primary">Lihat Absensi</button>
        </form>

        <?php if (isset($attendance_query)): ?>
            <h4>Absensi untuk Kelas ID: <?= $class_id ?></h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Nomor Absen</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($attendance = $attendance_query->fetch_assoc()): ?>
                        <tr>
                            <td><?= $attendance['date'] ?></td>
                            <td><?= $attendance['username'] ?></td>
                            <td><?= $attendance['attendance_code'] ?></td>
                            <td><?= $attendance['status'] ?></td>
                            <td>
                                <a href="edit_siswa.php?student_id=<?= $attendance['student_id'] ?>&class_id=<?= $class_id ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>
