<?php
require_once 'koneksi.php';

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Ambil judul untuk pesan konfirmasi (optional)
    $query = "SELECT judul FROM tugas WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    
    if(mysqli_num_rows($result) > 0) {
        $tugas = mysqli_fetch_assoc($result);
        $judul = $tugas['judul'];
        
        // Hapus data
        $query = "DELETE FROM tugas WHERE id = $id";
        
        if(mysqli_query($koneksi, $query)) {
            // Redirect dengan pesan sukses
            header('Location: index.php?deleted=1&judul=' . urlencode($judul));
            exit();
        } else {
            die("Gagal menghapus: " . mysqli_error($koneksi));
        }
    } else {
        header('Location: index.php');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
?>