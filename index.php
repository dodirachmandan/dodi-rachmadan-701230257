<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Lapangan Futsal</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e8f5e9; /* Hijau muda pucat */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
        }
        .header {
            width: 80%;
            max-width: 900px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .header button {
            background-color: #1e88e5; /* Biru cerah */
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .header button:hover {
            background-color: #1565c0; /* Biru lebih gelap */
            transform: scale(1.05);
        }
        .container {
            background-color: #ffffff;
            width: 70%;
            max-width: 900px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            margin-top: 10px;
        }
        h1 {
            background-color: #1e88e5
            color: white;
            text-align: center;
            padding: 20px;
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }
        .class-list {
            flex: 1;
            padding: 20px;
        }
        .class-card {
            background-color: #d7ffd9; /* Hijau muda pucat */
            margin-bottom: 15px;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .class-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .class-card h3 {
            margin: 0;
            color: #1e88e5; /* Biru cerah */
            font-size: 18px;
        }
        .class-card p {
            margin: 5px 0;
            color: #388e3c; /* Hijau gelap */
        }
        .class-card button {
            background-color: #4caf50; /* Hijau cerah */
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .class-card button:hover {
            background-color: #388e3c; /* Hijau lebih gelap */
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<div class="header">
    <button onclick="location.href='admin_halaman.php'">Admin</button>
    <button onclick="location.href='pimpinan_halaman.php'">Pimpinan</button>
</div>

<div class="container">
    <h1>Booking Lapangan Futsal</h1>
    <div class="class-list">
        <?php
        // Memanggil koneksi dari file koneksi.php
        require 'koneksi.php';

        // Query untuk mengambil data lapangan
        $query = "SELECT id_lapangan, nama_lapangan, harga_perjam, lokasi FROM lapangan";
        $result = mysqli_query($conn, $query);

        // Mengecek apakah ada data yang ditemukan
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='class-card'>
                        <div>
                            <h3>" . htmlspecialchars($row['nama_lapangan']) . "</h3>
                            <p>Harga Perjam: Rp. " . number_format($row['harga_perjam'], 2, ',', '.') . "</p>
                            <p>Lokasi: " . htmlspecialchars($row['lokasi']) . "</p>
                        </div>
                        <button onclick=\"window.location.href='form_booking.php?id_lapangan=" . $row['id_lapangan'] . "'\">Booking</button>
                      </div>";
            }
        } else {
            echo "<p>Tidak ada data yang tersedia di tabel lapangan.</p>";
        }

        // Menutup koneksi database
        mysqli_close($conn);
        ?>
    </div>
</div>

</body>
</html>
