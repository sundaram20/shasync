<?php
include_once("../../config/auto_loader.php");


$sql= "SELECT * FROM ".TBL_VISIT." WHERE id_shop='".$_SESSION['shop']."' AND id='".$_REQUEST['id']."' ";

$count = $_REQUEST['count']+1;

$res = mysqli_query($connNew,$sql);

if($res){
	$objData = mysqli_fetch_object($res);
	$companyName = selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$objData->id_company.'" ');
	$contactPerson = selectColumn(TBL_CUSTOMER,'CONCAT(first_name," ",last_name)','WHERE id_customer="'.$objData->id_contacts.'" ');

	echo "<tr>
		<td>".$count.".</td>
		<td>".$companyName."</td>
		<td>".$contactPerson."</td>
            <td>".$objData->discussion_summary."</td>
            <td>".$objData->Total."</td>
            <td>".$objData->entertainment."</td>
            <td>".$objData->lunch."</td>
	 </tr>";

	  unset($_SESSION['editCart']);

      unset($_SESSION['editCart']['charges_total']);

      unset($_SESSION['editCart']['charges_price']);

      unset($_SESSION['editCart']['charges_description']);

      unset($_SESSION['editCart']['charges_total']);



      unset($_SESSION['followup_hotel_id']); 

      unset($_SESSION['followup_description']); 

      unset($_SESSION['followup_date']); 

      unset($_SESSION['followupCode']); 

      unset($_SESSION['followupstatus']); 

      unset($_SESSION['feedback_hotel_id']); 

      unset($_SESSION['feedback_description']); 

      unset($_SESSION['feedback_date']); 

      unset($_SESSION['feedback_Explode_Description']); 

      unset($_SESSION['feedback_Explode_Date']); 

      unset($_SESSION['feedback_Explode_visit_id']); 

      unset($_SESSION['feedback_Explode_id']); 

      unset($_SESSION['followup_Explode_id']); 

      unset($_SESSION['followup_Explode_visit_id']); 

      unset($_SESSION['followup_Explode_Description']); 

      unset($_SESSION['assign_feedback_user_id']);

      unset($_SESSION['assign_followup_user_id']);

      unset($_SESSION['assign_user_id']);

      unset($_SESSION['date_created']);

      unset($_SESSION['followup_date_created']);

      unset($_SESSION['feedback_date_created']);
      unset($_SESSION['feedbackstatus']); 
      unset($_SESSION['conclusion_type']); 
}

	 
?>