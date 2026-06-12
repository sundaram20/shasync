<?php
include_once("../../config/auto_loader.php");


$sql= "SELECT * FROM ".TBL_DAILY_ENQUERY." WHERE id_shop='".$_SESSION['shop']."' AND id='".$_REQUEST['id']."' ";

$count = $_REQUEST['count']+1;

$res = mysqli_query($connNew,$sql);

if($res){
	$objData = mysqli_fetch_object($res);

	$companyName = selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$objData->id_company.'" ');

	$contactHotel = selectColumn(TBL_HOTELS,'name','WHERE id="'.$objData->hotel_id.'" ');

	echo "<tr>
		<td>".$count.".</td>
		<td>".($companyName==''?'Not Applicable':$companyName)."</td>
		<td>".($contactHotel==''?'Not Applicable':$contactHotel)."</td>
	     </tr>";

	unset($_SESSION['followup_hotel_id']); 
	unset($_SESSION['followup_description']); 
	unset($_SESSION['followup_date']); 
	unset($_SESSION['followupCode']); 
	unset($_SESSION['followupstatus']); 
	unset($_SESSION['feedback_hotel_id']); 
	unset($_SESSION['feedback_description']); 
	unset($_SESSION['assign_followup_user_id']);
	unset($_SESSION['feedback_date']); 
	unset($_SESSION['assign_user_id']);
	unset($_SESSION['details']);  
     
	 
}

	 
?>