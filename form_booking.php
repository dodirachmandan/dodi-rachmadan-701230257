<?php
// Koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bookinglapanganfutsal'; // Ganti dengan nama database Anda

$conn = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data lapangan
$query = "SELECT id_lapangan, nama_lapangan FROM lapangan";
$result = $conn->query($query);

$lapangan_options = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lapangan_options[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Lapangan Futsal</title>
    <style>
        /* Gaya CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7fa; /* Warna latar belakang hijau muda */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding-top: 150px; /* Memberikan jarak pada bagian atas */
            padding-bottom: 150px; /* Memberikan jarak pada bagian bawah */
        }

        .container {
            width: 70%; /* Lebar lebih lebar */
            max-width: 500px;
            background-color: #ffffff; /* Warna putih untuk formulir */
            padding: 20px;
            border-radius: 0px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            background-color: rgb(115, 196, 246);
            color: white;
            padding: 15px;
            border-radius: 0px;
            margin-bottom: 20px;
        }

        header h1 {
            margin: 0;
            font-size: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 0px;
            box-sizing: border-box;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group input[type="submit"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            background-color: rgb(115, 196, 246); 
            color: white;
            border: none;
            border-radius: 0px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group input[type="submit"]:hover {
            background-color: rgb(115, 196, 246); 
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Booking Lapangan Futsal</h1>
        </header>

        <form action="proses_booking.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="lapangan">Lapangan</label>
                <select id="lapangan" name="id_lapangan" required>
                    <option value="">-- Pilih lapangan --</option>
                    <?php
                    if (!empty($lapangan_options)) {
                        foreach ($lapangan_options as $lapangan) {
                            echo '<option value="' . htmlspecialchars($lapangan['id_lapangan']) . '">' . htmlspecialchars($lapangan['nama_lapangan']) . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No lapangan available</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="telepon">Nomor Telepon</label>
                <input type="tel" id="telepon" name="telepon" required>
            </div>
            <div class="form-group">
                <label for="tanggal_booking">Tanggal Booking</label>
                <input type="date" id="tanggal_booking" name="tanggal_booking" required>
            </div>
            <div class="form-group">
                <label for="anggota">Anggota</label>
                <input type="text" id="anggota" name="anggota" required>
            </div>
            <div class="form-group">
                <label for="jam">Jam</label>
                <input type="text" id="jam" name="jam" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" id="alamat" name="alamat" required>
            </div>
            <div class="form-group">
                <label for="tim">Tim</label>
                <input type="text" id="tim" name="tim" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/*" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Booking">
            </div>
        </form>
    </div>
</body>
</html>
