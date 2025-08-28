<?php
// $host = "localhost";
// $username = "root";
// $password = "";
// $database = "finderdkv";

$host = "localhost";
$username = "u440649791_finder";
$password = "Fauzaniwa1234aja*";
$database = "u440649791_finderdkv";


// Membuat koneksi
$koneksi = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Set karakter set untuk koneksi
$koneksi->set_charset("utf8");

// Fungsi untuk membersihkan input data
function bersihkanInput($data) {
    global $koneksi;
    return htmlspecialchars(stripslashes(trim($koneksi->real_escape_string($data))));
}
?>
