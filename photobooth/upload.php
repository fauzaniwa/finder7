<?php
header("Content-Type: application/json");
require 'koneksi.php'; // ✅ koneksi.php sesuai permintaan

$uploadDir = 'imgphotobooth/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Ambil data JSON dari JavaScript
$data = json_decode(file_get_contents('php://input'), true);
$imageData = $data['image'] ?? '';
$frame = $data['frame'] ?? '';

if (strpos($imageData, 'data:image/png;base64,') === 0) {
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $decoded = base64_decode($imageData);
    $filename = uniqid('photo_') . '.png';
    $filePath = $uploadDir . $filename;

    if (file_put_contents($filePath, $decoded)) {
        // Simpan ke database
        $stmt = $koneksi->prepare("INSERT INTO photobooth (filename, frame, created_at) VALUES (?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("ss", $filename, $frame);
            $stmt->execute();
            $stmt->close();

            echo json_encode(["success" => true, "file" => $filename]);
        } else {
            echo json_encode(["error" => "--."]);
        }
    } else {
        echo json_encode(["error" => "--."]);
    }
} else {
    echo json_encode(["error" => "--."]);
}
