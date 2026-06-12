<?php
include_once("../../config/auto_loader.php");

$id = addslashes($_POST['id']);
$shop = addslashes($_SESSION['shop']);

$sql = "SELECT * FROM fs_vendors WHERE id = '$id' AND id_shop = '$shop'";
$result = mysqli_query($connNew, $sql);
$row = mysqli_fetch_object($result);

echo json_encode([
    'id'          => $row->id,
    'name'        => $row->name,
    'mobile'      => $row->mobile,
    'email'       => $row->email,
    'vendor_type' => $row->vendor_type,
    'company_name'=> $row->company_name
]);
?>