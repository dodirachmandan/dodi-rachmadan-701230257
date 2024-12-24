<?php
// Include koneksi.php untuk menggunakan konfigurasi database
include 'koneksi.php';

// Ambil data lapangan dari database
$lapangan_options = [];
$sql_lapangan = "SELECT id_lapangan, nama_lapangan FROM lapangan";
$result = $conn->query($sql_lapangan);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lapangan_options[] = $row;
    }
}

// Verifikasi apakah tombol daftar telah ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir
    $nama = trim($_POST['nama']);
    $telepon = trim($_POST['telepon']);
    $tanggal_booking = trim($_POST['tanggal_booking']);
    $jam = trim($_POST['jam']);
    $alamat = trim($_POST['alamat']);
    $tim = trim($_POST['tim']);
    $foto = $_FILES['foto'];

    // Validasi input wajib
    if (empty($nama) || empty($telepon) || empty($tanggal_booking) || empty($jam) || empty($alamat) || empty($tim) || empty($foto['name'])) {
        echo "<p>Semua kolom wajib diisi. Silakan periksa kembali formulir Anda.</p>";
        echo "<a href='index.php'>Kembali ke Formulir</a>";
        exit();
    }

    // Validasi file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($foto['name']);
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    $max_file_size = 2 * 1024 * 1024; // 2 MB

    if (!in_array($file_type, $allowed_types)) {
        echo "<p>Tipe file tidak valid. Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.</p>";
        echo "<a href='index.php'>Kembali ke Formulir</a>";
        exit();
    }

    if ($foto['size'] > $max_file_size) {
        echo "<p>Ukuran file terlalu besar. Maksimal 2 MB.</p>";
        echo "<a href='index.php'>Kembali ke Formulir</a>";
        exit();
    }

    if ($foto['error'] !== UPLOAD_ERR_OK) {
        echo "<p>Terjadi kesalahan saat mengunggah file. Silakan coba lagi.</p>";
        echo "<a href='index.php'>Kembali ke Formulir</a>";
        exit();
    }

    // Pindahkan file ke direktori target
    if (!move_uploaded_file($foto['tmp_name'], $target_file)) {
        echo "<p>Gagal mengunggah file. Silakan coba lagi.</p>";
        echo "<a href='index.php'>Kembali ke Formulir</a>";
        exit();
    }

    // Siapkan query menggunakan prepared statement
    $sql = "INSERT INTO booking (nama, telepon, alamat, id_lapangan, tanggal_booking, jam, tim, foto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
            "ssssssss",
            $nama,
            $telepon,
            $alamat,
            $_POST['id_lapangan'], // Sesuaikan dengan ID lapangan
            $tanggal_booking,
            $jam,
            $tim,
            $target_file // Menyimpan hanya path file gambar
        );

        // Eksekusi query
        if ($stmt->execute()) {
            echo "<h1>Booking Berhasil</h1>";
            echo "<p>Data booking telah berhasil disimpan.</p>";
            echo "<a href='index.php'>Kembali ke Halaman Utama</a>";
        } else {
            echo "<p>Terjadi kesalahan saat menyimpan data. Silakan coba lagi.</p>";
            echo "<p>Error: " . $stmt->error . "</p>";
            echo "<a href='index.php'>Kembali ke Formulir</a>";
        }

        $stmt->close();
    } else {
        echo "<p>Terjadi kesalahan saat mempersiapkan query. Silakan coba lagi.</p>";
        echo "<p>Error: " . $conn->error . "</p>";
    }

    // Tutup koneksi
    $conn->close();
} else {
    // Redirect kembali ke halaman booking jika tidak ada data POST
    header('Location: index.php');
    exit();
}
