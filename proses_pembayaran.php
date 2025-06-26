<?php
session_start();
$mysqli = new mysqli('localhost', 'root', '', 'drinkstory');

if ($mysqli->connect_error) {
  die("Koneksi gagal: " . $mysqli->connect_error);
}

$session_id = session_id();
$mysqli->query("DELETE FROM cart WHERE session_id = '$session_id'");

echo "<script>alert('Pembayaran berhasil!'); window.location.href = 'menu.html';</script>";
?>
