<?php
require_once 'koneksi.php';

if(!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = intval($_GET['id']);
$error = '';
$success = '';

// Ambil data tugas
$query = "SELECT * FROM tugas WHERE id = $id";
$result = mysqli_query($koneksi, $query);

if(mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit();
}

$tugas = mysqli_fetch_assoc($result);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $deadline = $_POST['deadline'];
    
    if(empty($judul) || empty($deadline)) {
        $error = "Judul dan deadline harus diisi!";
    } else {
        $query = "UPDATE tugas SET 
                  judul = '$judul', 
                  deskripsi = '$deskripsi', 
                  deadline = '$deadline' 
                  WHERE id = $id";
        
        if(mysqli_query($koneksi, $query)) {
            $success = "Tugas berhasil diperbarui!";
            // Update data yang ditampilkan
            $tugas['judul'] = $judul;
            $tugas['deskripsi'] = $deskripsi;
            $tugas['deadline'] = $deadline;
        } else {
            $error = "Gagal update: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-edit"></i> Edit Tugas</h1>
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

            <form method="POST" id="editForm">
                <div class="form-group">
                    <label for="judul">
                        <i class="fas fa-heading"></i> Judul Tugas *
                    </label>
                    <input type="text" id="judul" name="judul" 
                           value="<?php echo htmlspecialchars($tugas['judul']); ?>"
                           required>
                    <small class="error-message" id="judulError"></small>
                </div>

                <div class="form-group">
                    <label for="deskripsi">
                        <i class="fas fa-align-left"></i> Deskripsi
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="5"><?php echo htmlspecialchars($tugas['deskripsi']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="deadline">
                        <i class="far fa-calendar-alt"></i> Deadline *
                    </label>
                    <input type="date" id="deadline" name="deadline" 
                           value="<?php echo $tugas['deadline']; ?>"
                           required>
                    <small class="error-message" id="deadlineError"></small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Tugas
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
            // Form validation
            document.getElementById('editForm').addEventListener('submit', function(e) {
                let valid = true;
                
                document.getElementById('judulError').textContent = '';
                document.getElementById('deadlineError').textContent = '';
                
                const judul = document.getElementById('judul').value.trim();
                if(judul === '') {
                    document.getElementById('judulError').textContent = 'Judul harus diisi';
                    valid = false;
                }
                
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