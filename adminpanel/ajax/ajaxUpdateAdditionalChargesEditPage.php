<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////
 $OrderUniqueID	=$_REQUEST['OrderUniqueID'];


$reservation_date = explode(' to ',$_SESSION['editCart'][$OrderUniqueID]['reservation_date']);
$checkinDate = $reservation_date[0];
$checkoutDate = $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}


$charges_total 			= 	$_REQUEST['charges_total'];
$otherchagersid			=	$_REQUEST['otherchagersid'];

$_SESSION['editCart'][$OrderUniqueID]['otherChargeType'][$otherchagersid]			= $_REQUEST['otherChargeType'];	
$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$otherchagersid]	= $_REQUEST['otherChargeTypeNoOfDays'];



$otherChargeTypeNoOfDays=$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$otherchagersid];
$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$otherchagersid]	=$_REQUEST['charges_price'];//*$otherChargeTypeNoOfDays;

$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$otherchagersid]	=$_REQUEST['charges_total'];
$_SESSION['editCart'][$OrderUniqueID]['charges_description'][$otherchagersid]	=	$_REQUEST['charges_description'];
$_SESSION['editCart'][$OrderUniqueID]['charges_tax'][$otherchagersid]			=	$_REQUEST['charges_tax'];
$_SESSION['editCart'][$OrderUniqueID]['charges_net'][$otherchagersid]			=	$_REQUEST['charges_net'];

if(!empty($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'])){	
foreach($_SESSION['editCart'][$OrderUniqueID]['OtherChargesUniqueCodeID'] as $OtherUniceValue ){
	
	//$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	$TotalAdditionalChargesPrice	+=$_SESSION['editCart'][$OrderUniqueID]['charges_price'][$OtherUniceValue]*$_SESSION['editCart'][$OrderUniqueID]['otherChargeTypeNoOfDays'][$OtherUniceValue];
	
	$TotalAdditionalChargesTaxValue +=$_SESSION['editCart'][$OrderUniqueID]['charges_total'][$OtherUniceValue];
}
}
//TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart'][$OrderUniqueID]['charges_price']);
//$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart'][$OrderUniqueID]['charges_total']);


$discount 		= $_REQUEST['discount'];
$discountType 	= $_REQUEST['AdditionalCharges'];
$discountVar 	= $_REQUEST['AdditionalChargesVar'];
$addcharges_var = $_REQUEST['addcharges_var'];
$discountPrice 	= $_SESSION['editCart'][$OrderUniqueID]['discountPrice'];
$totalPrice 	= $_SESSION['editCart'][$OrderUniqueID]['totalPrice'];
$taxablePrice 	= $_SESSION['editCart'][$OrderUniqueID]['taxablePrice'];
$amountReceived = $_SESSION['editCart'][$OrderUniqueID]['amountReceived'];



if(($discount == 'apply') && ($otherchagersid!='') && ($charges_total!='') && (is_numeric($charges_total))){
				
				
			if($TotalAdditionalChargesPrice !='' && $TotalAdditionalChargesPrice !='0'){		
		
		    		
			$msg .= '<i class="fa fa-inr"></i>  '.($TotalAdditionalChargesPrice).'|||';
			$msg .= '<i class="fa fa-inr"></i> '.round((($totalPrice-$discountPrice)+$TotalAdditionalChargesTaxValue+$TotalAdditionalChargesPrice+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';
			$msg .= '<i class="fa fa-inr"></i> '.round((($taxablePrice+$TotalAdditionalChargesTaxValue)),0,PHP_ROUND_HALF_UP).'|||';			
			$msg .= '<i class="fa fa-inr"></i> '.round((($totalPrice-$discountPrice)+$TotalAdditionalChargesTaxValue+$TotalAdditionalChargesPrice+$taxablePrice-$amountReceived),0,PHP_ROUND_HALF_UP).'|||';
			$msg .= '<input type="text" class="form-control"  name="otherChargeTypeNoOfDays|'.$otherchagersid.'" id="otherChargeTypeNoOfDays|'.$otherchagersid.'" value="'.$otherChargeTypeNoOfDays.'"  autocomplete="off" onKeyUp="calculateOthercharge(\'otherChargeTypeNoOfDays|'.$otherchagersid.'\');" >###';
			$msg .= $otherchagersid;
							
			}else{
			$TotalAdditionalChargesPrice = 0;	
			$msg .= '<i class="fa fa-inr"></i>  '.$TotalAdditionalChargesPrice.'|||';
			$msg .= '<i class="fa fa-inr"></i> '.round((($totalPrice-$discountPrice)+$TotalAdditionalChargesTaxValue+$TotalAdditionalChargesPrice+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';
			$msg .= '<i class="fa fa-inr"></i> '.round((($taxablePrice+$TotalAdditionalChargesTaxValue)),0,PHP_ROUND_HALF_UP).'|||';			
			$msg .= '<i class="fa fa-inr"></i> '.round((($totalPrice-$discountPrice)+$TotalAdditionalChargesTaxValue+$TotalAdditionalChargesPrice+$taxablePrice-$amountReceived),0,PHP_ROUND_HALF_UP);				
			}
	
}





echo $msg;
?>