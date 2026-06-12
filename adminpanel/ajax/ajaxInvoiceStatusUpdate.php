<?php  include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




	
	/*echo "<pre>";
	print_r($_SESSION);
	echo "-------------request--------<br>";
	print_r($_REQUEST);
	echo "</pre>";
	exit;*/
	echo "UPDATE  `".INVOICE."`  SET 
									
									`lead_status`='".$_REQUEST['invoice_status']."',
										
										`received`= '".addslashes($_REQUEST['new_received'])."',
										`balance`= '".addslashes($_REQUEST['new_balance'])."'
									where  `id`='".$_REQUEST['invoice_id']."'";


	$updateInventory = executeSql("UPDATE  `".INVOICE."`  SET 
									
									`lead_status`='".$_REQUEST['invoice_status']."',
										
										`received`= '".addslashes($_REQUEST['new_received'])."',
										`balance`= '".addslashes($_REQUEST['new_balance'])."'
									where  `id`='".$_REQUEST['invoice_id']."'");

							
	
						
	
	
	
echo '<p class="help-block">'.addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date']))).' Invoice Status has been updated sucessfully.<br>Please Wait...</p>';	


?>