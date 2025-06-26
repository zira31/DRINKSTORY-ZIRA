<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transaksi Berhasil</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: sans-serif;
      background: #f5b800;
      text-align: center;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 20px;
    }

    .msg {
      font-size: 26px;
      margin-bottom: 20px;
      animation: fadeInUp 1s ease-out forwards;
      opacity: 0;
    }

    @keyframes fadeInUp {
      0% {
        transform: translateY(30px);
        opacity: 0;
      }
      100% {
        transform: translateY(0);
        opacity: 1;
      }
    }

    a {
      display: inline-block;
      background: black;
      color: white;
      padding: 12px 24px;
      text-decoration: none;
      border-radius: 6px;
      font-size: 16px;
      animation: fadeInUp 1s ease-out 1s forwards;
      opacity: 0;
    }

    @media (max-width: 400px) {
      .msg {
        font-size: 20px;
      }

      a {
        padding: 12px 20px;
        font-size: 14px;
        width: 100%;
        max-width: 300px;
      }
    }
  </style>
  <script>
    setTimeout(() => {
      window.location.href = "menu.html";
    }, 3000); // 3 detik
  </script>
</head>
<body>
  <div class="msg">🎉 Pembayaran Berhasil!</div>
</body>
</html>
