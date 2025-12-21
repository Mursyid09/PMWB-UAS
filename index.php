<?php
// Pastikan file koneksi.php ada di folder yang sama
require_once 'koneksi.php';

// Query data tugas
$query = "SELECT * FROM tugas ORDER BY deadline ASC, created_at DESC";
$result = mysqli_query($koneksi, $query);

// Hitung statistik
$query_total = "SELECT COUNT(*) as total FROM tugas";
$query_selesai = "SELECT COUNT(*) as selesai FROM tugas WHERE status='Selesai'";
$query_pending = "SELECT COUNT(*) as pending FROM tugas WHERE status='Pending'";

$total_result = mysqli_query($koneksi, $query_total);
$selesai_result = mysqli_query($koneksi, $query_selesai);
$pending_result = mysqli_query($koneksi, $query_pending);

$total = mysqli_fetch_assoc($total_result)['total'] ?? 0;
$selesai = mysqli_fetch_assoc($selesai_result)['selesai'] ?? 0;
$pending = mysqli_fetch_assoc($pending_result)['pending'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tugas Harian</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-tasks"></i> Manajemen Tugas Harian</h1>
            <p>Kelola tugas Anda dengan mudah dan efisien</p>
        </header>

        <!-- Dashboard Stats -->
        <div class="dashboard-stats">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>Total Tugas</h3>
                <p class="stat-number"><?php echo $total; ?></p>
            </div>
            <div class="stat-card selesai">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3>Selesai</h3>
                <p class="stat-number"><?php echo $selesai; ?></p>
            </div>
            <div class="stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Pending</h3>
                <p class="stat-number"><?php echo $pending; ?></p>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <a href="tambah.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Tugas Baru
            </a>
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari tugas berdasarkan judul...">
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <?php if(mysqli_num_rows($result) > 0): ?>
            <table id="tasksTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result)): 
                        $status_class = ($row['status'] == 'Selesai') ? 'status-selesai' : 'status-pending';
                        $deadline_formatted = date('d M Y', strtotime($row['deadline']));
                        $today = date('Y-m-d');
                        $is_overdue = ($row['deadline'] < $today && $row['status'] == 'Pending');
                    ?>
                    <tr class="<?php echo $is_overdue ? 'overdue' : ''; ?>">
                        <td><?php echo $no++; ?></td>
                        <td class="task-title"><?php echo htmlspecialchars($row['judul']); ?></td>
                        <td class="task-desc"><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                        <td>
                            <div class="deadline-cell">
                                <i class="far fa-calendar"></i>
                                <span><?php echo $deadline_formatted; ?></span>
                                <?php if($is_overdue): ?>
                                <span class="overdue-badge">TERLAMBAT</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge <?php echo $status_class; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                        <td class="action-buttons">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="proses_status.php?id=<?php echo $row['id']; ?>&status=<?php echo ($row['status'] == 'Pending') ? 'Selesai' : 'Pending'; ?>" 
                               class="btn btn-status" 
                               title="<?php echo ($row['status'] == 'Pending') ? 'Tandai Selesai' : 'Kembalikan ke Pending'; ?>">
                                <?php if($row['status'] == 'Pending'): ?>
                                    <i class="fas fa-check"></i>
                                <?php else: ?>
                                    <i class="fas fa-undo"></i>
                                <?php endif; ?>
                            </a>
                            <a href="hapus.php?id=<?php echo $row['id']; ?>" 
                               class="btn btn-delete" 
                               title="Hapus"
                               onclick="return confirm('Yakin ingin menghapus tugas \'<?php echo addslashes($row['judul']); ?>\'?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-list fa-3x"></i>
                <h3>Belum ada tugas</h3>
                <p>Mulai dengan menambahkan tugas baru</p>
                <a href="tambah.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Tugas Pertama
                </a>
            </div>
            <?php endif; ?>
        </div>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Manajemen Tugas Harian | Dibuat untuk Projek Akhir</p>
        </footer>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>