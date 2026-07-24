<?php
include_once("../../config/auto_loader.php");

header('Content-Type: application/json');

$id_shop = $_SESSION['shop'];
$type    = $_GET['type']  ?? '';
$value   = trim($_GET['value'] ?? '');

$response = ['found' => false];

if ($id_shop && $value !== '' && in_array($type, ['company', 'serial'], true)) {

    $column = ($type === 'company') ? 's.id_company' : 's.serial';

    $baseSql = "SELECT s.*,
                       c.name AS company_name,
                       CONCAT(cu.title,' ',cu.first_name,' ',cu.last_name) AS contact_name,
                       p.name AS product_name
                FROM support s
                LEFT JOIN " . TBL_COMPANY . " c ON c.id_company = s.id_company
                LEFT JOIN " . TBL_CUSTOMER . " cu ON cu.id_customer = s.id_contacts
                LEFT JOIN fs_hotels p ON p.id = s.id_product
                WHERE s.id_shop = ? AND $column = ?
                ORDER BY s.id DESC
                LIMIT 1";

    $stmt = mysqli_prepare($connNew, $baseSql);

    if ($stmt) {
        if ($type === 'company') {
            mysqli_stmt_bind_param($stmt, 'ii', $id_shop, $value);
        } else {
            mysqli_stmt_bind_param($stmt, 'ii', $id_shop, $value);
        }

        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $response = [
                'found'        => true,
                'id_company'   => $row['id_company'],
                'company_name' => $row['company_name'],
                'id_contacts'  => $row['id_contacts'],
                'contact_name' => trim($row['contact_name']),
                'id_product'   => $row['id_product'],
                'product_name' => $row['product_name'],
                'serial'       => $row['serial'],
            ];
        }
        mysqli_stmt_close($stmt);
    } else {
        // Temporary debug line — remove once confirmed working
        $response['sql_error'] = mysqli_error($connNew);
    }
}

echo json_encode($response);