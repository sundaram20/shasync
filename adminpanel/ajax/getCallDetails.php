<?php
include_once("../config/auto_loader.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Invalid or missing ID']);
    exit;
}

$id = (int)$_GET['id'];
$stmt = mysqli_prepare($connNew, "SELECT * FROM `call` WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo json_encode(['error' => 'No record found']);
    exit;
}

$response = [
    'name' => $row['name'] ?? '',
    'mobile' => $row['mobile'] ?? '',
    'email' => $row['email'] ?? '',
    'extra' => !empty($row['extra_data']) ? json_decode($row['extra_data'], true) : []
];

echo json_encode($response);
mysqli_stmt_close($stmt);
?>