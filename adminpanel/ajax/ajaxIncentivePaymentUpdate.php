<?php  include_once("../../config/auto_loader.php");


/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/
$id_incentive		     =	$_REQUEST['id_incentive'];
$payment_status				   		   =	$_REQUEST['payment_status'];
$remarks  =	$_REQUEST['remarks'];


	
	
		
	$sqlNotify = "UPDATE ".TBL_INCENTIVE." SET					
					`payment_status`='".$payment_status."',
					`payment_remarks`='".$remarks."'
					";

		
		$sqlNotify .="
					WHERE `id`='".$id_incentive."'";
					
	mysqli_query($connNew,$sqlNotify);

	$enquiry_id=selectColumn(TBL_INCENTIVE,'id_enquiry',"WHERE  id = '".$id_incentive."' ");
	$eId=encryptor('encrypt',$enquiry_id);

echo '<p class="help-block">Incentive Payment Status is Updated.</p><script>window.setTimeout(function() {window.location.href = "manageIncentive.php?action=edit&eId='.$eId.'";}, 2000);</script>';



?>
