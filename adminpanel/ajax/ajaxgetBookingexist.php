<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkin_date = $reservation_date[0];
$checkout_date = $reservation_date[1];
$id_company = $_REQUEST['id_company'];
$rate_id = 	$_REQUEST['rate_id'];	
							


$resSql = executeSql("SELECT * from `".TBL_COMPANY."` where status='1' and id_company='".addslashes($id_company)."' ");
$rowresult = $db->fetch_object2($resSql);

			$company_credibility	=	$rowresult->company_credibility;
			
			
			if($company_credibility==1){
			
			echo "Credit Allowed";
			
			}if($company_credibility==2){
			
			echo "Credit Not Allowed";
			
			}
?>