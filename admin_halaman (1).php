<?php
// Menghubungkan dengan file koneksi.php
include 'koneksi.php';

// Mendapatkan nilai pencarian dari URL jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Membuat kueri pencarian
$query = "SELECT b.id_booking, b.nama, b.telepon, b.alamat, l.nama_lapangan, b.tanggal_booking, b.jam, b.tim, b.foto 
          FROM booking b
          JOIN lapangan l ON b.id_lapangan = l.id_lapangan";
if (!empty($search)) {
    $query .= " WHERE b.nama LIKE '%" . mysqli_real_escape_string($conn, $search) . "%' OR l.nama_lapangan LIKE '%" . mysqli_real_escape_string($conn, $search) . "%'";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Admin - Pemesanan Lapangan</title>
    <style>
        /* Desain Tabel Sederhana */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 0.5rem;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        /* Desain Tombol */
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            cursor: pointer;
            border-radius: 0.25rem;
            margin: 0.2rem;
            text-align: center;
            display: inline-block;
            text-decoration: none;
            color: #fff;
        }
        .btn-warning {
            background-color: #ffc107;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-sm {
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Halaman Admin - Pemesanan Lapangan</h1>

        <!-- Form pencarian -->
        <form action="admin_halaman.php" method="get" class="mb-3">
            <input type="text" name="search" placeholder="Cari pemesanan..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Cari</button>
        </form>

        <!-- Tabel Pemesanan -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Lapangan</th>
                    <th>Tanggal Booking</th>
                    <th>Jam</th>
                    <th>Tim</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) :
                    while ($row = mysqli_fetch_assoc($result)) :
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_booking']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['telepon']); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_lapangan']); ?></td>
                    <td><?php echo htmlspecialchars($row['tanggal_booking']); ?></td>
                    <td><?php echo htmlspecialchars($row['jam']); ?></td>
                    <td><?php echo htmlspecialchars($row['tim']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto Booking" style="max-width: 100px;"></td>
                    <td>
                        <!-- Tombol Edit -->
                        <form action="edit_booking.php" method="post">
                            <input type="hidden" name="id_booking" value="<?php echo htmlspecialchars($row['id_booking']); ?>">
                            <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                        </form>
                        <!-- Tombol Hapus -->
                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo htmlspecialchars($row['id_booking']); ?>">Hapus</button>
                    </td>
                </tr>
                <?php
                    endwhile;
                else :
                ?>
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data pemesanan yang ditemukan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menangani tombol Hapus
            document.querySelectorAll('.delete-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var id_booking = this.getAttribute('data-id');
                    if (confirm('Yakin ingin menghapus?')) {
                        window.location.href = 'hapus_booking.php?id_booking=' + id_booking;
                    }
                });
            });
        });
    </script>
</body>
</html>
