<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

debugData($_REQUEST);die;
$value	=	$_REQUEST['value'];
$clicked_id = $_REQUEST['clicked_id'];
$roomdefaultvalue;
$product_cost	=	selectColumn(TBL_HOTELS,'product_cost'," WHERE `id` = '".addslashes($value)."'");


$ListData=array();
$ListData['product_cost']=$product_cost;
$ListData['clicked_id']=$clicked_id;

echo json_encode($ListData);	

?>