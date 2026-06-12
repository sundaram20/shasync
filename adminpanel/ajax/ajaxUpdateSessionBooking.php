<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////
$remove = $_REQUEST['remove'];
$uniqueCode = $_REQUEST['uniqueCode'];

if($remove == 'removeAll'){
echo "shafeer";
	unset($_SESSION['bookCart']);
	
 echo '<td colspan="8"><strong>Please add room again with new details.</strong></td>' ;
}else if(($remove == 'removeOne') && ($uniqueCode!='')){

	unset($_SESSION['bookCart']['dataValue'][$uniqueCode]);
	unset($_SESSION['bookCart']['tarrif_price'][$uniqueCode]);
	unset($_SESSION['bookCart']['room_quantity'][$uniqueCode]);
	unset($_SESSION['bookCart']['adult_no'][$uniqueCode]);
	unset($_SESSION['bookCart']['infant_no'][$uniqueCode]);
	unset($_SESSION['bookCart']['child_no'][$uniqueCode]);
	unset($_SESSION['bookCart']['room_price'][$uniqueCode]);
	unset($_SESSION['bookCart']['pkg_extra'][$uniqueCode]);
	unset($_SESSION['bookCart']['pkg_description'][$uniqueCode]);
	unset($_SESSION['bookCart']['inclusion_food'][$uniqueCode]);
	unset($_SESSION['bookCart']['room_tax_price'][$uniqueCode]);
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
	echo 'success|||';
	foreach($_SESSION['editCart']['dataValue'] as $uniqueCode =>$dataCode){			
		$totalAdult += $_SESSION['editCart']['adult_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalChild += $_SESSION['editCart']['child_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalInfant += $_SESSION['editCart']['infant_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
		$totalPrice +=  ($_SESSION['editCart']['tarrif'][$uniqueCode]+$_SESSION['editCart']['meal'][$uniqueCode])*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceTarrif +=  $_SESSION['editCart']['tarrif_price'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceFood +=  $_SESSION['editCart']['inclusion_food'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalPriceExtra +=  $_SESSION['editCart']['pkg_extra'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
		$totalRoom += $_SESSION['editCart']['room_quantity'][$uniqueCode];				
		$taxablePrice = $_SESSION['editCart']['room_tax_price'][$uniqueCode];
		
		
		
		$dataValue = explode('|',$_SESSION['editCart']['dataValue'][$uniqueCode]);
		$checkin_date = explode('to',$_SESSION['editCart']['reservation_date']); 
$Newcheckin	=	date("Y-m-d", strtotime($checkin_date['1']));
		$query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$_SESSION['shop']."'";

$result14 = executeSql($query14,$link);
$query14count = mysqli_num_rows($result14);	

$query14data 	= mysqli_fetch_array($result14);
$seasonIdnew	= $query14data['id'];	



echo "SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($_SESSION['editCart']['hotel_id'])."' and  `room_id` = '".addslashes($_SESSION['editCart']['room_type_id'][$uniqueCode])."' and  `seasonId` = '".addslashes($seasonIdnew)."'";


$resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($_SESSION['editCart']['hotel_id'])."' and  `room_id` = '".addslashes($_SESSION['editCart']['room_type_id'][$uniqueCode])."' and  `seasonId` = '".addslashes($seasonIdnew)."'");

$rowTax = $db->fetch_object2($resTax);
  $rowTax->tax_room;
echo "<br>Room price". $_SESSION['editCart']['room_price'][$uniqueCode];//*$_SESSION['editCart']['noOfDays']*($rowTax->tax_room/100);
					
					
					//$roomTax	+=	$rowOrderDetail->total_price*$row->no_of_days*($rowTax->tax_room/100);
					

		
		$roomTax	+=	round($_SESSION['editCart']['tarrif'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*$_SESSION['editCart']['room_quantity'][$uniqueCode]*($rowTax->tax_room/100));
		
			
		
}
echo '==>'.$roomTax;
$_SESSION['editCart']['charges_price'][$otherchagersid]	=$_REQUEST['charges_price'];

$_SESSION['editCart']['charges_total'][$otherchagersid]	=$_REQUEST['charges_total'];


$TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart']['charges_price']);
$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart']['charges_total']);

$_SESSION['editCart']['totalRoom']= $totalRoom;
$_SESSION['editCart']['totalAdult']= $totalAdult;
$_SESSION['editCart']['totalChild']= $totalChild;
$_SESSION['editCart']['totalInfant']= $totalInfant;
$_SESSION['editCart']['totalPrice'] = $totalPrice;
$_SESSION['editCart']['taxablePrice'] = $roomTax+$TotalAdditionalChargesTaxValue;
$_SESSION['editCart']['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart']['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart']['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart']['discountPrice'];
$_SESSION['editCart']['finalPrice']  = round((($_SESSION['editCart']['totalPrice']+$TotalAdditionalChargesPrice-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['taxablePrice']),0,PHP_ROUND_HALF_UP);
print_r($_SESSION);
echo '|||<table class="table" >
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"><i class="fa fa-inr"></i> '.round($_SESSION['editCart']['totalPrice'],2).'</td>
              </tr>
			   <tr>
                <th>Additional Charges:</th>
                <td id="addchargesvalue"><i class="fa fa-inr"></i>'.round($TotalAdditionalChargesPrice,2).'</td>
              </tr>
			  <tr>
                <th>Discount:</th>
                <td id="discount"><i class="fa fa-inr"></i> '.round($_SESSION['editCart']['discountPrice'],2).'</td>
              </tr>
              <tr>
                <th>Tax </th>
                <td id="tax"><i class="fa fa-inr"></i>  '.$_SESSION['editCart']['taxablePrice'].'</td>
              </tr>              
              <tr>
                <th>Total:</th>
                <td id="totalPrice"><i class="fa fa-inr"></i>  '.$_SESSION['editCart']['finalPrice'].'</td>
              </tr>
			  <tr>
                <th>Amount Received:</th>
                <td id="amountReceived" ><i class="fa fa-inr"></i>  '.round($_SESSION['editCart']['amountReceived'],2).'</td>
              </tr>
			  <tr>
                <th>Balance:</th>
                <td id="balance"><i class="fa fa-inr"></i> '.($_SESSION['editCart']['finalPrice']-$_SESSION['editCart']['amountReceived']).'</td>
              </tr>
            </table>';
}

?>