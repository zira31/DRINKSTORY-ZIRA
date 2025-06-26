<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'];
$price = (int)$data['price'];

$conn = new mysqli("localhost", "root", "", "drinkstory");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO cart (item_name, item_price) VALUES (?, ?)");
$stmt->bind_param("si", $name, $price);
if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
$conn->close();
?>
