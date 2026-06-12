<?php

include_once("../config/auto_loader.php");

$teamSql = "SELECT id,name,ids_user_monthly_reporting FROM mst_team WHERE id_shop=6 AND status=1 AND ids_user_monthly_reporting !='' ";

$resTeam = mysqli_query($connNew,$teamSql);
$i=1;
while($rowTeam=mysqli_fetch_object($resTeam)){

	$reportTo = array();
	$reportToEmail = array();
	$reportTo=explode(',',$rowTeam->ids_user_monthly_reporting);

	

	foreach($reportTo as $key => $id ){
		array_push($reportToEmail,selectColumn(TBL_USERS,'email','where id="'.$id.'" '));
	}

	 $userSql="SELECT name,email FROM ".TBL_USERS." WHERE ".$rowTeam->id." IN (ids_team) ";
	$resUser = mysqli_query($connNew,$userSql);
	
	while($rowUser = mysqli_fetch_object($resUser)){
		
		echo 'Team No '.$i.' : '.$rowTeam->name.' Executive : '.$rowUser->name.' Email : '.$rowUser->email.'<br> Head :';
		print_r($reportToEmail);
		echo '<br>';	
		
	}
	$i++;
}

?>