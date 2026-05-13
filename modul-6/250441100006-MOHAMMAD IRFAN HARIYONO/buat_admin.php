<?php
include 'koneksi.php';

$user = "admin";
$pass = password_hash("admin123", PASSWORD_DEFAULT);
$role = "admin";

$stmt = $conn->prepare("INSERT INTO users(username,password,role) VALUES (?,?,?)");
$stmt->bind_param("sss",$user,$pass,$role);
$stmt->execute();

echo "Admin berhasil dibuat";
?>