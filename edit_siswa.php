<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guru') {
    header("Location: login.php");
    exit;
}

// Cek jika ada ID siswa yang diberikan untuk diedit
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id'];

    // Ambil data siswa dari database
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        echo "Siswa tidak ditemukan.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $role = $_POST['role'];

    // Update data siswa ke database
    $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $role, $student_id);
    $stmt->execute();

    // Redirect setelah update
    header("Location: lihat_absensi.php?class_id=" . $_GET['class_id']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Siswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Data Siswa</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nama Siswa</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $student['username'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Peran</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="siswa" <?= ($student['role'] == 'siswa') ? 'selected' : '' ?>>Siswa</option>
                    <option value="guru" <?= ($student['role'] == 'guru') ? 'selected' : '' ?>>Guru</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        </form>
        <a href="lihat_absensi.php?class_id=<?= $_GET['class_id'] ?>" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>
