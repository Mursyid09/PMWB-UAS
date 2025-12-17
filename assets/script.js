// 1. Validasi Form
document.getElementById('taskForm').addEventListener('submit', function(e) {
    const judul = document.getElementById('judul').value;
    if (judul.trim() === "") {
        alert("Judul tugas tidak boleh kosong!");
        e.preventDefault();
    }
});

// 2. Fitur Pencarian Real-time
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#taskTable tbody tr');

    rows.forEach(row => {
        let text = row.cells[0].textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});

// 3. Konfirmasi Hapus
function konfirmasiHapus(id) {
    if (confirm("Apakah Anda yakin ingin menghapus tugas ini?")) {
        window.location.href = "proses_tugas.php?aksi=hapus&id=" + id;
    }
}