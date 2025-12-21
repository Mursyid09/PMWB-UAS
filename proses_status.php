<?php
require_once 'koneksi.php';

if(isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'] === 'Selesai' ? 'Selesai' : 'Pending';
    
    $query = "UPDATE tugas SET status = '$status' WHERE id = $id";
    
    if(mysqli_query($koneksi, $query)) {
        header('Location: index.php');
        exit();
    } else {
        die("Error: " . mysqli_error($koneksi));
    }
} else {
    header('Location: index.php');
    exit();
}
?>