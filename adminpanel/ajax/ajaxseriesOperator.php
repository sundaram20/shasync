<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$seriesId=$_REQUEST['seriesId'];
$operatorId=$_REQUEST['operatorId'];

if($seriesId!='' && $operatorId !=''){
$resOrder = executeSql("SELECT * from `".TBL_ORDERS."` where series_id='".addslashes($seriesId)."' and operator_id='".addslashes($operatorId)."' and type='S' order by id_order desc limit 0,1");
if(num_rows($resOrder) > 0){
	$rowOrder = $db->fetch_object2($resOrder);	
		echo $companyId = $rowOrder->id_company.'|||';
		echo $companyperson = $rowOrder->id_company_person.'|||';
		echo $guestId = $rowOrder->id_customer.'|||';		
	}else {
	echo '|||||||||';
	}
}else{
echo '|||||||||';

}
?>