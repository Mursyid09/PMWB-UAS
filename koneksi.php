<?php
$host = "localhost";
$user = "root";      // default XAMPP
$password = "";      // default XAMPP kosong
$database = "db_projekakhir"; // NAMA DATABASE ANDA

// Membuat koneksi
$koneksi = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($koneksi, "utf8");

// OPTIONAL: Tampilkan pesan sukses (hapus di production)
// echo "Koneksi database berhasil!";
?>