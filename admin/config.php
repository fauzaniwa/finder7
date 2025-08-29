<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "finderdkv";

// $host = "localhost";
// $username = "u440649791_finder";
// $password = "Fauzaniwa1234aja*";
// $database = "u440649791_finderdkv";


// Membuat koneksi ke database
$conn = mysqli_connect($host, $username, $password, $database);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>