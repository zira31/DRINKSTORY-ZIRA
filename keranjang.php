<?php
include 'koneksi.php';

// Tangani aksi tambah/kurang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);
  if (isset($_POST['tambah'])) {
    $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE id = $id");
  } elseif (isset($_POST['kurang'])) {
    $conn->query("UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE id = $id");
  }
}

// Ambil data terbaru
$result = $conn->query("SELECT id, item_name, item_price, quantity FROM cart");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang</title>
  <link rel="stylesheet" href="keranjang.css">
  <style>
    .back-button {
      display: inline-block;
      margin-bottom: 20px;
      background-color: #333;
      color: white;
      padding: 8px 12px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
    }

    .back-button:hover {
      background-color: #555;
    }

    .cart-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px solid #ddd;
    }

    .quantity-form {
      display: flex;
      gap: 5px;
      align-items: center;
    }

    .quantity-btn {
      background-color: #f5b800;
      border: none;
      padding: 4px 10px;
      font-size: 14px;
      border-radius: 4px;
      cursor: pointer;
    }

    .hapus {
      background-color: #ff4d4d;
      color: white;
      border: none;
      padding: 4px 8px;
      border-radius: 4px;
      cursor: pointer;
    }

    .total {
      margin-top: 20px;
      font-size: 18px;
      text-align: right;
    }

    .checkout {
      display: inline-block;
      margin-top: 15px;
      background-color: #28a745;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 16px;
      border: none;
      cursor: pointer;
    }

    .checkout:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="menu.html" class="back-button">← Kembali ke Menu</a>
    <h1>Keranjang Belanja</h1>

    <?php if ($result->num_rows > 0): ?>
      <ul class="cart-list">
        <?php
          $total = 0;
          $hasValidItem = false;
          while ($row = $result->fetch_assoc()):
            $subtotal = $row['item_price'] * $row['quantity'];
            $total += $subtotal;
            if ($row['quantity'] > 0) {
              $hasValidItem = true;
            }
        ?>
          <li class="cart-item">
            <div>
              <strong><?= htmlspecialchars($row['item_name']) ?></strong><br>
              Rp <?= number_format($row['item_price'], 0, ',', '.') ?> x <?= $row['quantity'] ?> =
              <strong>Rp <?= number_format($subtotal, 0, ',', '.') ?></strong>
            </div>
            <div class="quantity-form">
              <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="kurang" class="quantity-btn">−</button>
              </form>

              <form method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="tambah" class="quantity-btn">+</button>
              </form>

              <form method="post" action="hapus_dari_keranjang.php" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="hapus">Hapus</button>
              </form>
            </div>
          </li>
        <?php endwhile; ?>
      </ul>

      <div class="total">
        Total: <strong>Rp <?= number_format($total, 0, ',', '.') ?></strong>
      </div>

      <?php if ($total > 0 && $hasValidItem): ?>
        <form method="POST" action="pembayaran.php" onsubmit="return validateCheckout();">
          <button type="submit" class="checkout" id="checkoutButton">Bayar Sekarang</button>
        </form>
      <?php else: ?>
        <button class="checkout" onclick="alert('Keranjang belum memiliki item dengan jumlah yang valid.'); return false;">Bayar Sekarang</button>
      <?php endif; ?>

    <?php else: ?>
      <p>Keranjang kosong.</p>
      <button class="checkout" onclick="alert('Keranjang kosong. Tambahkan item terlebih dahulu.'); return false;">Bayar Sekarang</button>
    <?php endif; ?>
  </div>

  <script>
    function validateCheckout() {
      const cartItems = document.querySelectorAll('.cart-item');
      if (cartItems.length === 0) {
        alert('Keranjang kosong.');
        return false;
      }

      let valid = false;
      cartItems.forEach(item => {
        const quantityMatch = item.innerText.match(/x\s(\d+)/);
        if (quantityMatch && parseInt(quantityMatch[1]) > 0) {
          valid = true;
        }
      });

      if (!valid) {
        alert('Pastikan semua item memiliki jumlah minimal 1.');
        return false;
      }

      return true;
    }
  </script>
</body>
</html>