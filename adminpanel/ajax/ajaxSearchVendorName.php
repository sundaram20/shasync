<?php
include_once("../../config/auto_loader.php");
$search = addslashes($_REQUEST['term']);
$shop = addslashes($_SESSION['shop']);

$sql = "SELECT id, company_name, name, email FROM fs_vendors 
        WHERE id_shop = '$shop' AND status = 1 
        AND name LIKE '%$search%' 
        ORDER BY name LIMIT 20";

$result = mysqli_query($connNew, $sql);
$data = [];

while($row = mysqli_fetch_object($result)){
    $displayText = $row->name;
    if(!empty($row->company_name)) $displayText .= ' | ' . $row->company_name;
    if(!empty($row->email)) $displayText .= ' | ' . $row->email;
    
    $data[] = [
        'id'   => $row->id,
        'text' => $displayText
    ];
}

if(empty($data)){
    $data[] = ['id' => 0, 'text' => 'No vendors found'];
}

echo json_encode($data);
?>