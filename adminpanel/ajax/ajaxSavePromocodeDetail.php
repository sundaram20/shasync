<?php
include_once("../../config/auto_loader.php");

if(!empty($_REQUEST['eId'])){

	$promo_code_edit	= $_POST['promo_code_edit'];
	$date_valid_from	 = $_POST['date_valid_from'];
	$date_valid_to 		 = $_POST['date_valid_to'];
	$peid = addslashes(encryptor('decrypt',$_REQUEST['eId']));
	$editSql = "UPDATE `".TBL_PROMO_CODE."` SET 
					`vaoucher_value` = '".addslashes($_POST['vaoucher_value'])."',
					`food_value` = '".addslashes($_POST['food_value'])."',	
					`status` = '".addslashes($_POST['status'])."',	
					`date_valid_from` = '".addslashes(date('Y-m-d',strtotime($date_valid_from)))."',	
					`date_valid_to` = '".addslashes(date('Y-m-d',strtotime($date_valid_to)))."'	,
					`last_modified` = '".currenDateTime()."',
					`last_modified_by` = '".$_SESSION['userId']."'
					WHERE `id` =  ".$peid."	";
						
		$res = executeSql($editSql);

		if($res){
				
			$sql2 = 	"UPDATE `".TBL_PROMO_CODE_DETAILS."` SET 
				`vaoucher_value` = '".addslashes($_POST['vaoucher_value'])."',
				`food_value` = '".addslashes($_POST['food_value'])."',	
				`status` = '".addslashes($_POST['status'])."',	
				`employee_name` = '".addslashes($_POST['employee_name'])."',
				`emp_title` = '".addslashes($_POST['emp_title'])."',											
				`date_valid_from` = '".addslashes(date('Y-m-d',strtotime($date_valid_from)))."',	
				`date_valid_to` = '".addslashes(date('Y-m-d',strtotime($date_valid_to)))."',
				`last_modified` = '".currenDateTime()."',
				`last_modified_by` = '".$_SESSION['userId']."'
				WHERE `id` IN  (".$promo_code_edit.")	";

			$res2 = executeSql($sql2);

			if($res2){
				echo '<p class="help-block"> Evoucher has been updated Sucessfully.</p><script>window.setTimeout(function() { window.location.href = "manageEvoucher.php?action=edit&page=1";}, 2000); </script>';

			}
			else{
				echo '<p class="help-block"> Error ! .</p><script>window.setTimeout(function() { window.location.href = "manageEvoucher.php?action=edit&page=1";}, 2000); </script>';
			}	
				
		}

					

}

?>