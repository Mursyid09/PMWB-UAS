<?php
include 'koneksi.php';

// Pastikan variabel aksi ada
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

// 1. LOGIKA TAMBAH TUGAS
if ($aksi == 'tambah') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $deadline = $_POST['deadline'];

    $query = "INSERT INTO tugas (judul, deskripsi, deadline, status) 
              VALUES ('$judul', '$deskripsi', '$deadline', 'Pending')";
    
    if (mysqli_query($conn, $query)) {
        header("location:index.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }

// 2. LOGIKA UPDATE STATUS (Selesai)
} elseif ($aksi == 'update') {
    $id = $_GET['id'];
    mysqli_query($conn, "UPDATE tugas SET status='Selesai' WHERE id=$id");
    header("location:index.php");

// 3. LOGIKA HAPUS DATA
} elseif ($aksi == 'hapus') {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM tugas WHERE id=$id");
    header("location:index.php");
}
?>