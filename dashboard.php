<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$role = strtolower($_SESSION['role']); // supaya tidak sensitif huruf besar/kecil

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = $_POST['note'];
    $alarm_time = $_POST['alarm_time'];
    $stmt = $conn->prepare("INSERT INTO notes (user_id, note, alarm_time) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $note, $alarm_time);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Catatan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <span class="active">CATATAN</span>
    <a href="logout.php">LOGOUT</a>
</div>

<div class="container">
    <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?></h1>
    <h2>Daftar Catatan & Alarm</h2>

    <form method="POST" class="note-form">
        <input type="text" name="note" placeholder="Tulis catatan..." required>
        <input type="datetime-local" name="alarm_time" required>
        <button type="submit">Simpan</button>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Catatan</th>
            <th>Alarm</th>
            <?php if ($role === 'biasa' || $role === 'admin'): ?>
            <th>Aksi</th>
        <?php endif; ?>
            
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM notes WHERE user_id = $user_id ORDER BY alarm_time ASC");
        $no = 1;
        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['note']); ?></td>
                <td class="alarm-time"><?= $row['alarm_time']; ?></td>
                <?php if ($role === 'biasa' || $role ==='admin'): ?>
                <td>
                    <a href="edit.php?id=<?= $row['id']; ?>">Edit</a> |
                    <a href="hapus.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="logout.php">Logout</a>

    <audio id="alarmSound" src="alarm.mp3" preload="auto"></audio>
    <script src="alarm.js"></script>
</div>

</body>
</html>
