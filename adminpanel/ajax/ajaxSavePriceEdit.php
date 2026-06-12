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
$pkg_extra = $extra ;
/////////////////////////////////////making calculation////////////////////////////////////////////////

//////////////////////////////removing modified session////////////////////////////////////////////////////////
$_SESSION['editCart']['room_price'][$uniqueCode] = $priceValue*$_SESSION['editCart']['room_quantity'][$uniqueCode];
$_SESSION['editCart']['inclusion_food'][$uniqueCode] = $inclusionFood;
$_SESSION['editCart']['pkg_extra'][$uniqueCode] = $pkg_extra;
$_SESSION['editCart']['tarrif_price'][$uniqueCode] = $tarrif;




/////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '||| |||<strong><i class="fa fa-inr"></i>'.$priceValue*$_SESSION['editCart']['noOfDays']*$_SESSION['editCart']['room_quantity'][$uniqueCode].'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span>';	  
}else {
	
$priceValue = $tarrif+$meal+$extra;
$inclusionFood =$meal;
$pkg_extra = $extra ;
/////////////////////////////////////making calculation////////////////////////////////////////////////

//////////////////////////////removing modified session////////////////////////////////////////////////////////
$_SESSION['editCart']['room_price'][$uniqueCode] = $priceValue*$_SESSION['editCart']['room_quantity'][$uniqueCode];
$_SESSION['editCart']['inclusion_food'][$uniqueCode] = $inclusionFood;
$_SESSION['editCart']['pkg_extra'][$uniqueCode] = $pkg_extra;
$_SESSION['editCart']['tarrif_price'][$uniqueCode] = $tarrif;



echo '||| |||<strong><i class="fa fa-inr"></i> '.$priceValue*$_SESSION['editCart']['noOfDays']*$_SESSION['editCart']['room_quantity'][$uniqueCode].'</strong>&nbsp;&nbsp;<span class="pricePopUp_open" onclick="pricePopUp(this.id);" id="pricePopUp_'.$uniqueCode.'" ><i class="fa fa-pencil"></i></span>';	 

}
/*echo "<pre>";
print_r($_SESSION);
print_r($_REQUEST);
echo "</pre>";*/
foreach($_SESSION['editCart']['dataValue'] as $uniqueCode =>$dataCode){		
	
$totalAdult += $_SESSION['editCart']['adult_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
$totalChild += $_SESSION['editCart']['child_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
$totalInfant += $_SESSION['editCart']['infant_no'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode];
$totalPrice +=  $_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
$totalPriceTarrif +=  $_SESSION['editCart']['tarrif_price'][$uniqueCode]*$_SESSION['editCart']['room_quantity'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
$totalPriceFood +=  $_SESSION['editCart']['inclusion_food'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
$totalPriceExtra +=  $_SESSION['editCart']['pkg_extra'][$uniqueCode]*$_SESSION['editCart']['noOfDays'];
$totalRoom += $_SESSION['editCart']['room_quantity'][$uniqueCode];				
$taxablePrice += $_SESSION['editCart']['room_tax_price'][$uniqueCode];
		
$dataValue = explode('|',$_SESSION['editCart']['dataValue'][$uniqueCode]);
//print_r($_REQUEST);


//$Newcheckin = $_SESSION['editCart']['Newcheckin'];
$checkin_date = explode('to',$_SESSION['editCart']['reservation_date']); 
$Newcheckin	=	date("Y-m-d", strtotime($checkin_date['1']));

 $query14	=	"SELECT * from `".TBL_RATE_SEASON."` WHERE ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$_SESSION['shop']."'";

$result14 = executeSql($query14,$link);
$query14count = mysqli_num_rows($result14);	

$query14data = mysqli_fetch_array($result14);
$seasonIdnew	= $query14data['id'];	


$resTax= executeSql("SELECT * FROM `".TBL_TAX_CONFIGURATION_TWO."` where id_shop='".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($dataValue['1'])."' and  `room_id` = '".addslashes($dataValue['2'])."' and  `seasonId` = '".addslashes($seasonIdnew)."'");

$rowTax = $db->fetch_object2($resTax);
 $rowTax->tax_room;
//echo "<br>Room price". $_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_room/100);
					
					
					//$roomTax	+=	$rowOrderDetail->total_price*$row->no_of_days*($rowTax->tax_room/100);
					

		
		$roomTax	+=	round($_SESSION['editCart']['room_price'][$uniqueCode]*$_SESSION['editCart']['noOfDays']*($rowTax->tax_room/100));
		
}

$_SESSION['editCart']['charges_price'][$uniqueCode]	=$_REQUEST['charges_price'];

$_SESSION['editCart']['charges_total'][$uniqueCode]	=$_REQUEST['charges_total'];


$TotalAdditionalChargesPrice	=	array_sum($_SESSION['editCart']['charges_price']);
$TotalAdditionalChargesTaxValue	=	array_sum($_SESSION['editCart']['charges_total']);

$_SESSION['editCart']['totalRoom']= $totalRoom;
$_SESSION['editCart']['totalAdult']= $totalAdult;
$_SESSION['editCart']['totalChild']= $totalChild;
$_SESSION['editCart']['totalInfant']= $totalInfant;
$_SESSION['editCart']['totalPrice'] = $totalPrice;
$_SESSION['editCart']['taxablePrice'] = $roomTax;
$_SESSION['editCart']['totalPriceTarrif'] = $totalPriceTarrif;
$_SESSION['editCart']['totalPriceFood'] = $totalPriceFood;
$_SESSION['editCart']['totalPriceExtra'] = $totalPriceExtra;
$_SESSION['editCart']['discountPrice'];
$_SESSION['editCart']['finalPrice']  = round((($_SESSION['editCart']['totalPrice']+$TotalAdditionalChargesPrice-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['taxablePrice']),0,PHP_ROUND_HALF_UP);

echo '|||<table class="table" >
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"><i class="fa fa-inr"></i>'.$_SESSION['editCart']['totalPrice'].'</td>
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
                <td id="tax"><i class="fa fa-inr"></i>  '.($_SESSION['editCart']['taxablePrice']+$TotalAdditionalChargesTaxValue).'</td>
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
?>