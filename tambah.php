<?php
require_once 'koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $deadline = $_POST['deadline'];
    
    // Validasi
    if(empty($judul)) {
        $error = "Judul tugas harus diisi!";
    } elseif(empty($deadline)) {
        $error = "Deadline harus diisi!";
    } else {
        $query = "INSERT INTO tugas (judul, deskripsi, deadline) VALUES ('$judul', '$deskripsi', '$deadline')";
        
        if(mysqli_query($koneksi, $query)) {
            $success = "Tugas berhasil ditambahkan!";
            // Redirect setelah 1.5 detik
            header("refresh:1.5;url=index.php");
        } else {
            $error = "Gagal menambah tugas: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas Baru</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-plus-circle"></i> Tambah Tugas Baru</h1>
            <a href="index.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </header>

        <div class="form-container">
            <?php if($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
            <?php endif; ?>
            
            <?php if($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
            <?php endif; ?>

            <form method="POST" id="tugasForm">
                <div class="form-group">
                    <label for="judul">
                        <i class="fas fa-heading"></i> Judul Tugas *
                    </label>
                    <input type="text" id="judul" name="judul" 
                           placeholder="Masukkan judul tugas" 
                           value="<?php echo isset($_POST['judul']) ? htmlspecialchars($_POST['judul']) : ''; ?>">
                    <small class="error-message" id="judulError"></small>
                </div>

                <div class="form-group">
                    <label for="deskripsi">
                        <i class="fas fa-align-left"></i> Deskripsi
                    </label>
                    <textarea id="deskripsi" name="deskripsi" 
                              rows="5" 
                              placeholder="Deskripsi tugas (opsional)"><?php echo isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : ''; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="deadline">
                        <i class="far fa-calendar-alt"></i> Deadline *
                    </label>
                    <input type="date" id="deadline" name="deadline" 
                           value="<?php echo isset($_POST['deadline']) ? $_POST['deadline'] : ''; ?>">
                    <small class="error-message" id="deadlineError"></small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Tugas
                    </button>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('deadline').min = today;
            
            // Form validation
            document.getElementById('tugasForm').addEventListener('submit', function(e) {
                let valid = true;
                
                // Clear previous errors
                document.getElementById('judulError').textContent = '';
                document.getElementById('deadlineError').textContent = '';
                
                // Validate judul
                const judul = document.getElementById('judul').value.trim();
                if(judul === '') {
                    document.getElementById('judulError').textContent = 'Judul harus diisi';
                    valid = false;
                }
                
                // Validate deadline
                const deadline = document.getElementById('deadline').value;
                if(deadline === '') {
                    document.getElementById('deadlineError').textContent = 'Deadline harus diisi';
                    valid = false;
                }
                
                if(!valid) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>