<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$ratePointId=$_REQUEST['ratePointId'];
$ratePointDetail = $_REQUEST['ratePointDetail'];
$data= json_decode($ratePointDetail,true);


if($ratePointDetail !=''){


if($data['ratePointId'] == $ratePointId){
	foreach($data['ratepoints'] as $val){
		$parentDropDown .= '<input class="form-group input-sm" name="ratepoints[]" value="'.$val.'"><br>';
	}
	echo $parentDropDown;
}else if($ratePointId==''){ 
	echo 'Please Select Rate Point.';
}else{
		$resCat = selectSql(TBL_RATE_POINTS,"where parent_id='".addslashes($ratePointId)."'",' ORDER BY `id`');
		  if(num_rows($resCat)){
			while($resultCat = $db->fetch_object2($resCat)){
				$parentDropDown .= '<input class="form-group input-sm" name="ratepoints[]" value="'.ucfirst($resultCat->description).'"><br>';
			}
		  }
		echo $parentDropDown;
	
	}

}else if($ratePointId!=''){
	$resCat = selectSql(TBL_RATE_POINTS,"where parent_id='".addslashes($ratePointId)."'",' ORDER BY `id`');
				  if(num_rows($resCat)){
					while($resultCat = $db->fetch_object2($resCat)){
						$parentDropDown .= '<input class="form-group input-sm" name="ratepoints[]" value="'.ucfirst($resultCat->description).'"><br>';
					}
				  }
				  echo $parentDropDown;		
}else{
		echo 'Please Select Rate Point.';
}
?>