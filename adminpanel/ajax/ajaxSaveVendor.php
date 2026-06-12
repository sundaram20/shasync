<?php
include_once("../../config/auto_loader.php");

$response = [];

$name       = addslashes($_POST['vendor_name']);
$mobile     = addslashes($_POST['vendor_mobile']);
$email      = addslashes($_POST['vendor_email']);
$type       = addslashes($_POST['vendor_type']);
$id_company = addslashes($_POST['vendor_company']);
$shop       = addslashes($_SESSION['shop']);
$editId     = addslashes($_POST['EditVendorID']);

if(!empty($editId)){
    $sql = "UPDATE fs_vendors SET 
            name='$name', mobile='$mobile', email='$email',
            vendor_type='$type', company_name='$id_company',
            last_modified='".currenDateTime()."'
            WHERE id='$editId' AND id_shop='$shop'";
} else {
    $sql = "INSERT INTO fs_vendors SET
            name='$name', mobile='$mobile', email='$email',
            vendor_type='$type', company_name='$id_company',
            id_shop='$shop', status='1',
            date_created='".currenDateTime()."',
            last_modified='".currenDateTime()."'";
}

if(executeSql($sql)){
    $id = !empty($editId) ? $editId : $db->insert_id();
    $response = ['status' => 1, 'id' => $id, 'name' => $name, 'message' => 'Saved successfully'];
} else {
    $response = ['status' => 0, 'message' => 'Failed to save vendor'];
}

echo json_encode($response);
?>