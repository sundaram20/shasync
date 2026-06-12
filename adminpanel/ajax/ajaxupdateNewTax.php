<?php include_once("../../config/auto_loader.php");
//////////////////////////////////////executing query////////////////////////////////////////////////////


$OrderID	=	addslashes(encryptor('decrypt',$_SESSION['eId']));

$sqlOrderDetail = executeSql("Select * from `".TBL_ORDER_DETAIL."` where id_order='".addslashes($OrderID)."'");
			if(num_rows($sqlOrderDetail) >0 ){
			
			while($rowOrderDetail= $db->fetch_object2($sqlOrderDetail)){
				
				$SelectTaxDateSQL		= executeSql("SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  order by start_date desc");
		$SelectTaxDateRow 		= $db->fetch_object2($SelectTaxDateSQL);
		$SlectedDateNewTax_id	= $SelectTaxDateRow->id;		
		$uniqueCodeRequest		= $_REQUEST['uniqueCode'];
		
		$price 					= ($rowOrderDetail->total_price/$rowOrderDetail->room_quantity);
		
		$resNewTax= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price."' and tax_slabs_to  >= '".$price."') OR ( tax_slabs_from between '".$price."' and '".$price."') OR ( tax_slabs_to between '".$price."' and '".$price."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
		
		if(num_rows($resNewTax) >0 ){
				$rowNewTax = $db->fetch_object2($resNewTax);
		
	
				
		$TaxInclusiveStatus1	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$rowOrderDetail->rate_plan_id."'");
		
		$no_of_days	= selectColumn(TBL_ORDERS,'no_of_days'," WHERE `id_order` = '".addslashes($OrderID)."'"); 
			if($TaxInclusiveStatus1	== '2' ){
				
					
				$Tprice	=	$rowOrderDetail->total_price/$rowOrderDetail->room_quantity;
				$roomTax	=	(($Tprice)*($rowNewTax->tax_percent/100));
				
				
				$rowNewTax->tax_percent;
				$TotalTax	+=	(($Tprice*$rowOrderDetail->room_quantity)*($rowNewTax->tax_percent/100));
				
				 $insertOrder = "UPDATE `".TBL_ORDER_DETAIL."` SET 	
				`tax_perday_perroom`='".addslashes($roomTax)."'
				 where id_order='".$OrderID."' and id_order_detail='".$rowOrderDetail->id_order_detail."'";
				 executeSql($insertOrder);
				 
				 
			}
				
			
		
			
				
		}
		
	
				
				
				
				
				
					
				}
				
				$subtotal	= selectColumn(TBL_ORDERS,'subtotal'," WHERE `id_order` = '".addslashes($OrderID)."'"); 
				$total_addcharges	= selectColumn(TBL_ORDERS,'total_addcharges'," WHERE `id_order` = '".addslashes($OrderID)."'"); 
				$total_discounts	= selectColumn(TBL_ORDERS,'total_discounts'," WHERE `id_order` = '".addslashes($OrderID)."'"); 
				
				$amount_received	= selectColumn(TBL_ORDERS,'amount_received'," WHERE `id_order` = '".addslashes($OrderID)."'"); 
				
				$total_price	=($subtotal+$total_addcharges+$TotalTax-$total_discounts);
				
				$balance	= $total_price-$amount_received;
				
				 $insertOrder = "UPDATE `".TBL_ORDERS."` SET 	
				`tax_group_id`='0',`total_tax`='".$TotalTax."',`total_price`='".$total_price."',`amount_received`='".$amount_received."',`balance`='".$balance."'
				 where id_order='".$OrderID."'";
				 executeSql($insertOrder);
			}



 echo  '<p class="help-block">Tax value has been updated sucessfully</p><script>window.setTimeout(function() {window.location.href = "editOrders.php?eId='.$_SESSION['eId'].'&action=edit";}, 2000);</script>';
?>