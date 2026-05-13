<?php
$conn = new mysqli("localhost","root","","db_praktikum");

if($conn->connect_error){
    die("Koneksi gagal");
}
?>