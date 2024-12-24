<?php
// Menghubungkan dengan file koneksi.php
include 'koneksi.php';

// Mendapatkan data dari tabel booking untuk ditampilkan di halaman laporan
$query = "SELECT * FROM booking";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pimpinan</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .buttons {
            text-align: center;
            margin: 20px;
        }
        .buttons a {
            padding: 10px 20px;
            margin: 5px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .buttons a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Booking Lapangan</h2>
    <div class="buttons">
        <button onclick="window.print();">Cetak</button>
        <a href="#" onclick="exportToCSV();">Unduh Laporan</a>
        <a href="index.php">Kembali</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID Booking</th>
                <th>ID Lapangan</th>
                <th>Nama</th>
                <th>Telepon</th>
                <th>Tanggal Booking</th>
                <th>Jumlah Anggota</th>
                <th>Jam</th>
                <th>Alamat</th>
                <th>Tim</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_booking']); ?></td>
                    <td><?php echo htmlspecialchars($row['id_lapangan']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama']); ?></td>
                    <td><?php echo htmlspecialchars($row['telepon']); ?></td>
                    <td><?php echo htmlspecialchars($row['tanggal_booking']); ?></td>
                    <td><?php echo htmlspecialchars($row['anggota']); ?></td>
                    <td><?php echo htmlspecialchars($row['jam']); ?></td>
                    <td><?php echo htmlspecialchars($row['alamat']); ?></td>
                    <td><?php echo htmlspecialchars($row['tim']); ?></td>
                    <td><img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto" width="100"></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function exportToCSV() {
            var data = [];
            var headers = ['ID Booking', 'ID Lapangan', 'Nama', 'Telepon', 'Tanggal Booking', 'Jumlah Anggota', 'Jam', 'Alamat', 'Tim', 'Foto'];
            data.push(headers.join(','));

            var rows = document.querySelectorAll('table tr');
            rows.forEach(function(row, index) {
                if (index > 0) { // Skip header row
                    var rowData = [];
                    var cells = row.querySelectorAll('td');
                    cells.forEach(function(cell) {
                        rowData.push(cell.textContent.trim());
                    });
                    data.push(rowData.join(','));
                }
            });

            var csvContent = 'data:text/csv;charset=utf-8,' + data.join('\n');
            var encodedUri = encodeURI(csvContent);
            var link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "laporan_booking.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>
