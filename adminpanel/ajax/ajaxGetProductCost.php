<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$value	=	$_REQUEST['value'];
$clicked_id = $_REQUEST['clicked_id'];
$roomdefaultvalue;
$product_cost	=	selectColumn(TBL_HOTELS,'product_cost'," WHERE `id` = '".addslashes($value)."'");
$serial_number_applicable  = selectColumn(TBL_HOTELS, 'serial_number_applicable',  " WHERE `id` = '".addslashes($value)."'");


$ListData=array();
$ListData['product_cost']=$product_cost;
$ListData['clicked_id']=$clicked_id;
$ListData['serial_number_applicable'] = $serial_number_applicable;

echo json_encode($ListData);	

?>