<?php
// Menghubungkan dengan file koneksi.php
include 'koneksi.php';

// Mendapatkan `id_booking` dari formulir atau URL
$id_booking = isset($_POST['id_booking']) ? $_POST['id_booking'] : null;

if (!$id_booking) {
    die("ID booking tidak ditemukan.");
}

// Mengambil data booking berdasarkan ID
$query = "SELECT * FROM booking WHERE id_booking = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_booking);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
} else {
    die("Data booking tidak ditemukan.");
}

// Menangani update data saat formulir disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id_lapangan = $_POST['id_lapangan'];
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $tanggal_booking = $_POST['tanggal_booking'];
    $anggota = $_POST['anggota'];
    $jam = $_POST['jam'];
    $alamat = $_POST['alamat'];
    $tim = $_POST['tim'];

    // File upload
    if ($_FILES['foto']['name']) {
        $foto = $_FILES['foto'];
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($foto['name']);
        $uploadOk = 1;

        // Cek apakah file gambar valid
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            if (move_uploaded_file($foto['tmp_name'], $targetFile)) {
                $fotoPath = $targetFile;
            } else {
                echo "Gagal mengunggah gambar.";
                exit;
            }
        } else {
            echo "Hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
            exit;
        }
    } else {
        $fotoPath = $data['foto']; // mempertahankan gambar lama jika tidak diupload ulang
    }

    $updateQuery = "UPDATE booking SET id_lapangan = ?, nama = ?, telepon = ?, tanggal_booking = ?, anggota = ?, jam = ?, alamat = ?, tim = ?, foto = ? WHERE id_booking = ?";
    $updateStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, "sssssssssi", $id_lapangan, $nama, $telepon, $tanggal_booking, $anggota, $jam, $alamat, $tim, $fotoPath, $id_booking);

    if (mysqli_stmt_execute($updateStmt)) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'admin_dashboard.php';</script>";
    } else {
        echo "Gagal memperbarui data: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Booking</title>
    <style>
        form {
            width: 50%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"], input[type="date"], input[type="time"], textarea, input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .btn-back {
            background-color: #ccc;
            color: black;
            text-decoration: none;
            padding: 10px 20px;
            display: inline-block;
            border-radius: 4px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form method="post" action="" enctype="multipart/form-data">
        <h2>Edit Data Booking</h2>
        <input type="hidden" name="id_booking" value="<?php echo htmlspecialchars($id_booking); ?>">

        <label for="id_lapangan">ID Lapangan:</label>
        <input type="text" name="id_lapangan" id="id_lapangan" value="<?php echo htmlspecialchars($data['id_lapangan']); ?>" required>

        <label for="nama">Nama:</label>
        <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>

        <label for="telepon">Telepon:</label>
        <input type="text" name="telepon" id="telepon" value="<?php echo htmlspecialchars($data['telepon']); ?>" required>

        <label for="tanggal_booking">Tanggal Booking:</label>
        <input type="date" name="tanggal_booking" id="tanggal_booking" value="<?php echo htmlspecialchars($data['tanggal_booking']); ?>" required>

        <label for="anggota">Jumlah Anggota:</label>
        <input type="text" name="anggota" id="anggota" value="<?php echo htmlspecialchars($data['anggota']); ?>" required>

        <label for="jam">Jam:</label>
        <input type="time" name="jam" id="jam" value="<?php echo htmlspecialchars($data['jam']); ?>" required>

        <label for="alamat">Alamat:</label>
        <textarea name="alamat" id="alamat" required><?php echo htmlspecialchars($data['alamat']); ?></textarea>

        <label for="tim">Tim:</label>
        <input type="text" name="tim" id="tim" value="<?php echo htmlspecialchars($data['tim']); ?>">

        <label for="foto">Foto:</label>
        <input type="file" name="foto" id="foto">
        <?php if ($data['foto']): ?>
            <img src="<?php echo htmlspecialchars($data['foto']); ?>" alt="Current Image" width="100">
        <?php endif; ?>

        <button type="submit" name="update">Update</button>
        <a href="admin_halaman.php" class="btn-back">Kembali</a>
    </form>
</body>
</html>
