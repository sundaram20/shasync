<?php include_once("../../config/auto_loader.php");
//////////////////////////////////////executing query////////////////////////////////////////////////////
$dataValue = explode('|',$_REQUEST['dataValue']);
$uniqueCode = $_REQUEST['uniqueCode'];
$tarrif =  $_REQUEST['tarrif'];
$meal =  $_REQUEST['meal'];
$extra =  $_REQUEST['extra'];
$resRoom = executeSql("SELECT rt.name as room_name, rp.name as rate_name, rd.* from `".TBL_RATE_DETAILS."` rd left join `".TBL_ROOM_TYPE."` rt on rd.room_id = rt.id left join `".TBL_RATE_PLAN."` rp on rd.rate_plan_id = rp.id where rd.status='1' and rt.status='1' and rd.rate_assign_id='".addslashes($dataValue['5'])."'  and rd.rate_plan_id='".addslashes($dataValue['4'])."' and rd.rate_id='".addslashes($dataValue['3'])."' and room_id='".addslashes($dataValue['2'])."' order by rd.room_id");	


$ratePlan = executeSql("SELECT * FROM `".TBL_RATE_PLAN."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and id='".addslashes($dataValue['4'])."'");
$rowPlan = $db->fetch_object2($ratePlan);

if(num_rows($resRoom) >0){

$rowRoom = $db->fetch_object2($resRoom);
$priceValue = $tarrif+$meal+$extra;
$inclusionFood =$meal;
$pkg_extra = $extra;
/////////////////////////////////////making calculation////////////////////////////////////////////////

//////////////////////////////removing modified session////////////////////////////////////////////////////////
$_SESSION['bookCart']['room_price'][$uniqueCode] = $priceValue;
$_SESSION['bookCart']['inclusion_food'][$uniqueCode] = $inclusionFood;
$_SESSION['bookCart']['pkg_extra'][$uniqueCode] = $pkg_extra;
$_SESSION['bookCart']['tarrif_price'][$uniqueCode] = $tarrif;

$dataValue = explode('|',$_SESSION['bookCart']['dataValue'][$uniqueCode]);	
	
$seasonIdnew		=	selectColumn(TBL_RATE,'seasonId'," WHERE `id` = '".$_REQUEST['rate_id']."'");

//echo "<br>SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($_SESSION['bookCart']['hotel_id'])."' and  `room_id` = '".addslashes($dataValue['2'])."' and  `seasonId` = '".addslashes($seasonIdnew)."'";


$resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($_SESSION['bookCart']['hotel_id'])."' and  `room_id` = '".addslashes($dataValue['2'])."' and  `seasonId` = '".addslashes($seasonIdnew)."'");

$rowTax = $db->fetch_object2($resTax);
$_SESSION['bookCart']['room_price'][$uniqueCode]."*".$_SESSION['bookCart']['noOfDays'];
$rowTax->tax_room;


 $taxablePrice	+=	$_SESSION['bookCart']['room_price'][$uniqueCode]*$_SESSION['bookCart']['noOfDays']*($rowTax->tax_room/100);


/*if($rowPlan->tax_detail=='2'){
///////////////////////////tax configuration////////////////////////////////////////////////
	if(($_SESSION['bookCart']['tarrif_price'][$uniqueCode]) >7500){

		$resTax = executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and price_range='".addslashes('>7500')."'");
		$rowTax = $db->fetch_object2($resTax);
		
		if($rowTax->tax_detail=='1'){
		$_SESSION['bookCart']['room_tax_price'][$uniqueCode] =  round(($_SESSION['bookCart']['room_price'][$uniqueCode]*$_SESSION['bookCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}else{
		$_SESSION['bookCart']['room_tax_price'][$uniqueCode] =  round((($_SESSION['bookCart']['room_price'][$uniqueCode]+$_SESSION['bookCart']['inclusion_food'][$uniqueCode])*$_SESSION['bookCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}
	
		
		
	}else{
		
		$resTax = executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' and status='1' and price_range='".addslashes('<=7500')."'");
		$rowTax = $db->fetch_object2($resTax);		
		if($rowTax->tax_detail=='1'){
		$_SESSION['bookCart']['room_tax_price'][$uniqueCode] =  round(($_SESSION['bookCart']['room_price'][$uniqueCode]*$_SESSION['bookCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}else{
		
		$_SESSION['bookCart']['room_tax_price'][$uniqueCode] =  round((($_SESSION['bookCart']['room_price'][$uniqueCode]+$_SESSION['bookCart']['inclusion_food'][$uniqueCode])*$_SESSION['bookCart']['noOfDays']*($rowTax->tax_percent/100)),0,PHP_ROUND_HALF_UP);
		}
		
	}
	
///////////////////////////tax configuration////////////////////////////////////////////////
}else{
$_SESSION['bookCart']['room_tax_price'][$uniqueCode] = '0';

}*/





	unset($_SESSION['bookCart']['taxablePrice']);
	unset($_SESSION['bookCart']['discountType']);
	unset($_SESSION['bookCart']['discountVar']);
	unset($_SESSION['bookCart']['discountPrice']);
	unset($_SESSION['bookCart']['totalPrice']);
	unset($_SESSION['bookCart']['totalRoom']);
	unset($_SESSION['bookCart']['totalAdult']);
	unset($_SESSION['bookCart']['totalChild']);
	unset($_SESSION['bookCart']['totalInfant']);
	unset($_SESSION['bookCart']['totalPriceTarrif']);
	unset($_SESSION['bookCart']['totalPriceFood']);
	unset($_SESSION['bookCart']['totalPriceExtra']);
/////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<strong><i class="fa fa-inr"></i> '.$priceValue*$_SESSION['bookCart']['noOfDays'].'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span>';	  
}else {
echo 'Error.';

}
?>