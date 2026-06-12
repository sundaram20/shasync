<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////
$discount = $_REQUEST['discount'];
$discountType = $_REQUEST['discountType'];
$discountVar = $_REQUEST['discountVar'];
$totalPrice = $_SESSION['bookCart']['totalPrice'];
$taxablePrice = $_SESSION['bookCart']['taxablePrice'];
if(($discount == 'apply') && ($discountType!='') && ($discountVar!='') && (is_numeric($discountVar)) && (is_numeric($discountType))){
	if($discountType == '1'){			
		if($discountVar <= $totalPrice){
		    $discount_price = $discountVar;			
			$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
			$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';			
			$msg .= '';			
		}else {
			$discount_price = 0;
			$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
			$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';			
			$msg .= '<font color="#d73925" >Please enter discount less than '.$totalPrice.'.</font>';
		}		
	}
	else if($discountType == '2'){	
	  if($discountVar <= '100'){		
		$discount_price = round($totalPrice*($discountVar/100),0,PHP_ROUND_HALF_UP);
		$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
		$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';
		$msg .= '|||';
		$msg .= '<i class="fa fa-inr"></i> ';
		}else {
			$discount_price = 0;			
			$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
			$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';
			$msg .= '<font color="#d73925" >Please enter percent less than 100.</font>|||';
		}
	}else {
			$discount_price = 0;			
			$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
			$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';
			$msg .= '<font color="#d73925" >Please enter proper value.</font>|||';
	}	
}else{
$discount_price = 0;			
$msg .= '<i class="fa fa-inr"></i>  '.$discount_price.'|||';
$msg .= '<i class="fa fa-inr"></i>  '.round((($totalPrice-$discount_price)+$taxablePrice),0,PHP_ROUND_HALF_UP).'|||';
$msg .= '<font color="#d73925" >Please enter proper value.</font>|||';

}
$_SESSION['bookCart']['discountType'] = $discountType;
$_SESSION['bookCart']['discountVar'] = $discountVar;
$_SESSION['bookCart']['discountPrice'] = $discount_price;
			




echo $msg;
?>