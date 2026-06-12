<?php
include_once("../../config/auto_loader.php");

$format_type = $_GET['format_type'] ?? '';

$response = [];

if ($format_type !== '') {
    $sql = "SELECT id, name FROM call_list_name WHERE format_type = '".addslashes($format_type)."' AND status = 1 ORDER BY name ASC";
    $query = mysqli_query($connNew, $sql);
    
    while ($row = mysqli_fetch_assoc($query)) {
        $response[] = [
            'id' => $row['id'],
            'name' => $row['name']
        ];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
