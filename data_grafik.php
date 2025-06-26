<?php
include 'koneksi.php';

$data = array_fill(1, 12, 0); // Inisialisasi semua bulan

$result = $conn->query("SELECT MONTH(created_at) AS bulan, SUM(total_price) AS total FROM orders GROUP BY bulan");
while ($row = $result->fetch_assoc()) {
  $data[(int)$row['bulan']] = (int)$row['total'];
}

echo json_encode(array_values($data));
