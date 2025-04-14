<?php
session_start();
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
  $data = mysqli_fetch_assoc($result);
  $_SESSION['username'] = $data['username'];
  $_SESSION['role'] = $data['role'];
  $_SESSION['user_id'] = $data['id'];
  header("Location: dashboard.php");
} else {
  echo "<script>alert('Login gagal!'); window.location='index.php';</script>";
}
?>