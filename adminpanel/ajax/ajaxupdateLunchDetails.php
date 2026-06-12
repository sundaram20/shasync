<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////

$price 			= $_REQUEST['price'];
$adults 		= $_REQUEST['adults'];
$taxperpack 	= $_REQUEST['taxperpack'];
$tax_percentage = $_REQUEST['tax_percentage'];
$meals_type		= $_REQUEST['meals_type'];

$TotalTax	=	($price*$tax_percentage)/100;
if($price!='' && (is_numeric($price))){
$_SESSION['editCart']['totalRoom']= '0';
$_SESSION['editCart']['totalAdult'] = $adults;
$_SESSION['editCart']['totalPrice'] = $price*$adults;
$_SESSION['editCart']['taxablePrice'] = round(($adults*$TotalTax),0,PHP_ROUND_HALF_UP);
$_SESSION['editCart']['totalPriceTarrif'] = $price;
$_SESSION['editCart']['totalPriceFood'] = $price;
$_SESSION['editCart']['lunch_type'] = $meals_type;
$_SESSION['editCart']['lunch_tax_percentage'] = $tax_percentage;



//$_SESSION['editCart']['totalPriceExtra'] = '0';
//$_SESSION['editCart']['discountPrice'] = 0;
$_SESSION['editCart']['finalPrice']  = round((($_SESSION['editCart']['totalPrice']-$_SESSION['editCart']['discountPrice'])+$_SESSION['editCart']['taxablePrice']),0,PHP_ROUND_HALF_UP);

echo '<table class="table" >
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td id="subtotal"><i class="fa fa-inr"></i> '.$_SESSION['editCart']['totalPrice'].'</td>
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