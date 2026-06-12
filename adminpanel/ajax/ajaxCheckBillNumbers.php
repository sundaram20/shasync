<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



 if(isset($_POST['bill_no'])){
	  $sql = "SELECT * FROM daily_pickup WHERE UPPER(bill_no)= '".strtoupper($_POST['bill_no'])."' ";
	$results = mysqli_query($connNew,$sql);

	if(mysqli_num_rows($results)>0){
		echo json_encode(1);
	}
	else{
		echo json_encode(0);
	}
}
else{
	echo json_encode(0);
}	

?>