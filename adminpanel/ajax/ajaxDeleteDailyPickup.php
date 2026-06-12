<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//debugData($_REQUEST);die;
$value	=	$_REQUEST['value'];
$delSql  = "DELETE from `daily_pickup_details` where  id='".addslashes($_REQUEST['value'])."' ";



	

	
$Record=array();
	if(executeSql($delSql)){		

		$err = 0;

		$Record['successMsg'] = 'Pickup Details  has been deleted sucessfully.';

	}

echo json_encode($Record);	

?>