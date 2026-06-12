<?php //session_start();
include("../config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/class.pagingClass.php");
$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$id=$_POST['id'];
if($_POST['Save'] == 'Edit'){
     $edit = "    UPDATE `".TBL_LEADS."` SET 
					`user_id` = '".addslashes(implode(',',$_POST['userId']))."',
					`client_id` = '".addslashes($_POST['clientId'])."',	
					`lead_status` = '".addslashes($_POST['leadStatus'])."',
					`work_status` = '".addslashes($_POST['workStatus'])."'
					WHERE `id` = '".$id."'"; 
						
	if(executeSql($edit)){			
		$_SESSION['successMsg'] = 'Leads details has been updated sucessfully.';
		header("location:manageLeads.php");
		exit;
	}
}
	
	     
?>