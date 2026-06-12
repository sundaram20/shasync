<?php
include_once("../config/auto_loader.php");

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Invalid request'];

if (isset($_POST['id_product']) && !empty($_POST['id_product'])) {
    $productId = addslashes($_POST['id_product']);
    $sql = "SELECT cost, orginal_cost_active, serial_number_applicable 
            FROM fs_hotels 
            WHERE id = '$productId' AND id_shop = '" . addslashes($_SESSION['shop']) . "'";
    $result = mysqli_query($connNew, $sql);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        $response = [
            'status' => 'success',
            'cost' => $row['cost'],
            'orginal_cost_active' => $row['orginal_cost_active'],
            'serial_number_applicable' => $row['serial_number_applicable']
        ];
    } else {
        $response['message'] = 'Product not found';
    }
}

echo json_encode($response);
exit;
?>