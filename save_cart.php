<?php
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['items'])) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak valid.']);
    exit;
}

file_put_contents('cart.txt', json_encode($data['items'], JSON_PRETTY_PRINT));

echo json_encode(['status' => 'success', 'message' => 'Keranjang disimpan!']);
?>
