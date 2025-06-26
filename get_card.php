<?php
session_start();
header('Content-Type: application/json');

$mysqli = new mysqli('localhost', 'root', '', 'drinkstory');
if ($mysqli->connect_error) {
    echo json_encode([]);
    exit;
}

$session_id = session_id();
$result = $mysqli->query("SELECT item_name, item_price FROM cart WHERE session_id = '$session_id'");

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode($items);
?>
