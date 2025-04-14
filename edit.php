<?php
session_start();
include 'db.php';

if ($_SESSION['role'] !== 'biasa') {
    echo "Akses ditolak!";
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM notes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = $_POST['note'];
    $alarm_time = $_POST['alarm_time'];
    $stmt = $conn->prepare("UPDATE notes SET note = ?, alarm_time = ? WHERE id = ?");
    $stmt->bind_param("ssi", $note, $alarm_time, $id);
    $stmt->execute();
    header("Location: dashboard.php");
    exit();
}
?>

<form method="POST">
    <input type="text" name="note" value="<?php echo $row['note']; ?>" required><br>
    <input type="datetime-local" name="alarm_time" value="<?php echo date('Y-m-d\TH:i', strtotime($row['alarm_time'])); ?>" required><br>
    <button type="submit">Simpan</button>
</form>
