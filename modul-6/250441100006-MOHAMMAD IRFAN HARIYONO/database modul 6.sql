CREATE DATABASE IF NOT EXISTS db_praktikum;
USE db_praktikum;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    PASSWORD VARCHAR(255) NOT NULL,
    ROLE VARCHAR(20) NOT NULL DEFAULT 'mahasiswa'
);


CREATE TABLE kelas (
    id_kelas INT AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(100) NOT NULL,
    ruangan VARCHAR(50) NOT NULL,
    jadwal VARCHAR(100) NOT NULL,
    deskripsi TEXT
);


CREATE TABLE mahasiswa (
    id_mhs INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nim VARCHAR(20) NOT NULL,
    id_kelas INT,
    user_id INT,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
