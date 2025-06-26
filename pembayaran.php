<?php
include 'koneksi.php';

// Ambil data dari tabel cart
$sql = "SELECT item_name, item_price, quantity FROM cart";
$result = $conn->query($sql);

$total = 0;
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $total += $row['item_price'] * $row['quantity'];
}

// Jika tombol bayar ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "SELECT item_name, item_price, quantity FROM cart";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = $row['item_name'];
            $price = $row['item_price'];
            $qty = $row['quantity'];
            $total_harga = $price * $qty;

            // Simpan ke tabel orders
            $stmt = $conn->prepare("INSERT INTO orders (item_name, quantity, total_price) VALUES (?, ?, ?)");
            $stmt->bind_param("sii", $name, $qty, $total_harga);
            $stmt->execute();
        }

        // Kosongkan keranjang
        $conn->query("DELETE FROM cart");

        // Redirect ke halaman sukses
        header("Location: sukses.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pembayaran</title>
  <link rel="stylesheet" href="pembayaran.css">
</head>
<body>
  <div class="app-container">
    <div class="header">
      <a href="menu.html" class="back">&#8592; Kembali</a>
      <h2>Pembayaran</h2>
    </div>

    <div class="content">
      <!-- Info Pemesan -->
      <div class="section">
        <div class="section-row">
          <span class="label">Nama Pemesan</span>
          <span class="value">Customer</span>
        </div>
        <div class="section-row">
          <span class="label">Waktu</span>
          <span class="value" id="local-time">Memuat waktu...</span>
        </div>
      </div>

      <!-- Daftar Item -->
      <div class="items-section">
        <div class="items-header">
          <span>Item</span>
          <span>Harga</span>
        </div>
        <?php if (count($items)): ?>
          <?php foreach ($items as $item): ?>
            <div class="item">
              <div class="item-desc">
                <?= htmlspecialchars($item['item_name']) ?> x <?= $item['quantity'] ?>
              </div>
              <div class="item-price">
                Rp <?= number_format($item['item_price'] * $item['quantity'], 0, ',', '.') ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Keranjang kosong.</p>
        <?php endif; ?>
      </div>

      <!-- Total dan Tombol Bayar -->
      <div class="summary">
        <div class="row">
          <span>Total</span>
          <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
        </div>
        <?php if ($total > 0): ?>
          <form method="POST">
            <button type="submit" class="place-order">Bayar Sekarang</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Script Waktu -->
  <script>
    function updateLocalTime() {
      const now = new Date();
      const jam = now.getHours().toString().padStart(2, '0');
      const menit = now.getMinutes().toString().padStart(2, '0');
      const tanggal = now.getDate().toString().padStart(2, '0');
      const bulan = (now.getMonth() + 1).toString().padStart(2, '0');
      const tahun = now.getFullYear();
      const waktu = `${jam}:${menit} ${tanggal}/${bulan}/${tahun}`;
      document.getElementById("local-time").innerText = waktu;
    }

    updateLocalTime();
    setInterval(updateLocalTime, 60000);
  </script>
</body>
</html>
