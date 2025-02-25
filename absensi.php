<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'];
    $attendance_code = $_POST['attendance_code'];
    $status = $_POST['status'];
    $student_id = $_SESSION['user_id'];

    // Menyimpan data absensi ke database
    $stmt = $conn->prepare("INSERT INTO attendance (student_id, class_id, attendance_code, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $student_id, $class_id, $attendance_code, $status);
    $stmt->execute();

    // Mendapatkan waktu absensi
    $current_time = date("H:i:s");

    // Keterangan yang ditampilkan
    $message = "Anda telah melakukan absensi pada pukul " . $current_time;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Siswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script>
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('realTime').innerText = `${hours}:${minutes}:${seconds}`;
        }

        // Memperbarui waktu setiap detik
        setInterval(updateTime, 1000);
    </script>
</head>
<body onload="updateTime()">
    <div class="container mt-5">
        <h2 class="mb-4">Absensi Siswa</h2>
        <div class="mb-2">
            <strong>Waktu Sekarang: </strong><span id="realTime"></span>
        </div>
        <?php if (!empty($message)): ?>
            <div class="alert alert-success">
                <?= $message ?>
            </div>
        <?php endif; ?>
        <form method="POST" class="mb-4">
            <select name="class_id" class="form-select mb-2" required>
                <option value="">Pilih Kelas</option>
                <?php
                // Mengambil daftar kelas dari database
                $classes = $conn->query("SELECT * FROM classes");
                while ($row = $classes->fetch_assoc()):
                ?>
                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                <?php endwhile; ?>
            </select>
            <input type="text" name="attendance_code" placeholder="Nomor Absen" class="form-control mb-2" required>
            <select name="status" class="form-select mb-2" required>
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
            </select>
            <button type="submit" class="btn btn-success">Kirim Absensi</button>
        </form>
        <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>
