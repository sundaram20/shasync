<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$hotelId = $_REQUEST['hotelId'];
$rate_id = encryptor('decrypt',$_REQUEST['rate_id']);
//////////////////////////////getting and showing season date//////////////////////////////////////////////////////
if($hotelId != '' && $rate_id!=''){
$resInclusion = executeSql("SELECT * from `".TBL_RATE_ASSIGN_DETAILS."` where hotel_id='".addslashes($hotelId)."' and rate_id='".addslashes($rate_id)."'");
$rowInclusion = $db->fetch_object2($resInclusion);

	$inclusionDetail= json_decode($rowInclusion->inclusion_detail,true);
	
	$resRateInclusion = executeSql("SELECT * from `".TBL_RATE_INCLUSION."`  where status='1' and type='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."'"); ?>
	<script> 
	<?php while($rowRateInclusion = $db->fetch_object2($resRateInclusion)){
	
		if($inclusionDetail[$rowRateInclusion->id]!=''){	
		
		echo 'document.getElementById("inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'").value="'.$inclusionDetail[$rowRateInclusion->id].'"; ';
		}else{
		echo 'document.getElementById("inclusion|'.$rowRateInclusion->id.'|'.$rowRateInclusion->type.'").value="0"; ';		
		}
	} 
	if($inclusionDetail['0']!=''){
	echo 'document.getElementById("extras").value="'.$inclusionDetail['0'].'"; ';
	}else{
	echo 'document.getElementById("extras").value="0"; ';
	}
	?>
	
	</script>
	<?php
}

?>