<?php
include 'auth.php';
include 'koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    die("Akses ditolak.");
}

$id = (int)($_GET['id'] ?? 0);
if ($id === 0) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $conn->prepare("SELECT user_id FROM mahasiswa WHERE id_mhs=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($row) {
    $del1 = $conn->prepare("DELETE FROM mahasiswa WHERE id_mhs=?");
    $del1->bind_param("i", $id);
    $del1->execute();

    $uid  = $row['user_id'];
    $del2 = $conn->prepare("DELETE FROM users WHERE id=?");
    $del2->bind_param("i", $uid);
    $del2->execute();
}

header("Location: dashboard.php?deleted=1");
exit;
?>