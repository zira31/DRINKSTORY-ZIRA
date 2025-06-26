<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f0f0f0;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #fff;
      padding: 15px 30px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header h1 {
      margin: 0;
      font-size: 24px;
    }

    .logout-btn {
      background-color: #ff4d4d;
      color: white;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 6px;
      font-size: 14px;
      transition: background 0.3s ease;
    }

    .logout-btn:hover {
      background-color: #cc0000;
    }

    .dashboard {
      padding: 20px;
    }

    .chart-container, .report {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
      max-width: 1000px;
      margin-left: auto;
      margin-right: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f5b800;
      color: black;
    }

    .struk-btn {
      background-color: #008CBA;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 13px;
    }

    .struk-btn:hover {
      background-color: #0072a3;
    }

    .status-select {
      padding: 6px;
      border-radius: 5px;
      font-size: 13px;
    }

    .status-pending {
      background-color: #fff3cd;
    }

    .status-success {
      background-color: #d4edda;
    }
  </style>
</head>
<body>

<div class="header">
  <h1>Dashboard Admin</h1>
  <a href="homepage.html" class="logout-btn">Logout</a>
</div>

<div class="dashboard">
  <div class="chart-container">
    <canvas id="purchaseChart"></canvas>
  </div>

  <div class="report">
    <h2>Laporan Pembelian Masuk</h2>
    <table>
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Nama Produk</th>
          <th>Jumlah</th>
          <th>Total (Rp)</th>
          <th>Aksi</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
          <td><?= htmlspecialchars($row['item_name']) ?></td>
          <td><?= $row['quantity'] ?></td>
          <td><?= number_format($row['total_price'], 0, ',', '.') ?></td>
          <td>
            <form action="cetak_struk.php" method="POST" target="_blank" style="display:inline;">
              <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
              <button type="submit" class="struk-btn">Cetak Struk</button>
            </form>
          </td>
          <td>
            <select class="status-select <?= $row['status'] == 'Success' ? 'status-success' : 'status-pending' ?>"
                    onchange="updateStatus(this, <?= $row['id'] ?>)">
              <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
              <option value="Success" <?= $row['status'] == 'Success' ? 'selected' : '' ?>>Success</option>
            </select>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
  // Chart Grafik
  fetch('data_grafik.php')
    .then(res => res.json())
    .then(data => {
      new Chart(document.getElementById('purchaseChart').getContext('2d'), {
        type: 'bar',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
          datasets: [{
            label: 'Total Pembelian (Rp)',
            data: data,
            backgroundColor: '#f5b800'
          }]
        },
        options: {
          responsive: true,
          plugins: {
            title: {
              display: true,
              text: 'Grafik Pembelian per Bulan'
            }
          }
        }
      });
    });

  // AJAX untuk ubah status
  function updateStatus(selectElement, orderId) {
    const newStatus = selectElement.value;
    fetch('update_status.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `id=${orderId}&status=${newStatus}`
    })
    .then(response => response.text())
    .then(result => {
      if (newStatus === 'Success') {
        selectElement.classList.remove('status-pending');
        selectElement.classList.add('status-success');
      } else {
        selectElement.classList.remove('status-success');
        selectElement.classList.add('status-pending');
      }
    })
    .catch(error => console.error('Gagal update status:', error));
  }
</script>

</body>
</html>
