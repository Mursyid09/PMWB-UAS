<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php';

// Ambil data statistik untuk Dashboard
$query_total = mysqli_query($conn, "SELECT COUNT(*) as jml FROM tugas");
$data_total = mysqli_fetch_assoc($query_total);

$query_selesai = mysqli_query($conn, "SELECT COUNT(*) as jml FROM tugas WHERE status='Selesai'");
$data_selesai = mysqli_fetch_assoc($query_selesai);

$query_pending = mysqli_query($conn, "SELECT COUNT(*) as jml FROM tugas WHERE status='Pending'");
$data_pending = mysqli_fetch_assoc($query_pending);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tugas Harian</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Manajemen Tugas Harian</h1>

        <div class="dashboard">
            <div class="card" style="background:#4a90e2">Total: <?php echo $data_total['jml']; ?></div>
            <div class="card" style="background:#2ecc71">Selesai: <?php echo $data_selesai['jml']; ?></div>
            <div class="card" style="background:#e67e22">Pending: <?php echo $data_pending['jml']; ?></div>
        </div>

        <form id="taskForm" action="proses_tugas.php?aksi=tambah" method="POST">
            <input type="text" name="judul" id="judul" placeholder="Judul Tugas..." required>
            <textarea name="deskripsi" placeholder="Deskripsi..."></textarea>
            <input type="date" name="deadline" required>
            <button type="submit">Tambah Tugas</button>
        </form>

        <input type="text" id="searchInput" placeholder="Cari tugas...">
        <table id="taskTable">
            <thead>
                <tr>
                    <th>Tugas</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tampil = mysqli_query($conn, "SELECT * FROM tugas ORDER BY id DESC");
                while($row = mysqli_fetch_assoc($tampil)):
                ?>
                <tr>
                    <td>
                        <strong><?php echo $row['judul']; ?></strong><br>
                        <small><?php echo $row['deskripsi']; ?></small>
                    </td>
                    <td><?php echo $row['deadline']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <?php if($row['status'] == 'Pending'): ?>
                            <a href="proses_tugas.php?aksi=update&id=<?php echo $row['id']; ?>" style="color:green">✔</a>
                        <?php endif; ?>
                        <a href="javascript:void(0)" onclick="konfirmasiHapus(<?php echo $row['id']; ?>)" style="color:red">🗑</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="assets/script.js"></script>
</body>
</html>