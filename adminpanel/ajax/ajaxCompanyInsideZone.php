<?php
	include_once("../../config/auto_loader.php");

	$sql = "SELECT ".TBL_COMPANY.".id_company,".TBL_USERS.".user_type FROM ".TBL_COMPANY." LEFT JOIN ".TBL_AREAS." ON ".TBL_COMPANY.".area =".TBL_AREAS.".id
			LEFT JOIN ".TBL_USERS." ON ".TBL_AREAS.".user_id=".TBL_USERS.".id
		 WHERE id_company='".$_REQUEST['id']."' AND (FIND_IN_SET (".TBL_COMPANY.".area,'".$_SESSION['teamMemberAreas']."') OR ".TBL_USERS.".user_type=2)  ";
	
	$res = mysqli_query($connNew,$sql);
	if($res){
		$idGot = mysqli_fetch_object($res);
		if($idGot !=""){
			echo json_encode(1);
		}
		else{
			echo json_encode(0);
		}
	}
	else{
		echo "problem in sql";
	}
exit;
?>