<?php  include_once("../../config/auto_loader.php");

include_once("../includes/incentiveFunctions.php");


$enquiry_id				   =	$_REQUEST['enquiry_id'];
 

$user_id				   =	$_SESSION['userId'];

	ClaimIncentiveSave($_REQUEST['id_hotel_inc'],$_REQUEST['guest_name_inc'],$_REQUEST['checkin_inc'],$_REQUEST['checkout_inc'],$_REQUEST['no_room_inc'],$_REQUEST['no_pax_inc'],$_REQUEST['room_rate_inc'],$_REQUEST['banquet_revenue_amount_inc'],$_REQUEST['revenue_inc'],$_REQUEST['id_forward_for_approval'],$connNew,$user_id,$enquiry_id,$_REQUEST['remarks_inc'],$_REQUEST['query_type']);                     
	
	


$edit= addslashes(encryptor('encrypt',$enquiry_id));


echo '<p class="help-block"> Your Incentive has been  Sucessfully updated.<br>Please Wait...</p><script>window.setTimeout(function() {window.location.href = "editEnquiry.php?action=edit&eId='.$edit.'";}, 2000);</script>';




?>
