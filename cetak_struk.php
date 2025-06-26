<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
  $orderId = intval($_POST['order_id']);

  // Ubah status menjadi "Success"
  $conn->query("UPDATE orders SET status = 'Success' WHERE id = $orderId");

  // Ambil data order
  $result = $conn->query("SELECT * FROM orders WHERE id = $orderId");
  $order = $result->fetch_assoc();
  if (!$order) {
    echo "Order tidak ditemukan.";
    exit;
  }
} else {
  echo "Akses tidak valid.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Cetak Struk</title>
  <style>
    body {
      font-family: 'Courier New', Courier, monospace;
      background-color: #f4f4f4;
      padding: 30px;
    }

    .struk-container {
      background: white;
      width: 350px;
      margin: auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .struk-header {
      text-align: center;
      border-bottom: 2px dashed #333;
      padding-bottom: 10px;
    }

    .struk-header h2 {
      margin: 0;
      font-size: 20px;
    }

    .struk-body {
      margin-top: 15px;
    }

    .info-line {
      display: flex;
      justify-content: space-between;
      margin: 5px 0;
    }

    .dashed-separator {
      border-top: 2px dashed #aaa;
      margin: 15px 0;
    }

    .struk-footer {
      text-align: center;
      font-size: 12px;
      margin-top: 15px;
      color: #777;
    }
  </style>
</head>
<body>

<div class="struk-container">
  <div class="struk-header">
    <h2>DRINKSTORY</h2>
    <small><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></small>
  </div>

  <div class="struk-body">
    <div class="info-line">
      <strong>Produk:</strong>
      <span><?= htmlspecialchars($order['item_name']) ?></span>
    </div>
    <div class="info-line">
      <strong>Jumlah:</strong>
      <span><?= $order['quantity'] ?></span>
    </div>
    <div class="info-line">
      <strong>Total:</strong>
      <span>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></span>
    </div>

    <div class="dashed-separator"></div>

    <div class="info-line">
      <strong>Status:</strong>
      <span><?= $order['status'] ?></span>
    </div>
  </div>

  <div class="struk-footer">
    <p>Terima kasih telah berbelanja!</p>
    <p>www.drinkstory.com</p>
  </div>
</div>

<script>
  // Cetak otomatis saat halaman dibuka
  window.print();
</script>

</body>
</html>
